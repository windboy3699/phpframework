<?php
/**
 * SPF框架主文件
 *
 * @package
 * @author  XiaodongPan
 * @version $Id: SPF.php 2017-04-17 $
 */
namespace spf;

use spf\routing\MapRouter;
use spf\routing\RuleRouter;
use spf\interceptor\Interceptor;
use spf\http\Request;
use spf\config\Repository as ConfigRepository;
use spf\db\Factory as DbFactory;
use spf\cache\Memcache;
use spf\cache\Redis;

class SPF
{
    /**
     * app instance
     *
     * @var SPF
     */
    private static $app;

    /**
     * SPF路径
     *
     * @var string
     */
    private $spfPath;

    /**
     * App路径
     *
     * @var string
     */
    private $appPath;

    /**
     * 自动加载类的目录
     *
     * @var array
     */
    private $autoloadPaths = [];

    /**
     * 加载配置文件的目录
     *
     * @var array
     */
    private $loadConfigPaths = [];

    /**
     * 路由模式 map|rule
     *
     * @var string
     */
    private $routeMode;

    /**
     * 控制器路径
     * @var string
     */
    private $controllerNamespace;

    /**
     * 路由器
     *
     * @var MapRouter|RuleRouter
     */
    private $router;

    /**
     * 请求组件
     *
     * @var Request
     */
    private $request;

    /**
     * 组件实例
     *
     * @var array
     */
    private $components = [];

    /**
     * SPF constructor.
     *
     * @param $configure
     */
    private function __construct($configure)
    {
        $this->appPath = $configure['appPath'];
        $this->autoloadPaths = $configure['autoloadPaths'];
        $this->loadConfigPaths = $configure['loadConfigPaths'];
        if (isset($configure['routeMode'])) {
            $this->routeMode = $configure['routeMode'];
        }
        if (isset($configure['controllerNamespace'])) {
            $this->controllerNamespace = $configure['controllerNamespace'];
        }
    }

    /**
     * 生成App实例
     *
     * @return SPF
     */
    public static function create($configure)
    {
        self::$app = new self($configure);
        return self::$app;
    }

    /**
     * 获取SPF实例
     *
     * @return SPF
     */
    public static function app()
    {
        return self::$app;
    }

    /**
     * 获取App路径
     *
     * @return string
     */
    public function getAppPath()
    {
        return $this->appPath;
    }

    /**
     * 执行框架主流程
     */
    public function run()
    {
        //执行拦截器before
        $interceptors = [];
        $interceptoreClasses = $this->getInterceptorClasses($this->getRouter()->getController(), $this->getRouter()->getAction());
        $step = Interceptor::STEP_CONTINUE;
        foreach ($interceptoreClasses as $interceptorClass) {
            if (!class_exists($interceptorClass, true)) {
                continue;
            }
            $interceptor = new $interceptorClass;
            $interceptors[] = $interceptor;
            $step = $interceptor->before();
            if ($step != Interceptor::STEP_CONTINUE) {
                break;
            }
        }
        //执行主流程
        if ($step != Interceptor::STEP_EXIT) {
            $className = $this->getRouter()->getController();
            $methodName = $this->getRouter()->getAction();
            $controller = new $className;
            $controller->$methodName();
        }
        //执行拦截器after
        if ($interceptors) {
            $interceptors = array_reverse($interceptors);
            foreach ($interceptors as $interceptor) {
                $step = $interceptor->after();
                if ($step != Interceptor::STEP_CONTINUE) {
                    break;
                }
            }
        }
        return true;
    }

    /**
     * 根据controller获取拦截器
     *
     * @param $controller
     * @return array
     */
    private function getInterceptorClasses($controller, $action)
    {
        $finalClasses = [];
        $globalClasses = $this->getConfig("global", "interceptor", []);
        if ($globalClasses && !is_array($globalClasses)) {
            $globalClasses = [$globalClasses];
        }
        $key = $controller . "::" . $action;
        $classes = $this->getConfig($key, "interceptor", []);
        if (!$classes) {
            $classes = $this->getConfig("default", "interceptor", []);
        }
        if ($classes && !is_array($classes)) {
            $classes = array($classes);
        }
        foreach ($globalClasses as $value) {
            $finalClasses[$value] = $value;
        }
        foreach ($classes as $value) {
            if (preg_match('/^!/', $value)) {
                $value = substr($value, 1);
                unset($finalClasses[$value]);
            } else {
                $finalClasses[$value] = $value;
            }
        }
        return $finalClasses;
    }

