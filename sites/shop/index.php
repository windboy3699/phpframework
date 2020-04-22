<?php
/**
 * Created by PhpStorm.
 *
 * @package
 * @author  XiaodongPan
 * @version $Id: index.php 2020-04-22 $
 */
error_reporting(E_ALL & ~E_NOTICE);

ini_set('magic_quotes_runtime', 0);
ini_set('date.timezone', 'Asia/Shanghai');

define('FRAMEWORK_PATH', dirname(dirname(dirname(__FILE__))) . '/framework');
define('SERVICES_PATH', dirname(dirname(dirname(__FILE__))) . '/services');

require_once FRAMEWORK_PATH . '/spf/SPF.php';
$config = require_once dirname(__FILE__) . '/config/main.php';

$app = spf\SPF::create($config);
$app->registerAutoloader();
$app->run();