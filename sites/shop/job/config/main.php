<?php
/**
 * 运行应用所需基本配置和具体业务无关
 * 应用建立后这里一般不再新增
 */
$basePath = dirname(dirname(dirname(__FILE__)));
return [
    'appPath' => dirname(__FILE__) . '/..',

    'appNamespace' => 'shop\job',

    'classPaths' => [
        $basePath . '/classes',
        $basePath . '/job/classes',
    ],

    'configPaths' => [
        $basePath . '/job/config',
        $basePath . '/config',
    ],
];