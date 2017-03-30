<?php
/**
 * Created by PhpStorm.
 * @package /
 * @author  XiaodongPan
 * @version $Id: AuthInterceptor.php 2017-03-30 $
 */

class AuthInterceptor extends  SPF_Interceptor
{
    public function before()
    {
        echo 999;
        return SPF_Interceptor::STEP_CONTINUE;
    }

    public function after()
    {
        echo 888;
        return SPF_Interceptor::STEP_CONTINUE;
    }
}


