<?php
/**
 * Created by PhpStorm.
 *
 * @package
 * @author  XiaodongPan
 * @version $Id: IndexController.php 2017-04-20 $
 */
namespace App\Controller;

use Monolog\Logger;
use Monolog\Handler\StreamHandler;

class IndexController extends Controller
{
    public function handleRequest()
    {

        /*$log = new Logger('name');
        $log->pushHandler(new StreamHandler('test.log', Logger::WARNING));

        $log->addWarning('Foo');
        $log->addError('Bar');*/

        $this->showResult(0, 'ok');
    }
}