<?php
/**
 * Created by PhpStorm.
 *
 * @package
 * @author  XiaodongPan
 * @version $Id: LoginController.php 2017-04-24 $
 */
namespace App\Controller;

class LoginController extends Controller
{
    public function indexAction()
    {
        $this->render('login.html');
    }
}