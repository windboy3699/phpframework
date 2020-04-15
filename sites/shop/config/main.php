<?php
/**
 * 运行应用所需基本配置和具体业务无关
 * 应用建立后这里一般不再新增
 */
$appPath = dirname(dirname(__FILE__));
$sitesPath = dirname(dirname(dirname(__FILE__)));
return [
    'appPath' => dirname(__FILE__) . '/..',

    'autoloadPaths' => [
        [$appPath . '/classes'],
        [$appPath . '/web/classes'],
        [$appPath . '/job/classes'],
    ],

    'loadConfigPaths' => [
        $appPath . '/config',
    ],

    'routeMode' => 'rule',
];