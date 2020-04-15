<?php
/**
 * 拦截器模版
 *
 * @package SPF.Interceptor
 * @author  XiaodongPan
 * @version $Id: Interceptor.php 2017-04-12 $
 */
namespace spf\interceptor;

class Interceptor
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