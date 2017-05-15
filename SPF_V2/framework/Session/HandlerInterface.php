<?php
/**
 * Handler Interface
 *
 * @package SPF.Session
 * @author  XiaodongPan
 * @version $Id: HandlerInterface.php 2017-04-12 $
 */
namespace SPF\Session;

interface HandlerInterface
{
    public function open($save_path, $name);
    public function close();
    public function read($id);
    public function write($id, $data);
    public function destroy($id);
    public function gc($maxlifetime);
}