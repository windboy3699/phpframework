<?php
/**
 * Created by PhpStorm.
 *
 * @package App.Controller
 * @author  XiaodongPan
 * @version $Id: Controller.php 2017-04-19 $
 */
namespace App\Controller;

use SPF;
use SPF\View\View;
use SPF\Base\Util;
use App\Service\SystemService;

abstract class Controller
{
    /**
     * @var \SPF\Application\WebApplication
     */
    public $app;

    /**
     * @var \SPF\Base\Request
     */
    public $request;

    /**
     * @var \SPF\View\View
     */
    public $view = null;

    /**
     * @var 模板输出变量
     */
    public $out = [];

    /**
     * Controller constructor.
     */
    public function __construct()
    {
        $this->app = SPF::app();
        $this->request = $this->app->getRequest();
        $this->init();
    }

    /**
     * init data
     */
    public function init()
    {
        $systemService = new SystemService();
        $leftmenus = $systemService->getUserMenusByGroupId($_SESSION['system_group_id']);
        $this->out['leftmenus'] = $leftmenus;
        $this->out['system_realname'] = $_SESSION['system_realname'] ? $_SESSION['system_realname'] : '';
        $this->out['system_group_name'] = $_SESSION['system_group_name'] ? $_SESSION['system_group_name'] : '';
        $this->out['breadcrumbs'] = [];
        $this->out['static'] = 'http://cms.shop.com/static';
        $this->out['cururl'] = Util::getCurUrl();
        $this->out['referer'] = $_SERVER['HTTP_REFERER'];
    }

    /**
     * 获取模板引擎
     *
     * @return Twig_Environment
     */
    public function getView()
    {
        if ($this->view === null) {
            $this->view = View::create(APP_PATH . '/view/');
        }
        return $this->view;
    }

    /**
     * 显示视图
     *
     * @param string $tpl
     */
    public function render($tpl)
    {
        echo $this->getView()->render($tpl, $this->out);
    }

    /**
     * 页面跳转，默认跳回前一页
     *
     * @param string $url
     * @param string $message
     */
    public function jump($url = '', $message = '')
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

    /**
     * 输出Json数据
     *
     * @param $code
     * @param $msg
     * @param array $data
     */
    public function showResult($code, $msg, $data = [], $cbname = 'cb')
    {
        $result = ['code' => $code, 'msg' => $msg, 'data' => $data];
        $cb = isset($_REQUEST[$cbname]) ? trim($_REQUEST[$cbname]) : '';
        empty($cb) && header('Content-Type: application/json; charset=utf-8');
        $result = json_encode($result, JSON_UNESCAPED_UNICODE);
        echo $cb ? $cb .'('. $result .');' : $result;
        exit;
    }
}