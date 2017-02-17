<?php
/**
 * Redis队列驱动类
 * @package /SPF/Queue
 * @author XiaodongPan
 * @version $Id Redis.php 2016-10-25$
 */
class SPF_Queue_Redis implements SPF_Queue_Interface
{
    private $redis = null;

    private $name = 'queue';

    public function __construct($name, SPF_Cache_Redis $redis)
    {
        $this->redis = $redis;
        $this->name = $name;
    }

    /**
     * 队列中元素总计
     * @return int
     */
    public function count()
    {
        return $this->redis->lSize($this->name);
    }

    /**
     * 发送消息
     * @param mixed $message
     */
    public function send($message)
    {
        $this->redis->lpush($this->name, $message);
    }

    /**
     * 获取消息
     * @param int $number
     * @return mixed
     */
    public function receive($number = 0)
    {
        $number || $number = -1;
        $data = array();
        foreach ($this->redis->lRange($this->name, 0, $number) as $item) {
            $data[] = $item;
        }
        return $data;
    }

    /**
     * 弹出最后一个消息
     * @return mixed
     */
    public function pop()
    {
        return $this->redis->rPop($this->name);
    }

    /**
     * 删除某个消息
     * @param array $message
     */
    public function del($message)
    {
        $this->redis->lRem($this->name, $message, 1);
    }
}