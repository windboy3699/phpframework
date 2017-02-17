<?php
/**
 * Cache后端接口
 * @package /SPF/Queue
 * @author XiaodongPan
 * @version $Id Queue.php 2016-10-25$
 */
interface SPF_Queue_Interface
{
    public function count();
    public function send($message);
    public function receive($number = 0);
    public function pop();
    public function del($message);
}
