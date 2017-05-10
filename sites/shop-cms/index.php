<?php
/**
 * Created by PhpStorm.
 *
 * @package
 * @author  XiaodongPan
 * @version $Id: index.php 2017-04-24 $
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
    'App\\Model\\' => [APP_PATH . '/model'],
    'App\\Service\\' => [APP_PATH . '/service'],
    'App\\Controller\\' => [APP_PATH . '/controller'],
    'App\\Interceptor\\' => [APP_PATH . '/interceptor'],
    'App\\' => [APP_PATH . '/classes'],
];

$configPaths = [
    APP_PATH . '/config',
    SITES_PATH . '/shop-core/config',
];

SPF\SPF::registerAutoloader($autoloadPsr4);
$app = SPF\SPF::createWebApplication();
$app->setRouteModeRule();
$app->setConfigPaths($configPaths);
SPF\Session\Session::start([], 'redis', $app->getRedis());
$app->run();
