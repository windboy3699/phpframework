<?php
/**
 * Cache Interface
 *
 * @package SPF.Cache
 * @author  XiaodongPan
 * @version $Id: CacheInterface.php 2017-04-12 $
 */
namespace spf\cache;

interface CacheInterface
{
    public function set($key, $value, $expire = null);
    public function get($key);
    public function delete($key);
}