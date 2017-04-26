<?php
/**
 * Created by PhpStorm.
 *
 * @package
 * @author  XiaodongPan
 * @version $Id: LoggerInterceptor.php 2017-04-26 $
 */
namespace App\Interceptor;

use SPF\Base\Interceptor;

class LoggerInterceptor extends Interceptor
{
    public function before()
    {
        return Interceptor::STEP_CONTINUE;
    }

    public function after()
    {
        return Interceptor::STEP_CONTINUE;
    }
}