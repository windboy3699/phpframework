<?php
/**
 * 控制器抽象类
 * @package /SPF/Controller
 * @author XiaodongPan
 * @version $Id Controller.php 2016-10-25$
 */
abstract class SPF_Controller
{
    /**
     * @var SPF
     */
    public $spf;

    /**
     * @var SPF_Request
     */
    public $request;

    /**
     * @var 模板输出变量
     */
    public $out = [];

    public function __construct()
    {
        $this->spf = SPF::getInstance();
        $this->request = $this->spf->getRequest();
    }

    /**
     * 页面跳转，默认跳回前一页
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