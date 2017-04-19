<?php
/**
 * 应用入口文件
 *
 * @package
 * @author  XiaodongPan
 * @version $Id: index.php 2017-04-18 $
 */
error_reporting(E_ALL & ~E_NOTICE);

ini_set('magic_quotes_runtime', 0);
ini_set('date.timezone', 'Asia/Shanghai');

define('SPF_PATH', dirname(dirname(dirname(__FILE__))) . '/SPF_V2');
define('SITES_PATH', dirname(dirname(__FILE__)));
define('APP_PATH', dirname(__FILE__));

require_once SPF_PATH . '/framework/SPF.php';

$autoloadPsr4 = [
    'Shop\\Core\\' => [SITES_PATH . '/shop-core/' . 'classes'],
    'Game\\Core\\' => [SITES_PATH . '/game-core/' . 'classes'],
    'App\\Model\\' => [APP_PATH . '/model'],
    'App\\Service\\' => [APP_PATH . '/service'],
    'App\\Controller\\' => [APP_PATH . '/controller'],
    'App\\Interceptor\\' => [APP_PATH . '/interceptor'],
    'App\\' => [APP_PATH . '/classes'],
];

$loadConfigPaths = [
    APP_PATH . '/config',
    SITES_PATH . '/shop-core/config',
    SITES_PATH . '/game-core/config',
];

SPF::registerAutoloader($autoloadPsr4);
SPF::createWebApplication(APP_PATH, $loadConfigPaths)->run();
