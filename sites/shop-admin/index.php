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

require_once dirname(dirname(dirname(__FILE__))) . '/SPF/framework/SPF.php';
$config = require_once dirname(__FILE__).'/config/main.php';

$app = SPF\SPF::create($config);
$app->registerAutoloader();
SPF\Session\Session::start([], 'redis', $app->getRedis());
$app->run();