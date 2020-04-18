<?php
/**
 * Created by PhpStorm.
 *
 * @package
 * @author  XiaodongPan
 * @version $Id: ProduceMsgJob.php 2020-04-17 $
 */
namespace shop\job;

use RdKafka\Conf;
use RdKafka\Producer;

class ProduceJob extends AbstractJob
{
    public function getOptArgs()
    {
        return array(
            'msg:',
        );
    }

    public function run()
    {
        $msg = $this->getCommendArg('msg');

        $kafkaConf = $this->app->getConfig('kafka', 'server');
        $brokers = $kafkaConf['brokers'];

        $conf = new Conf();
        $conf->set('log_level', LOG_DEBUG);
        //$conf->set('debug', 'all');

        $producer = new Producer($conf);
        $producer->addBrokers($brokers);

        $topic = $producer->newTopic('test');

        /**
         * 第一个参数是分区，RD_KAFKA_PARTITION_UA代表未分配，并让librdkafka自动选择分区
         * 第二个参数是消息标志，应为0或RD_KAFKA_MSG_F_BLOCK以在完整队列上阻止生产
         * 第三个参数是消息内容
         */
        $topic->produce(RD_KAFKA_PARTITION_UA, 0, $msg);
    }
}