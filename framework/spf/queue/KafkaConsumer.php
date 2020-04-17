<?php
/**
 * Created by PhpStorm.
 *
 * @package
 * @author  XiaodongPan
 * @version $Id: KafkaConsumer.php 2020-04-17 $
 */
namespace spf\queue;

use RdKafka\Consumer;

class KafkaConsumer
{
    private $instance = null;

    public function __construct(KafkaConf $conf = null)
    {
        if ($conf == null) {
            $this->instance = new Consumer();
        } else {
            $this->instance = new Consumer($conf);
        }
    }

    /**
     * @param $brokers ("10.0.0.1,10.0.0.2")
     */
    public function addBrokers($brokers)
    {
        $this->instance->addBrokers($brokers);
    }

    public function newTopic($name, KafkaTopicConf $conf = null)
    {
        if ($conf == null) {
            return $this->instance->newTopic($name);
        } else {
            return $this->instance->newTopic($name, $conf);
        }
    }

    public function consumeStart($topic, $partition = 0, $offset = RD_KAFKA_OFFSET_BEGINNING)
    {
        return $topic->consumeStart($partition, $offset);
    }

    public function consume($topic, $partition = 0, $timeout = 1000)
    {
        return $topic->consume($partition, $timeout);
    }
}