<?php
/**
 * Memcache Handler
 *
 * @package SPF.Session
 * @author  XiaodongPan
 * @version $Id: MemcacheHandler.php 2017-04-12 $
 */
namespace SPF\Session;

use SPF\Cache\Memcache;

class MemcacheHandler implements HandlerInterface
{
    /**
     * @var Memcache
     */
    private $memcache;

    private $lifetime = 1440;

    public function __construct(Memcache $memcache)
    {
        if ($this->memcache === null) {
            $this->memcache = $memcache;
        }
        $this->lifetime = (int)ini_get('session.gc_maxlifetime');
    }

    public function open($save_path, $name)
    {
        return true;
    }

    public function close()
    {
        return true;
    }

    public function read($id)
    {
        return serialize($this->memcache->get($id));
    }

    public function write($id, $data)
    {
        $this->memcache->set($id, $data, $this->lifetime);
        return true;
    }

    public function destroy($id)
    {
        $this->memcache->delete($id);
        return true;
    }

    public function gc($maxlifetime)
    {
        return true;
    }
}