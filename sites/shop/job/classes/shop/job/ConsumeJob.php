<?php
/**
 * Created by PhpStorm.
 *
 * @package
 * @author  XiaodongPan
 * @version $Id: ConsumeMsgJob.php 2020-04-17 $
 */
namespace shop\job;

use RdKafka\Conf;
use RdKafka\Consumer;

class ConsumeJob extends AbstractJob
{
    public function getOptArgs()
    {
        return array(
        );
    }

    public function run()
    {
        $kafkaConf = $this->app->getConfig('kafka', 'server');
        $brokers = $kafkaConf['brokers'];

        $conf = new Conf();
        $conf->set('log_level', LOG_DEBUG);

        $consumer = new Consumer($conf);
        $consumer->addBrokers($brokers);

        $topic = $consumer->newTopic('test');
        /**
         * 第一个参数是要使用的分区
         * 第二个参数是开始消费的偏移量
         * RD_KAFKA_OFFSET_BEGINNING 从头开始消费
         * RD_KAFKA_OFFSET_END 最后一条消费
         * RD_KAFKA_OFFSET_STORED 最后一条消费的offset记录开始消费
         *
         */
        $topic->consumeStart(0, RD_KAFKA_OFFSET_BEGINNING);

        while (true) {
            /**
             * 第一个参数是分区
             * 第二个参数是超时
             */
            $msg = $topic->consume(0, 1000);
            if (null === $msg || $msg->err === RD_KAFKA_RESP_ERR__PARTITION_EOF) {
                sleep(1);
                continue;
            } elseif ($msg->err) {
                echo $msg->errstr(), "\n";
                break;
            } else {
                echo $msg->payload, "\n";
            }
        }
    }
}