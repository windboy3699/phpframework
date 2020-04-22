<?php
/**
 * Created by PhpStorm.
 *
 * @package
 * @author  XiaodongPan
 * @version $Id: ThriftTestController.php 2020-04-22 $
 * @todo 封装thrift
 * 服务端根据url路由到不同的service
 * 客户端通过new ThriftServiceFactory(\services\shop)然后调用service方法
 * 返回值结构体的定义
 */
namespace shop;

use spf\routing\Controller as BaseController;

class TestController extends BaseController
{
    public function thriftAction()
    {
        $loader = new \Thrift\ClassLoader\ThriftClassLoader();
        $loader->registerNamespace('Thrift', FRAMEWORK_PATH . '/vendor/apache/thrift/lib/php/lib');
        $loader->registerNamespace('services\shop', SERVICES_PATH . '/shop-intf/php');
        $loader->register();

        try {
            //HTTP方式调用
            $socket = new \Thrift\Transport\THttpClient('service.shop.com', 80, '/index.php');

            //Socket方式调用，速度比HTTP快，需服务端开启进程
            //$socket = new \Thrift\Transport\TSocket('127.0.0.1', 9090);

            $transport = new \Thrift\Transport\TBufferedTransport($socket, 1024, 1024);
            $protocol = new \Thrift\Protocol\TBinaryProtocol($transport);
            $client = new \services\shop\ShopServiceClient($protocol);

            $transport->open();

            $ret = $client->sayHello("Thrift");
            echo $ret;

            $transport->close();

        } catch (\Thrift\Exception\TException $tx) {
            print 'TException: '.$tx->getMessage()."\n";
        }
    }
}