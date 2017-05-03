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
        $_SESSION['system_username'] = $user['username'];
        $_SESSION['system_realname'] = $user['realname'];
        $_SESSION['system_group_id'] = $user['group_id'];
        $this->showResult(0, '登录成功');
    }

    public function logoutAction()
    {
        session_destroy();
        $this->jump('/login/');
    }
}