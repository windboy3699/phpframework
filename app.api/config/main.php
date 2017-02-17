<?php
/**
 * WebApplication执行所需项目配置信息
 */

$appPath = dirname(dirname(__FILE__));

return [
    'appPath' => $appPath,

    'appName'=> 'mobile.api',

    //自动加载目录
    'loadPath' => [
        'appcore' => dirname($appPath) . '/app.core/classes',
        'controllers' => $appPath . '/controllers',
        'models' => $appPath . '/models',
        'services' => $appPath . '/services',
        'interceptors' => $appPath . '/interceptors',
        'classes' => $appPath . '/classes',
    ],

    //配置文件目录
    'configPath' => [
        $appPath . '/config',
    ],

    'controllerPath' => $appPath . '/controllers',
];