<?php
/**
 * Created by PhpStorm.
 *
 * @package
 * @author  XiaodongPan
 * @version $Id: LoginController.php 2017-04-24 $
 */
namespace shop\admin;

use shop\admin\model\SystemUserModel;
use shop\admin\model\SystemGroupModel;

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

        $this->response->setSession('systemUsername', $user['username']);
        $this->response->setSession('systemRealname', $user['realname']);
        $this->response->setSession('systemGroupId', $user['group_id']);
        $this->response->setSession('systemGroupName', $group['name']);

        $this->showResult(0, '');
    }

    public function logoutAction()
    {
        $this->response->sessionDestroy();
        $this->jump('/admin/login');
    }
}