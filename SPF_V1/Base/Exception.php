<?php
/**
 * 异常类
 * @package /SPF/Base
 * @author  XiaodongPan
 * @version $Id: Exception.php 2016-10-26 $
 */
class SPF_Exception extends Exception
{
    public function __construct($msg = '', $code = 0)
    {
        parent::__construct($msg, $code);
        echo '<h1>Exception:</h1>';
        echo $this->getMessage() .'<br /><br />';
        echo $this->getFile() .' 行：'. $this->getLine();
        exit;
    }
}