<?php
/**
 * Handler Redis
 * @package /SPF/Session
 * @author XiaodongPan
 * @version $Id HandlerRedis.php 2016-10-25$
 */
class SPF_Session_HandlerRedis implements SPF_Session_HandlerInterface
{
    private $redis = null;

    private $lifetime = 1440;

    public function __construct(SPF_Cache_Redis $redis)
    {
        $this->redis = $redis;
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
        return $this->redis->get($id);
    }

    public function write($id, $data)
    {
        $this->redis->set($id, $data, $this->lifetime);
        return true;
    }

    public function destroy($id)
    {
        $this->redis->delete($id);
        return true;
    }

    public function gc($maxlifetime)
    {
        return true;
    }
}