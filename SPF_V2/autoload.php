<?php
/**
 * Created by PhpStorm.
 *
 * @package
 * @author  XiaodongPan
 * @version $Id: autoload.php 2017-04-12 $
 */
class Autoloader
{
    const PREFIX_SPF = 'SPF';

    private $spfPath = '';

    private $appPath = '';

    private $classesPaths = [];

    private $suffixes = [
        'Controller',
        'Model',
        'Service',
        'Interceptor',
    ];

    public function __construct($appPath, $classesPaths = [])
    {
        $this->spfPath = dirname(dirname(__FILE__));
        $this->appPath = $appPath;
        $this->classesPaths = $classesPaths;
    }

    public function load($className)
    {
        if (class_exists($className)) {
            return ;
        }
        if (substr($className, 0, 3) == self::PREFIX_SPF) {
            $this->loadSPFClass($className);
        } elseif ($this->loadClasses($className) == false) {
            $this->loadSuffixClass($className);
        }
    }

    /**
     * 加载SPF框架类
     * 先加载SPF_Cache_Memcache -> SPF/Cache/Memcache.php
     * 否则加载SPF_Controller -> SPF/Controller/Controller.php
     * 否则加载SPF_Request -> SPF/Base/Request
     * @param $className
     * @throws SPF_Exception
     */
    private function loadSPFClass($className)
    {
        if ($className == self::PREFIX_SPF) {
            require_once $this->spfPath . DIRECTORY_SEPARATOR . 'SPF.php';
        }
        $classFile = str_replace('_', DIRECTORY_SEPARATOR, substr($className, 4));
        if (is_file($this->spfPath . DIRECTORY_SEPARATOR . $classFile .'.php')) {
            $classFile = $this->spfPath . DIRECTORY_SEPARATOR . $classFile .'.php';
        } elseif (is_dir($this->spfPath . DIRECTORY_SEPARATOR . $classFile)) {
            $classFile = $this->spfPath . DIRECTORY_SEPARATOR . $classFile . DIRECTORY_SEPARATOR . trim(substr($classFile, strrpos($classFile, DIRECTORY_SEPARATOR)), DIRECTORY_SEPARATOR) .'.php';
        } elseif (!strpos($classFile, DIRECTORY_SEPARATOR)) {
            $classFile = $this->spfPath . DIRECTORY_SEPARATOR . 'Base' . DIRECTORY_SEPARATOR . $classFile . '.php';
        }
        if (!is_file($classFile)) {
            return false;
        }
        return require_once $classFile;
    }

    /**
     * 加载特定后缀结尾的类
     * @param $className
     */
    private function loadSuffixClass($className)
    {
        $classlen = strlen($className);
        $path = '';
        foreach ($this->suffixes as $suffex) {
            $index = strrpos($className, $suffex);
            if($index && (($classlen - strlen($suffex)) == $index)) {
                $path = $this->appPath . DIRECTORY_SEPARATOR . strtolower($suffex);
                break;
            }
        }
        if ($path) {
            $segs = explode('_', $className);
            $fileName = end($segs);
            array_pop($segs);
            if ($segs) {
                array_walk($segs, function(&$v, $k){$v = strtolower($v);});
                $path .= DIRECTORY_SEPARATOR . implode(DIRECTORY_SEPARATOR, $segs);
            }
            $classPath = $path . DIRECTORY_SEPARATOR . $fileName . '.php';
            if (is_file($classPath)) {
                return require_once $classPath;
            }
        }
        return false;
    }

    /**
     * 加载/classes目录下的类
     * @param $className
     */
    private function loadClasses($className)
    {
        foreach ($this->classesPaths as $classPath) {
            $segs = explode('_', $className);
            $fileName = end($segs);
            array_pop($segs);
            if ($segs) {
                array_walk($segs, function(&$v, $k){$v = strtolower($v);});
                $classPath .= DIRECTORY_SEPARATOR . implode(DIRECTORY_SEPARATOR, $segs);
            }
            $classPath .= DIRECTORY_SEPARATOR . $fileName . '.php';
            if (is_file($classPath)) {
                return require_once $classPath;
            }
        }
        return false;
    }
}