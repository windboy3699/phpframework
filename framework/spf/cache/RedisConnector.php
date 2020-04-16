<?php
/**
 * Redis连接器
 *
 * @package SPF.Cache
 * @author  XiaodongPan
 * @version $Id: RedisConnector.php 2017-04-12 $
 */
namespace spf\cache;

class RedisConnector
{
    private $redis = null;

    public function __construct($config)
    {
        $this->redis = new \Redis;
        if ($config['persistent'] == true) {
            //不会主动关闭的链接
            $this->redis->pconnect($config['host'], $config['port'], $config['timeout']);
        } else {
            //链接时长 (可选, 默认为 0 ，不限链接时间)
            $this->redis->connect($config['host'], $config['port'], $config['timeout']);
        }
    }

    public function set($key, $value, $expire)
    {
        if ($expire) {
            return $this->redis->setex($key, $expire, $value);
        } else {
            return $this->redis->set($key, $value);
        }
    }

    public function get($key)
    {
        return $this->redis->get($key);
    }

    public function delete($key)
    {
        return $this->redis->del($key);
    }

    public function __call($method, array $args)
    {
        return call_user_func_array(array($this->redis, $method), $args);
    }
}
