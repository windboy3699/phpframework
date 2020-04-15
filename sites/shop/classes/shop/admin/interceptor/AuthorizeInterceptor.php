<?php
/**
 * Created by PhpStorm.
 *
 * @package
 * @author  XiaodongPan
 * @version $Id: AuthorizeInterceptor.php 2017-05-03 $
 */
namespace shop\admin\interceptor;

use spf\SPF;
use spf\interceptor\Interceptor;
use shop\admin\model\SystemGroupModel;
use shop\admin\model\SystemMenuModel;

class AuthorizeInterceptor extends Interceptor
{
    public function before()
    {
        if (empty($_SESSION['systemUsername'])) {
            $this->jump('/admin/login');
        }
        $groupId = $_SESSION['systemGroupId'];
        //group_id=1默认为超级管理员
        if ($groupId == 1) {
            return Interceptor::STEP_CONTINUE;
        }
        $groupModel = new SystemGroupModel();
        $groups = $groupModel->findById($groupId);
        $menuIds = explode(',', $groups['menus']);

        $menuModel = new SystemMenuModel();
        $menus = $menuModel->findByIds($menuIds);
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

    /**
     * 页面跳转，默认跳回前一页
     *
     * @param string $url
     * @param string $message
     */
    private function jump($url = '', $message = '')
    {
        if ($url == '') {
            $url = empty($_SERVER['HTTP_REFERER']) ? '/' : $_SERVER['HTTP_REFERER'];
        }
        if ($message) {
            echo '<script>alert("', $message, '");document.location.href="', $url, '";</script>';
        } else {
            echo '<script>document.location.href="', $url, '";</script>';
        }
        exit;
    }
}