    /**
     * 获取Router
     */
    public function getRouter()
    {
        if (!empty($this->router)) {
            return $this->router;
        }
        if ($this->routeMode == 'map') {
            $this->router = new MapRouter();
            $this->router->setMappings($this->getConfig('mappings', 'route', []));
            $this->router->parse();
        } elseif ($this->routeMode == 'rule' || empty($this->routeMode)) {
            $this->router = new RuleRouter();
            $this->router->setControllerNamespace($this->controllerNamespace);
            $this->router->parse();
        }
        return $this->router;
    }

    /**
     * 获取Request对象
     *
     * @return Request
     */
    public function getRequest()
    {
        if (!$this->request) {
            $this->request = new Request();
        }
        return $this->request;
    }

    /**
     * 获取配置
     *
     * @param $name
     * @param string $file
     * @param null $default
     * @return mixed
     */
    public function getConfig($name, $file = 'common', $default = null)
    {
        if (!isset($this->components['configRepository'])) {
            $this->components['configRepository'] = new ConfigRepository($this->loadConfigPaths);
        }
        return $this->components['configRepository']->get($name, $file, $default);
    }

    /**
     * 获取Db实例
     *
     * @param $dbname
     * @param bool|false $alwaysMaster
     * @return mixed
     * @throws \SPF\Db\Exception
     */
    public function getDb($dbname, $alwaysMaster = false)
    {
        $config = $this->getConfig('db_' . $dbname, 'server');
        return DbFactory::getInstance($config, $alwaysMaster);
    }

    /**
     * 获取Memcache实例
     *
     * @return mixed
     * @throws \SPF\Cache\Exception
     */
    public function getMemcache()
    {
        if (!isset($this->components['memcache'])) {
            $this->components['memcache'] = new Memcache($this->getConfig('memcache', 'server'));
        }
        return $this->components['memcache'];
    }

    /**
     * 获取Redis实例
     *
     * @return mixed
     * @throws \SPF\Cache\Exception
     */
    public function getRedis()
    {
        if (!isset($this->components['redis'])) {
            $this->components['redis'] = new Redis($this->getConfig('redis', 'server'));
        }
        return $this->components['redis'];
    }

    /**
     * 注册自动加载
     */
    public function registerAutoloader()
    {
        spl_autoload_register(array(self::app(), 'autoload'));
    }

    /**
     * 自动加载类
     *
     * @param $className
     * @return bool|mixed
     */
    public function autoload($className)
    {
        $className = ltrim($className, "\\");
        $loadPaths = $this->autoloadPaths;
        $lastDsPos = strrpos($className, "\\");
        if ($lastDsPos !== false) {
            $relatNs = substr($className, 0, $lastDsPos);
            $relatNs = strtolower($relatNs);
            $lastName = substr($className, $lastDsPos + 1);
            $fileName  = str_replace("\\", DIRECTORY_SEPARATOR, $relatNs) . DIRECTORY_SEPARATOR . $lastName . '.php';
        } else {
            $fileName = $className . '.php';
        }
        if (preg_match('/^spf/', $className)) {
            $classFile = dirname(dirname(__FILE__)) . DIRECTORY_SEPARATOR . $fileName;
            if (is_file($classFile)) {
                require_once $classFile;
                return true;
            } else {
                return false;
            }
        }
        foreach ($this->libAutoloadPaths() as $name => $path) {
            if (preg_match('/^' . addslashes($name) . '/', $className)) {
                $classFile = $path . DIRECTORY_SEPARATOR . $fileName;
                if (is_file($classFile)) {
                    require_once $classFile;
                    return true;
                } else {
                    return false;
                }
            }
        }
        foreach ($loadPaths as $path) {
            $classFile = $path . DIRECTORY_SEPARATOR . $fileName;
            if (is_file($classFile)) {
                require_once $classFile;
                return true;
            }
        }
        return false;
    }

    /**
     * lib autoload路径
     *
     * @return array
     */
    private function libAutoloadPaths()
    {
        $path = dirname(dirname(__FILE__)) . '/lib';
        return [
            'Monolog\\' => $path . '/lib/Monolog',
            'Psr\\Log\\' => $path . '/lib/Psr/Log',
        ];
    }
}