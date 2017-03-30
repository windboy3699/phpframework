<?php
/**
 * Created by PhpStorm.
 * @package /
 * @author  XiaodongPan
 * @version $Id: AuthInterceptor.php 2017-03-30 $
 */

class LoggerInterceptor extends  SPF_Interceptor
{
    public function before()
    {
        echo 777;
        return SPF_Interceptor::STEP_CONTINUE;
    }

    public function after()
    {
        echo 666;
        return SPF_Interceptor::STEP_CONTINUE;
    }
}


