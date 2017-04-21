<?php
/**
 * SPF
 *
 * @package
 * @author  XiaodongPan
 * @version $Id: SPF.php 2017-04-17 $
 */
use SPF\Application\WebApplication;

class SPF
{
    private static $app;

    private static $autoloadPsr4 = [];

    /**
     * 生成App实例
     * @return WebApplication
     */
    public static function createWebApplication()
    {
        if (!self::$app) {
            self::$app = new WebApplication();
        }
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
     * 注册自动加载
     *
     * @param array $autoloadPsr4
     */
    public static function registerAutoloader(array $autoloadPsr4)
    {
        self::$autoloadPsr4 = $autoloadPsr4;
        spl_autoload_register(array('SPF', 'autoload'));
    }

    /**
     * 自动加载类
     *
     * @param $className
     * @return bool|mixed
     */
    public static function autoload($className)
    {
        $className = ltrim($className, '\\');
        $psr4 = array_merge(self::baseAutoloadPsr4(), self::$autoloadPsr4);
        foreach ($psr4 as $namespacePrefix => $paths) {
            $pattern = '/^' . addslashes($namespacePrefix) . '/';
            if (preg_match($pattern, $className)) {
                $suffixNamespace = str_replace($namespacePrefix, '', $className);
                if (empty($suffixNamespace)) {
                    return false;
                }
                $lastDsPos = strrpos($suffixNamespace, '\\');
                if ($lastDsPos !== false) {
                    $relatNs = substr($suffixNamespace, 0, $lastDsPos);
                    $namespacePrefix == 'SPF' || $relatNs = strtolower($relatNs);
                    $lastName = substr($suffixNamespace, $lastDsPos + 1);
                    $fileName  = str_replace('\\', DIRECTORY_SEPARATOR, $relatNs) . DIRECTORY_SEPARATOR . $lastName . '.php';
                } else {
                    $fileName = $suffixNamespace . '.php';
                }
                foreach ($paths as $path) {
                    $classFile = $path . DIRECTORY_SEPARATOR . $fileName;
                    if (is_file($classFile)) {
                        require_once $classFile;
                        return true;
                    }
                }
                break;
            }
        }
        return false;
    }

    public static function baseAutoloadPsr4()
    {
        return [
            'SPF' => [dirname(__FILE__)],
            'Monolog\\' => [SPF_PATH . '/lib/Monolog'],
            'Psr\\Log\\' => [SPF_PATH . '/lib/Psr/Log'],
        ];
    }
}

function p($var)
{
    echo '<pre>';
    print_r($var);
    exit;
}