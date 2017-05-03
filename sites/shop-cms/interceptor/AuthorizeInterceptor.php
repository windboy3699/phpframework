<?php
/**
 * Created by PhpStorm.
 *
 * @package
 * @author  XiaodongPan
 * @version $Id: AuthorizeInterceptor.php 2017-05-03 $
 */
namespace App\Interceptor;

use SPF;
use SPF\Base\Interceptor;
use App\Model\SystemGroupModel;
use App\Model\SystemMenuModel;

class AuthorizeInterceptor extends Interceptor
{
    public function before()
    {
        if (empty($_SESSION['system_username'])) {
            exit('用户未登录');
        }
        $group_id = $_SESSION['system_group_id'];
        //group_id=1默认为超级管理员
        if ($group_id == 1) {
            return Interceptor::STEP_CONTINUE;
        }
        $groupModel = new SystemGroupModel();
        $groups = $groupModel->findById($group_id);
        $menu_ids = explode(',', $groups['menus']);

        $menuModel = new SystemMenuModel();
        $menus = $menuModel->findByIds($menu_ids);
        if (empty($menus)) {
            exit('用户无访问菜单');
        }
        $access = false;
        $path = SPF::app()->getRouter()->getPath();
        if (!$path) {
            return Interceptor::STEP_CONTINUE;
        }
        foreach ($menus as $item) {
            if (strpos($path, trim($item['path'], '\/')) !== false) {
                $access = true;
            }
        }
        if (!$access) {
            exit('用户无权限访问');
        }
        return Interceptor::STEP_CONTINUE;
    }

    public function after()
    {
        return Interceptor::STEP_CONTINUE;
    }
}