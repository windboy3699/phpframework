<?php
/**
 * Cache后端接口
 * @package /SPF/Cache
 * @author  XiaodongPan
 * @version $Id: Interface.php 2016-10-31 $
 */
interface SPF_Cache_Interface
{
    public function set($key, $value, $expire = null);
    public function get($key);
    public function delete($key);
}