<?php
/**
 * Created by PhpStorm.
 *
 * @package
 * @author  XiaodongPan
 * @version $Id: HelloController.php 2017-04-19 $
 */
namespace App\Controller\Mobile\V1;

use App\Controller\Controller;

class HelloController extends Controller
{
    public function handleRequest()
    {
        $this->render('test.html');
    }
}