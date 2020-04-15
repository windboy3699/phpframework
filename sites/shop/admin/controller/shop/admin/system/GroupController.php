<?php
/**
 * Created by PhpStorm.
 *
 * @package
 * @author  XiaodongPan
 * @version $Id: GroupController.php 2017-04-28 $
 */
namespace shop\admin\system;

use shop\admin\Controller;
use shop\admin\model\SystemGroupModel;
use shop\admin\model\SystemMenuModel;

class GroupController extends Controller
{
    public function indexAction()
    {
        $model = new SystemGroupModel();
        $this->out['groups'] = $model->getAllGroups();
        $this->out['breadCrumbs'] = $this->getBreadCrumbs('index');
        $this->render('system/group.html');
    }

    public function addAction()
    {
        $this->out['levelMenus'] = $this->getLevleMenus();
        $this->out['breadCrumbs'] = $this->getBreadCrumbs('add');
        $this->render('system/group_edit.html');
    }

    public function editAction()
    {
        $id = $this->request->getParam('id', 0);

        $model = new SystemGroupModel();
        $group = $model->findById($id);

        $this->out['group'] = $group;
        $this->out['menus'] = explode(',', $group['menus']);
        $this->out['levelMenus'] = $this->getLevleMenus();
        $this->out['breadCrumbs'] = $this->getBreadCrumbs('edit');
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
        $menusData = $menuModel->getAllMenus();
        $menusBykey = $levelMenus = [];
        foreach ($menusData as $item) {
            $menusBykey[$item['id']] = $item;
        }
        foreach ($menusData as $item) {
            if ($item['level'] == 1) {
                $levelMenus[$item['id']]['menu'] = $item;
            }
            if ($item['level'] == 2) {
                $levelMenus[$item['topid']]['submenus'][$item['id']]['menu'] = $item;
            }
            if ($item['level'] == 3) {
                $topMenu = $menusBykey[$item['topid']];
                $levelMenus[$topMenu['topid']]['submenus'][$item['topid']]['submenus'][$item['id']]['menu'] = $item;
            }
        }
        return $levelMenus;
    }

    private function getBreadCrumbs($action)
    {
        $data = ['index' => [[
                    'name' => '系统管理',
                    'link' => '',
                ], [
                    'name' => '用户组管理',
                    'link' => '',
                ]
            ], 'add' => [[
                    'name' => '系统管理',
                    'link' => '',
                ], [
                    'name' => '用户组添加',
                    'link' => '',
                ]
            ], 'edit' => [[
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