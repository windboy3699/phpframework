<?php
/**
 * 框架主文件，并且提供常用的config、db、cache等核心组件
 *
 * @package SPF.Application
 * @author  XiaodongPan
 * @version $Id: WebApplication.php 2017-04-12 $
 */
namespace SPF\Application;

use SPF\Base\Config;
use SPF\Routing\MapRouter;
use SPF\Base\Request;
use SPF\Base\Interceptor;

class WebApplication
{
    /**
     * @var \SPF\Routing\Router
     */
    private $router;

    /**
     * @var \SPF\Base\Request
     */
    private $request;

    /**
     * @var string
     */
    private $appPath;

    /**
     * @var array
     */
    private $loadConfigPaths;

    /**
     * @var array
     */
    private $components = [];

    /**
     * WebApplication constructor.
     *
     * @param $appPath
     * @param $loadConfigPaths
     */
    public function __construct($appPath, $loadConfigPaths)
    {
        $this->appPath = $appPath;
        $this->loadConfigPaths = $loadConfigPaths;
    }

    /**
     * 执行框架主流程
     */
    public function run()
    {
        $this->router = new MapRouter();
        $this->router->setMappings($this->getConfig('mappings', 'route', []));
        $this->router->mapping();

        //执行拦截器before
        $interceptors = [];
        $interceptoreClasses = $this->getInterceptorClasses($this->router->getController(), $this->router->getAction());
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
            $className = $this->router->getController();
            $methodName = $this->router->getAction();
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
        $key = $action == MapRouter::DEFAULT_ACTION ? $controller : $controller . "@" . $action;
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
     *
     * @return SPF_Controller_Router
     */
    public function getRouter()
    {
        return $this->router;
    }

    /**
     * 获取Request对象
     *
     * @return SPF_Request
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
        if (!isset($this->components['config'])) {
            $this->components['config'] = new Config($this->loadConfigPaths);
        }
        return $this->components['config']->get($name, $file, $default);
    }

    /**
     * 获取Db实例
     *
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
     *
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
     *
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
}