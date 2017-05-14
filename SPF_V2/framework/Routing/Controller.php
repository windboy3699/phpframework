<?php
/**
 * AbstractController
 *
 * @package SPF.Routing
 * @author  XiaodongPan
 * @version $Id: Controller.php 2017-05-09 $
 */
namespace SPF\Routing;

use SPF\SPF;
use SPF\View\View;

abstract class Controller
{
    /**
     * @var \SPF\Application\WebApplication
     */
    protected $app;

    /**
     * @var \SPF\Http\Request
     */
    protected $request;

    /**
     * @var \SPF\View\View
     */
    protected $view = null;

    /**
     * @var 模板输出变量
     */
    protected $out = [];

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
     * initialize
     */
    public function init()
    {

    }

    /**
     * 获取模板引擎
     *
     * @return Twig_Environment
     */
    public function getView()
    {
        if ($this->view === null) {
            $this->view = View::create(SPF::app()->getAppPath() . '/view/');
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
}