<?php
/**
 * 项目入口文件
 * @package /Class
 * @author  XiaodongPan
 * @version $Id: index.php 2016-10-25 $
 */
error_reporting(E_ALL & ~E_NOTICE);

ini_set('magic_quotes_runtime', 0);
ini_set('date.timezone', 'Asia/Shanghai');

//目录定义
define('SPF_PATH', dirname(dirname(dirname(__FILE__))) . '/SPF_V1');
define('APP_PATH', dirname(__FILE__));
define('GLOBAL_PATH', dirname(APP_PATH) . '/global');

require_once SPF_PATH . '/SPF.php';

$runConfig = [
    'appPath' => APP_PATH,
    //自动加载目录
    'loadPath' => [
        'kernel' => GLOBAL_PATH . '/classes/kernel',
        'classes' => [
            GLOBAL_PATH . '/classes',
            APP_PATH . '/classes',
        ],
    ],
    //配置文件目录
    'configPath' => [
        APP_PATH . '/config',
        GLOBAL_PATH . '/config',
    ],
];

$spf = SPF::create($runConfig);
$spf->registerAutoloader();
$spf->run();