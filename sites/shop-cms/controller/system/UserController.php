<?php
/**
 * Created by PhpStorm.
 *
 * @package
 * @author  XiaodongPan
 * @version $Id: UserController.php 2017-04-27 $
 */
namespace App\Controller\System;

use App\Controller\Controller;
use App\Model\SystemUserModel;

class UserController extends Controller
{
    public function indexAction()
    {
        $userModel = new SystemUserModel();
        $users = $userModel->getUsers();
        $this->out['users'] = $users;
        $this->render('system/user.html');
    }

    public function addAction()
    {
        $this->render('system/user_edit.html');
    }

    public function saveAction()
    {

    }
}