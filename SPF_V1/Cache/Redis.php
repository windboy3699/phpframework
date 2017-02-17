<?php
/**
 * Redis封装
 * @package /SPF/Cache
 * @author  XiaodongPan
 * @version $Id: Redis.php 2016-10-26 $
 */
class SPF_Cache_Redis implements SPF_Cache_Interface
{
    const EXPIRE = 1200;

    private $master = null;

    private $slave = null;

    private $config = array();

    /**
     * SPF_Redis constructor.
     * @param $config
     * <pre>
     * $config = [
     *     'master' => [
     *         'host' => '127.0.0.1',
     *         'port' => 6379,
     *         'timeout' => 0,
     *         'persistent' => false,
     *     ],
     *     'slave' => [
     *         [
     *             'host' => '127.0.0.1',
     *             'port' => 6379,
     *             'timeout' => 0,
     *             'persistent' => false,
     *         ]
     *     ],
     * ]
     * </pre>
     */
    public function __construct(array $config)
    {
        if (!$config['master'] || !$config['slave']) {
            throw new SPF_Exception('Redis缺少配置');
        }
        if (!extension_loaded('redis')) {
            throw new SPF_Exception('Redis扩展不存在');
        }
        $this->config['master'] = isset($config['master']) ? $config['master'] : [];
        $this->config['slave'] = isset($config['slave']) ? $config['slave'] : [];
    }

    public function getMaster()
    {
        if ($this->master === null) {
            $this->master = new SPF_Cache_RedisConnector($this->config['master']);
        }
        return $this->master;
    }

    public function getSlave()
    {
        if ($this->slave === null) {
            $count = count($this->config['slave']);
            $idx = $count == 1 ? 0 : mt_rand(0, $count-1);
            $config = $this->config['slave'][$idx];
            $this->slave = new SPF_Cache_RedisConnector($config);
        }
        return $this->slave;
    }

    public function set($key, $value, $expire = null)
    {
        $expire = $expire > 0 ? $expire : ($expire === null ? self::EXPIRE : 0);
        return $this->getMaster()->set($key, $value, $expire);
    }

    public function get($key)
    {
        return $this->getSlave()->get($key);
    }

    public function delete($key)
    {
        return $this->getMaster()->delete($key);
    }

    public function __call($method, array $args)
    {
        return call_user_func_array(array($this->getMaster(), $method), $args);
    }
}