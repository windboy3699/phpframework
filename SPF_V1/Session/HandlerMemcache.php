<?php
/**
 * Handler Memcache
 * @package /SPF/Session
 * @author XiaodongPan
 * @version $Id HandlerMemcache.php 2016-10-25$
 */
class SPF_Session_HandlerMemcache implements SPF_Session_HandlerInterface
{
    private $memcache = null;

    private $lifetime = 1440;

    public function __construct(SPF_Cache_Memcache $memcache)
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
        return $this->memcache->get($id);
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