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
define('SPF_PATH', dirname(dirname(__FILE__)) . '/SPF_V1');
define('APP_PATH', dirname(__FILE__));

//执行框架流程
require_once SPF_PATH . '/SPF.php';
$config = require_once APP_PATH . '/config/main.php';
$spf = SPF::create($config);
$spf->registerAutoloader();
$spf->run();