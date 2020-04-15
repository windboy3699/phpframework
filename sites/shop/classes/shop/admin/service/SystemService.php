<?php
/**
 * Created by PhpStorm.
 *
 * @package
 * @author  XiaodongPan
 * @version $Id: SystemService.php 2017-05-03 $
 */
namespace shop\admin\service;

use spf\SPF;
use shop\admin\model\SystemUserModel;
use shop\admin\model\SystemGroupModel;
use shop\admin\model\SystemMenuModel;

class SystemService
{
    /**
     * @var SystemUserModel
     */
    private $userModel;

    /**
     * @var SystemGroupModel
     */
    private $groupModel;

    /**
     * @var SystemMenuModel
     */
    private $menuModel;

    public function __construct()
    {
        $this->userModel = new SystemUserModel();
        $this->groupModel = new SystemGroupModel();
        $this->menuModel = new SystemMenuModel();
    }

    public function getUserMenusByGroupId($groupId)
    {
        $menusData = $this->menuModel->getAllMenus();
        $allMenus = [];
        foreach ($menusData as $item) {
            $allMenus[$item['id']] = $item;
        }

        if ($groupId != 1) {
            $group = $this->groupModel->findById($groupId);
            $menuIds = explode(',', $group['menus']);
            $userMenus = $this->menuModel->findByIds($menuIds);
            if (empty($userMenus)) {
                return [];
            }
            foreach ($userMenus as $menu) {
                if ($menu['level'] == 2) {
                    $menuIds[] = $menu['topid'];
                }
                if ($menu['level'] == 3) {
                    $menuIds[] = $menu['topid'];
                    $menuIds[] = $allMenus[$menu['topid']]['topid'];
                }
            }
            $menuIds = array_unique($menuIds);
        }

        $path = SPF::app()->getRouter()->getPath();

        $showMenus = [];
        foreach ($menusData as $item) {
            if ($groupId != 1 && !in_array($item['id'], $menuIds)) {
                continue;
            }
            if ($item['path']) {
                if (strpos($path, trim($item['path'], '\/')) !== false) {
                    $item['active'] = 1;
                }
            }
            if ($item['level'] == 1) {
                $showMenus[$item['id']]['menu'] = $item;
            }
            if ($item['level'] == 2) {
                $showMenus[$item['topid']]['submenus'][$item['id']]['menu'] = $item;
                if ($item['active'] == 1) {
                    $showMenus[$item['topid']]['menu']['active'] = 1;
                }
            }
            if ($item['level'] == 3) {
                $topMenu = $allMenus[$item['topid']];
                $showMenus[$topMenu['topid']]['submenus'][$item['topid']]['submenus'][$item['id']]['menu'] = $item;
                if ($item['active'] == 1) {
                    $showMenus[$topMenu['topid']]['menu']['active'] = 1;
                    $showMenus[$topMenu['topid']]['submenus'][$item['topid']]['menu']['active'] = 1;
                }
            }
        }
        return $showMenus;
    }
}