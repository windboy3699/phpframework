<?php
/**
 * SPF (Sports PHP Framework - 体育部PHP框架)
 * 框架主文件，并且提供常用的config、db、cache等核心组件
 * @package /SPF
 * @author  XiaodongPan
 * @version $Id: SPF.php 2016-10-26 $
 */
final class SPF
{
    private static $instance;

    private $options = [];

    private $router;

    private $request;

    private $components = array();

    private function __construct($options)
    {
        $this->options = array_merge($this->options, $options);
    }

    /**
     * 生成SPF实例
     * @param $options
     * @return SPF
     */
    public static function create($options)
    {
        if (!self::$instance) {
            self::$instance = new SPF($options);
        }
        return self::$instance;
    }

    /**
     * 获取SPF实例
     * @return SPF
     */
    public static function getInstance()
    {
        if (!self::$instance) {
            throw new SPF_Exception('SPF Instance Not Create');
        }
        return self::$instance;
    }

    /**
     * 执行框架主流程
     */
    public function run()
    {
        $this->router = new SPF_Controller_Router();
        $this->router->setMapping($this->getConfig('mappings', 'route', []));
        $this->router->setControllerPath($this->options['controllerPath']);
        $this->router->parse();

        //执行拦截器before
        $interceptors = [];
        $interceptoreClasses = $this->getInterceptorClasses($this->router->getControllerNameNoSuffix());
        $step = SPF_Interceptor::STEP_CONTINUE;
        foreach ($interceptoreClasses as $interceptorClass) {
            if (!class_exists($interceptorClass, true)) {
                continue;
            }
            $interceptor = new $interceptorClass;
            $interceptors[] = $interceptor;
            $step = $interceptor->before();
            if ($step != SPF_Interceptor::STEP_CONTINUE) {
                break;
            }
        }
        //执行主流程
        if ($step != SPF_Interceptor::STEP_EXIT) {
            $controllerName = $this->router->getControllerName();
            $actionName = $this->router->getActionName();
            $controller = new $controllerName;
            $controller->$actionName();
        }
        //执行拦截器after
        if ($interceptors) {
            $interceptors = array_reverse($interceptors);
            foreach ($interceptors as $interceptor) {
                $step = $interceptor->after();
                if ($step != SPF_Interceptor::STEP_CONTINUE) {
                    break;
                }
            }
        }
        return true;
    }

    /**
     * 根据controller获取拦截器
     * @param $controller
     * @return array
     */
    private function getInterceptorClasses($controller)
    {
        $finalClasses = [];
        $globalClasses = $this->getConfig("global", "interceptor", []);
        if ($globalClasses && !is_array($globalClasses)) {
            $globalClasses = [$globalClasses];
        }
        $classes = $this->getConfig($controller, "interceptor", []);
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
     * @return mixed
     */
    public function getRouter()
    {
        return $this->router;
    }

    /**
     * 获取Group
     * @return mixed
     */
    public function getGroup()
    {
        return $this->router->getGroup();
    }

    /**
     * 获取Controller
     * @return mixed
     */
    public function getController()
    {
        return $this->router->getController();
    }

    /**
     * 获取Action
     * @return mixed
     */
    public function getAction()
    {
        return $this->router->getAction();
    }

    /**
     * 获取Request对象
     * @return SPF_Request
     */
    public function getRequest()
    {
        if (!$this->request) {
            $this->request = new SPF_Request();
        }
        return $this->request;
    }

    /**
     * 获取配置
     * @param $name
     * @param string $file
     * @param null $default
     * @return mixed
     */
    public function getConfig($name, $file = 'common', $default = null)
    {
        if (!isset($this->components['config'])) {
            $this->components['config'] = new SPF_Config($this->options['configPath']);
        }
        return $this->components['config']->get($name, $file, $default);
    }

    /**
     * 获取Db实例
     * @param $dbname
     * @param bool|false $alwaysMaster
     * @return mixed
     * @throws SPF_Exception
     */
    public function getDb($dbname, $alwaysMaster = false)
    {
        $config = $this->getConfig('db_' . $dbname, 'server');
        return SPF_Db::getInstance($config, $alwaysMaster);
    }

    /**
     * 获取Memcache实例
     * @return mixed
     * @throws SPF_Exception
     */
    public function getMemcache()
    {
        if (!isset($this->components['memcache'])) {
            $this->components['memcache'] = new SPF_Cache_Memcache($this->getConfig('memcache', 'server'));
        }
        return $this->components['memcache'];
    }

    /**
     * 获取Redis实例
     * @return mixed
     * @throws SPF_Exception
     */
    public function getRedis()
    {
        if (!isset($this->components['redis'])) {
            $this->components['redis'] = new SPF_Cache_Redis($this->getConfig('redis', 'server'));
        }
        return $this->components['redis'];
    }

    /**
     * 注册自动加载方法
     */
    public function registerAutoloader()
    {
        $paths = $this->options['loadPath'];
        $paths['spf'] = dirname(__FILE__);
        SPF_Autoloader::setPaths($paths);
        spl_autoload_register(array('SPF_Autoloader', 'loadClass'));
    }
}

require_once dirname(__FILE__) . '/Base/Autoloader.php';

//调试断点输出
function p($var)
{
    echo '<pre>';
    print_r($var);
    exit;
}