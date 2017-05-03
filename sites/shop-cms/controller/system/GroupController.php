<?php
/**
 * Created by PhpStorm.
 *
 * @package
 * @author  XiaodongPan
 * @version $Id: GroupController.php 2017-04-28 $
 */
namespace App\Controller\System;

use App\Controller\Controller;
use App\Model\SystemGroupModel;
use App\Model\SystemMenuModel;

class GroupController extends Controller
{
    public function indexAction()
    {
        $model = new SystemGroupModel();
        $this->out['groups'] = $model->getAllGroups();
        $this->out['breadcrumbs'] = $this->getBreadCrumbs('index');
        $this->render('system/group.html');
    }

    public function addAction()
    {
        $this->out['levelmenus'] = $this->getLevleMenus();
        $this->out['breadcrumbs'] = $this->getBreadCrumbs('add');
        $this->render('system/group_edit.html');
    }

    public function editAction()
    {
        $id = $this->request->getParam('id', 0);

        $model = new SystemGroupModel();
        $group = $model->findById($id);

        $this->out['group'] = $group;
        $this->out['menus'] = explode(',', $group['menus']);
        $this->out['levelmenus'] = $this->getLevleMenus();
        $this->out['breadcrumbs'] = $this->getBreadCrumbs('edit');
        $this->render('system/group_edit.html');
    }

    public function saveAction()
    {
        $id = (int)$this->request->getParam('id', 0);
        $name = trim($this->request->getParam('name', ''));
        $menus = $this->request->getParam('menus', []);
        if (empty($name) || empty($menus)) {
            $this->showResult(-1, '参数不用为空');
        }
        $data = [
            'name' => $name,
            'menus' => implode(',', $menus),
        ];
        $model = new SystemGroupModel();
        $ret = $model->save($data, $id);
        if ($ret) {
            $this->showResult(0, '保存成功');
        } else {
            $this->showResult(-2, '执行失败');
        }
    }

    private function getLevleMenus()
    {
        $menuModel = new SystemMenuModel();
        $menusdata = $menuModel->getAllMenus();
        $menusbykey = $levelmenus = [];
        foreach ($menusdata as $item) {
            $menusbykey[$item['id']] = $item;
        }
        foreach ($menusdata as $item) {
            if ($item['level'] == 1) {
                $levelmenus[$item['id']]['menu'] = $item;
            }
            if ($item['level'] == 2) {
                $levelmenus[$item['topid']]['submenus'][$item['id']]['menu'] = $item;
            }
            if ($item['level'] == 3) {
                $topMenu = $menusbykey[$item['topid']];
                $levelmenus[$topMenu['topid']]['submenus'][$item['topid']]['submenus'][$item['id']]['menu'] = $item;
            }
        }
        return $levelmenus;
    }

    protected function getBreadCrumbs($action)
    {
        $data = [
            'index' => [
                [
                    'name' => '系统管理',
                    'link' => '',
                ], [
                    'name' => '用户组管理',
                    'link' => '',
                ]
            ],
            'add' => [
                [
                    'name' => '系统管理',
                    'link' => '',
                ], [
                    'name' => '用户组添加',
                    'link' => '',
                ]
            ],
            'edit' => [
                [
                    'name' => '系统管理',
                    'link' => '',
                ], [
                    'name' => '用户组编辑',
                    'link' => '',
                ]
            ],
        ];
        return isset($data[$action]) ? $data[$action] : [];
    }
}