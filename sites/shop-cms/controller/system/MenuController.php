<?php
/**
 * Created by PhpStorm.
 *
 * @package
 * @author  XiaodongPan
 * @version $Id: MenuController.php 2017-04-28 $
 */
namespace App\Controller\System;

use App\Controller\Controller;
use App\Model\SystemMenuModel;

class MenuController extends Controller
{
    public function indexAction()
    {
        $topid = (int)$this->request->getParam('topid', 0);
        $model = new SystemMenuModel();
        $menus = $model->getMenusByTopId($topid);
        $addmenu = true;
        if ($topid > 0) {
            $menu = $model->findById($topid);
            if ($menu['level'] > 2) {
                $addmenu = false;
            }
        }
        $this->out['menus'] = $menus;
        $this->out['topid'] = $topid;
        $this->out['addmenu'] = $addmenu;
        $this->render('system/menu.html');
    }

    public function addAction()
    {
        $topid = (int)$this->request->getParam('topid', 0);
        $this->out['menu']['topid'] = $topid;
        $this->out['menu']['sort'] = 100;
        $this->out['menu']['visible'] = 1;
        $this->render('system/menu_edit.html');
    }

    public function editAction()
    {
        $id = (int)$this->request->getParam('id', 0);
        $model = new SystemMenuModel();
        $menu = $model->findById($id);
        $this->out['menu'] = $menu;
        $this->render('system/menu_edit.html');
    }

    public function saveAction()
    {
        $id = (int)$this->request->getParam('id', 0);
        $topid = (int)$this->request->getParam('topid', 0);
        $name = trim($this->request->getParam('name', ''));
        $path = trim($this->request->getParam('path', ''));
        $link = trim($this->request->getParam('link', ''));
        $visible = (int)$this->request->getParam('visible', 1);
        $sort = (int)$this->request->getParam('sort', 100);
        if (empty($name)) {
            $this->showResult(-1, '参数不用为空');
        }
        $data = [
            'name' => $name,
            'path' => $path,
            'link' => $link,
            'visible' => $visible,
            'sort' => $sort,
        ];
        $model = new SystemMenuModel();
        if (empty($id)) {
            $data['topid'] = $topid;
            $topMenu = $model->findById($topid);
            if ($topid == 0) {
                $data['level'] = 1;
            } elseif ($topMenu['topid'] == 0) {
                $data['level'] = 2;
            } else {
                $data['level'] = 3;
            }
        }

        $ret = $model->save($data, $id);
        if ($ret) {
            $this->showResult(0, '保存成功');
        } else {
            $this->showResult(-2, '执行失败');
        }
    }
}