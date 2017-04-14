<?php
/**
 * Memcache
 *
 * @package SPF\Cache
 * @author  XiaodongPan
 * @version $Id: Memcache.php 2017-04-12 $
 */
namespace SPF\Cache;

class Memcache implements CacheInterface
{
    const EXPIRE = 1200;

    private $useMemcached = true;

    private $keyPrefix = null;

    private $cache = null;

    /**
     * SPF_Memcache constructor.
     * @param $config
     * <pre>
     * $config = [
     *     'keyPrefix' => 'api.sports.',
     *     'servers' => [
     *         'host' => '127.0.0.1',
     *         'port' => '11211',
     *         'weight' => 1,
     *     ]
     * ]
     * </pre>
     */
    public function __construct($config)
    {
        if ($this->keyPrefix === null) {
            $this->keyPrefix = $config['keyPrefix'] ? $config['keyPrefix'] : '';
        }
        $cache = $this->getMemCache();
        $servers = $config['servers'];
        if (count($servers)) {
            foreach ($servers as $server) {
                if ($this->useMemcached) {
                    $cache->addServer($server['host'], $server['port'], $server['weight']);
                } else {
                    $cache->addServer($server['host'], $server['port'], false, $server['weight']);
                }
            }
        } else {
            throw new SPF_Exception('MemCache server config not defined');
        }
    }

    public function getMemCache()
    {
        if ($this->cache !== null) {
            return $this->cache;
        } else {
            $extension = $this->useMemcached ? 'memcached' : 'memcache';
            if (!extension_loaded($extension)) {
                throw new SPF_Exception("MemCache requires PHP $extension extension to be loaded.");
            }
            return $this->cache = $this->useMemcached ? new Memcached : new Memcache;
        }
    }

    public function get($key)
    {
        return $this->cache->get($this->buildKey($key));
    }

    public function mGet($keys)
    {
        $keys = $this->buildKey($keys);
        return $this->useMemcached ? $this->cache->getMulti($keys) : $this->cache->get($keys);
    }

    public function set($key, $value, $expire = null)
    {
        $key = $this->buildKey($key);
        $expire = (int)$expire;
        if ($expire === null) {
            $expire = time() + self::EXPIRE;
        } elseif ($expire > 0) {
            $expire += time();
        }
        return $this->useMemcached ? $this->cache->set($key, $value, $expire) : $this->cache->set($key, $value, 0, $expire);
    }

    public function add($key, $value, $expire = null)
    {
        $key = $this->buildKey($key);
        $expire = (int)$expire;
        if ($expire === null) {
            $expire = time() + self::EXPIRE;
        } elseif ($expire > 0) {
            $expire += time();
        }
        return $this->useMemcached ? $this->cache->add($key, $value, $expire) : $this->cache->add($key, $value, 0, $expire);
    }

    public function delete($key)
    {
        return $this->cache->delete($this->buildKey($key), 0);
    }

    public function flush()
    {
        return $this->cache->flush();
    }

    private function buildKey($key)
    {
        if (!is_array($key))  {
            return $this->keyPrefix . $key;
        }
        foreach ($key as $k => $v) {
            $key[$k] = $this->keyPrefix . $v;
        }
        return $key;
    }
}