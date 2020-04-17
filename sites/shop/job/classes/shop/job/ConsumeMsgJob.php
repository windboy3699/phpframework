<?php
/**
 * Created by PhpStorm.
 *
 * @package
 * @author  XiaodongPan
 * @version $Id: ConsumeMsgJob.php 2020-04-17 $
 */
namespace shop\job;

use spf\queue\KafkaConf;
use spf\queue\KafkaConsumer;

class ConsumeMsgJob extends AbstractJob
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

        $conf = new KafkaConf();
        $conf->set('log_level', LOG_DEBUG);

        $consumer = new KafkaConsumer($conf);
        $consumer->addBrokers($brokers);

        $topic = $consumer->newTopic('test');
        $topic->consumeStart(0, RD_KAFKA_OFFSET_BEGINNING);

        while (true) {
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