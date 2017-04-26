<?php
/**
 * Redis
 *
 * @package SPF.Cache
 * @author  XiaodongPan
 * @version $Id: Redis.php 2017-04-12 $
 */
namespace SPF\Cache;

class Redis implements CacheInterface
{
    const EXPIRE = 1200;

    private $master = null;

    private $slave = null;

    private $config = array();

    /**
     * Redis constructor.
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
            throw new Exception('Redis缺少配置');
        }
        if (!extension_loaded('redis')) {
            throw new Exception('Redis扩展不存在');
        }
        $this->config['master'] = isset($config['master']) ? $config['master'] : [];
        $this->config['slave'] = isset($config['slave']) ? $config['slave'] : [];
    }

    public function getMaster()
    {
        if ($this->master === null) {
            $this->master = new RedisConnector($this->config['master']);
        }
        return $this->master;
    }

    public function getSlave()
    {
        if ($this->slave === null) {
            $count = count($this->config['slave']);
            $idx = $count == 1 ? 0 : mt_rand(0, $count-1);
            $config = $this->config['slave'][$idx];
            $this->slave = new RedisConnector($config);
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