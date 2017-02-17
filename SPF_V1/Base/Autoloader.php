<?php
/**
 * 自动加载
 * @package /SPF/Base
 * @author  XiaodongPan
 * @version $Id: Autoloader.php 2016-10-25 $
 */
class SPF_Autoloader
{
    private static $paths = [];

    /**
     * 路径设置
     * @param $paths
     */
    public static function setPaths($paths)
    {
        isset($paths['spf']) && self::$paths['spf'] = $paths['spf'];
        isset($paths['appcore']) && self::$paths['appcore'] = $paths['appcore'];
        isset($paths['controllers']) && self::$paths['controllers'] = $paths['controllers'];
        isset($paths['models']) && self::$paths['models'] = $paths['models'];
        isset($paths['services']) && self::$paths['services'] = $paths['services'];
        isset($paths['interceptors']) && self::$paths['interceptors'] = $paths['interceptors'];
        isset($paths['classes']) && self::$paths['classes'] = $paths['classes'];
    }

    public static function loadClass($className)
    {
        if (class_exists($className)) {
            return ;
        }
        if (strpos($className, 'SPF') !== false) {
            self::loadSPFClass($className);
        } elseif (strpos($className, 'Sportscore_') !== false) {
            self::loadSportscoreClass($className);
        } else {
            self::loadAppClass($className);
        }
    }

    /**
     * 加载SPF框架类
     * @param $className
     * @throws SPF_Exception
     */
    private static function loadSPFClass($className)
    {
        if ($className == 'SPF') {
            require_once self::$paths['spf'] . '/SPF.php';
        }
        $classFile = str_replace('_', DIRECTORY_SEPARATOR, substr($className, 4));
        if (is_file(self::$paths['spf'] . DIRECTORY_SEPARATOR . $classFile .'.php')) {
            //加载SPF_Cache_Memcache => SPF/Cache/Memcache.php
            $classFile = self::$paths['spf'] . DIRECTORY_SEPARATOR . $classFile .'.php';
        } elseif (is_dir(self::$paths['spf'] . DIRECTORY_SEPARATOR . $classFile)) {
            //加载SPF_Controller => SPF/Controller/Controller.php
            $classFile = self::$paths['spf'] . DIRECTORY_SEPARATOR . $classFile . DIRECTORY_SEPARATOR . trim(substr($classFile, strrpos($classFile, DIRECTORY_SEPARATOR)), DIRECTORY_SEPARATOR) .'.php';
        } elseif (!strpos($classFile, DIRECTORY_SEPARATOR)) {
            //加载SPF_Request => SPF/Base/Request
            $classFile = self::$paths['spf'] . DIRECTORY_SEPARATOR . 'Base' . DIRECTORY_SEPARATOR . $classFile . '.php';
        }
        if (!is_file($classFile)) {
            throw new SPF_Exception('自动加载的类不存在：'. $className);
        }
        require_once $classFile;
    }

    private static function loadSportscoreClass($className)
    {
        $className = str_replace('Sportscore_', '', $className);
        $path = self::$paths['appcore'];
        if (empty($path)) {
            return ;
        }
        $segs = explode('_', $className);
        $fileName = end($segs);
        array_pop($segs);
        if ($segs) {
            array_walk($segs,function(&$v,$k){$v = strtolower($v);});
            $path .= DIRECTORY_SEPARATOR . implode(DIRECTORY_SEPARATOR, $segs);
        }
        $classPath = $path . DIRECTORY_SEPARATOR . $fileName . '.php';
        if (is_file($classPath)) {
            require_once $classPath;
        } else {
            return ;
        }
    }

    /**
     * 加载App下Controller,Model,Service结尾的类
     * @param $className
     */
    private static function loadAppClass($className)
    {
        if (strpos($className, 'Model')) {
            $path = self::$paths['models'];
        } elseif (strpos($className, 'Service')) {
            $path = self::$paths['services'];
        } elseif (strpos($className, 'Controller')) {
            $path = self::$paths['controllers'];
        } elseif (strpos($className, 'Interceptor')) {
            $path = self::$paths['interceptors'];
        } else {
            $path = self::$paths['classes'];
        }
        if (empty($path)) {
            return ;
        }
        $segs = explode('_', $className);
        $fileName = end($segs);
        array_pop($segs);
        if ($segs) {
            array_walk($segs,function(&$v,$k){$v = strtolower($v);});
            $path .= DIRECTORY_SEPARATOR . implode(DIRECTORY_SEPARATOR, $segs);
        }
        $classPath = $path . DIRECTORY_SEPARATOR . $fileName . '.php';
        if (is_file($classPath)) {
            require_once $classPath;
        } else {
            return ;
        }
    }
}