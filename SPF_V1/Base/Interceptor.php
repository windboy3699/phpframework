<?php
/**
 * Created by PhpStorm.
 * @package /
 * @author  XiaodongPan
 * @version $Id: Interceptor.php 2016-12-11 $
 */
class SPF_Interceptor
{
    const STEP_CONTINUE = 1;
    const STEP_BREAK    = 2;
    const STEP_EXIT     = 3;

    public function __construct()
    {

    }

    public function before()
    {
        return self::STEP_CONTINUE;
    }

    public function after()
    {
        return self::STEP_CONTINUE;
    }

    public function __destruct()
    {

    }
}