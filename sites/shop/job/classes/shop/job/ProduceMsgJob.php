<?php
/**
 * Created by PhpStorm.
 *
 * @package
 * @author  XiaodongPan
 * @version $Id: ProduceMsgJob.php 2020-04-17 $
 */
namespace shop\job;

use spf\queue\KafkaConf;
use spf\queue\KafkaProducer;

class ProduceMsgJob extends AbstractJob
{
    public function getOptArgs()
    {
        return array(
            'info:',
        );
    }

    public function run()
    {
        $msg = $this->getCommendArg('info');

        $kafkaConf = $this->app->getConfig('kafka', 'server');
        $brokers = $kafkaConf['brokers'];

        $conf = new KafkaConf();
        $conf->set('log_level', LOG_DEBUG);

        $producer = new KafkaProducer($conf);
        $producer->addBrokers($brokers);

        $topic = $producer->newTopic('test');
        $topic->produce(RD_KAFKA_PARTITION_UA, 0, $msg);
    }
}