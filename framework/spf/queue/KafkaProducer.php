<?php
/**
 * Created by PhpStorm.
 *
 * @package
 * @author  XiaodongPan
 * @version $Id: KafkaProducer.php 2020-04-17 $
 */
namespace spf\queue;

use RdKafka\Producer;

class KafkaProducer
{
    private $instance = null;

    public function __construct(KafkaConf $conf = null)
    {
        if ($conf == null) {
            $this->instance = new Producer();
        } else {
            $this->instance = new Producer($conf);
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

    public function produce($topic, $msg, $partition = RD_KAFKA_PARTITION_UA, $flag = 0)
    {
        return $topic->produce($partition, $flag, $msg);
    }

    /**
     * 正常关闭
     * @param $timeout_ms
     */
    public function flush($timeout_ms)
    {
        $this->instance->flush($timeout_ms);
    }

    /**
     * 如果不关心发送尚未发送的消息，可以在调用flush()之前使用purge()
     */
    public function purge()
    {
        $this->instance->purge(RD_KAFKA_PURGE_F_QUEUE);
    }
}