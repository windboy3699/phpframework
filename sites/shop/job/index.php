<?php
/**
 * Created by PhpStorm.
 *
 * @package
 * @author  XiaodongPan
 * @version $Id: index.php 2017-04-21 $
 * @example php index.php --class=ConsumeJob --start=1
 */
error_reporting(E_ALL & ~E_NOTICE);

ini_set('magic_quotes_runtime', 0);
ini_set('date.timezone', 'Asia/Shanghai');

require_once dirname(dirname(dirname(dirname(__FILE__)))) . '/framework/spf/SPF.php';
$config = require_once dirname(__FILE__).'/config/main.php';

$app = SPF\SPF::create($config);
$app->registerAutoloader();

$opts = getopt('', array(
    'class:'
));

$className = $app->getAppNamespace() . '\\' . $opts['class'];
if (!class_exists($className)) {
    exit("$className is not exist\n");
}
$job = new $className();
$job->run();