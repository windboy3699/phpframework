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

    public function checkAction()
    {
        $username = $this->request->getParam('username', '');
        $password = $this->request->getParam('password', '');

        $where = [
            'AND' => [
                'username' => $username,
                'password' => md5($password),
            ]
        ];

        $user = $this->getDb()->select('admin_user', '*', $where);

        if (empty($user)) {
            $this->jump('', '用户名或密码错误');
        } else {
            $this->jump('/');
        }
    }
}