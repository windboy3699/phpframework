<?php
/**
 * @todo 封装thrift
 * 服务端根据url路由到不同的service
 * 客户端通过new ThriftServiceFactory(\services\shop)然后调用service方法
 * 返回值结构体的定义
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

$loader = new \Thrift\ClassLoader\ThriftClassLoader();
$loader->registerNamespace('Thrift', FRAMEWORK_PATH . '/vendor/apache/thrift/lib/php/lib');
$loader->registerNamespace('services', SERVICES_PATH . '/shop-intf/php');
$loader->register();

if (php_sapi_name() == 'cli') {
    ini_set("display_errors", "stderr");
}

header('Content-Type', 'application/x-thrift');

if (php_sapi_name() == 'cli') {
    echo "\r\n";
}

$handler = new \services\shop\ShopServiceImpl();
$processor = new \services\shop\ShopServiceProcessor($handler);

if (php_sapi_name() == 'cli') {
    $transportFactory = new \Thrift\Factory\TTransportFactory();
    $protocolFactory = new \Thrift\Factory\TBinaryProtocolFactory(true, true);

    //作为cli方式运行，监听端口，官方实现，需socket调用
    $transport = new \Thrift\Server\TServerSocket('127.0.0.1', 9090);
    $server = new \Thrift\Server\TSimpleServer($processor, $transport, $transportFactory, $transportFactory, $protocolFactory, $protocolFactory);
    $server->serve();
} else {
    $transport = new \Thrift\Transport\TBufferedTransport(new \Thrift\Transport\TPhpStream(\Thrift\Transport\TPhpStream::MODE_R | \Thrift\Transport\TPhpStream::MODE_W));
    $protocol = new \Thrift\Protocol\TBinaryProtocol($transport, true, true);

    $transport->open();
    $processor->process($protocol, $protocol);
    $transport->close();
}