<?php
/**
 * Created by PhpStorm.
 *
 * @package
 * @author  XiaodongPan
 * @version $Id: UserController.php 2017-04-27 $
 */
namespace shop\admin\system;

use shop\admin\Controller;
use shop\admin\model\SystemUserModel;
use shop\admin\model\SystemGroupModel;
use spf\pagination\Paginator;

class UserController extends Controller
{
    public function indexAction()
    {
        $userModel = new SystemUserModel();
        $users = $userModel->getUsers();

        $total = $userModel->getUsersCount();
        $paginator = new Paginator($total);
        $pagination = $paginator->render();

        $this->out['users'] = $users;
        $this->out['breadCrumbs'] = $this->getBreadCrumbs('index');
        $this->out['pagination'] = $pagination;
        $this->render('system/user.html');
    }

    public function addAction()
    {
        $this->out['groups'] = $this->getGroups();
        $this->out['breadCrumbs'] = $this->getBreadCrumbs('add');
        $this->render('system/user_edit.html');
    }

    public function editAction()
    {
        $id = $this->request->getParam('id', 0);

        $model = new SystemUserModel();
        $user = $model->findById($id);

        $this->out['user'] = $user;
        $this->out['groups'] = $this->getGroups();
        $this->out['breadCrumbs'] = $this->getBreadCrumbs('edit');
        $this->render('system/user_edit.html');
    }

    public function saveAction()
    {
        $id = (int)$this->request->getParam('id', 0);
        $username = trim($this->request->getParam('username', ''));
        $password = trim($this->request->getParam('password', ''));
        $realname = trim($this->request->getParam('realname', ''));
        $groupId = (int)$this->request->getParam('group_id', 0);
        if (empty($username) || empty($realname) || empty($groupId)) {
            $this->showResult(-1, '参数不用为空');
        }
        $data = [
            'username' => $username,
            'realname' => $realname,
            'group_id' => $groupId,
        ];
        $password && $data['password'] = md5($password);
        if (empty($id)) {
            $data['add_user'] = $_SESSION['systemUsername'];
            $data['create_time'] = date("Y-m-d H:i:s");
        }
        $model = new SystemUserModel();
        $ret = $model->save($data, $id);
        if ($ret) {
            $this->showResult(0, '保存成功');
        } else {
            $this->showResult(-2, '执行失败');
        }
    }

    public function deleteAction()
    {
        $id = (int)$this->request->getParam('id', 0);
        $model = new SystemUserModel();
        $ret = $model->deleteById($id);
        if ($ret) {
            $this->showResult(0, '删除成功');
        } else {
            $this->showResult(-1, '删除失败');
        }
    }

    public function getGroups()
    {
        $model = new SystemGroupModel();
        $data = $model->getAllGroups();
        if (empty($data)) {
            return [];
        }
        $groups = [];
        foreach ($data as $item) {
            $groups[$item['id']] = $item['name'];
        }
        return $groups;
    }

    private function getBreadCrumbs($action)
    {
        $data = ['index' => [[
                    'name' => '系统管理',
                    'link' => '',
                ], [
                    'name' => '用户管理',
                    'link' => '',
                ]
            ], 'add' => [[
                    'name' => '系统管理',
                    'link' => '',
                ], [
                    'name' => '用户添加',
                    'link' => '',
                ]
            ], 'edit' => [[
                    'name' => '系统管理',
                    'link' => '',
                ], [
                    'name' => '用户编辑',
                    'link' => '',
                ]
            ],
        ];
        return isset($data[$action]) ? $data[$action] : [];
    }
}