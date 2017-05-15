<?php
/**
 * Created by PhpStorm.
 *
 * @package
 * @author  XiaodongPan
 * @version $Id: LoginController.php 2017-04-24 $
 */
namespace App\Controller;

use App\Model\SystemUserModel;
use App\Model\SystemGroupModel;

class LoginController extends Controller
{
    public function indexAction()
    {
        $this->render('login.html');
    }

    public function checkAction()
    {
        $username = $this->request->getParam('username', '');
        $password = $this->request->getParam('password', '');
        $model = new SystemUserModel();
        $user = $model->checkLogin($username, $password);
        if (!$user) {
            $this->showResult(-1, '用户名或密码错误');
        }
        $groupModel = new SystemGroupModel();
        $group = $groupModel->findById($user['group_id']);
        $_SESSION['systemUsername'] = $user['username'];
        $_SESSION['systemRealname'] = $user['realname'];
        $_SESSION['systemGroupId'] = $user['group_id'];
        $_SESSION['systemGroupName'] = $group['name'];
        $this->showResult(0, '');
    }

    public function logoutAction()
    {
        session_destroy();
        $this->jump('/login/');
    }
}