<?php
/**
 * Created by PhpStorm.
 * @package /
 * @author  XiaodongPan
 * @version $Id: AuthInterceptor.php 2017-03-30 $
 */
namespace App\Interceptor;

use SPF\Interception\Interceptor;

class LoggerInterceptor extends  Interceptor
{
    public function before()
    {
        echo 777;
        return Interceptor::STEP_CONTINUE;
    }

    public function after()
    {
        echo 666;
        return Interceptor::STEP_CONTINUE;
    }
}


