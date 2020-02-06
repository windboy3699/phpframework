<?php
/**
 * Queue Interface
 *
 * @package SPF.Queue
 * @author  XiaodongPan
 * @version $Id: QueueInterface.php 2017-04-12 $
 */
namespace SPF\Queue;

interface QueueInterface
{
    public function count();
    public function send($message);
    public function receive($number = 0);
    public function pop();
    public function del($message);
}
