<?php
/**
 * Abstract Router
 *
 * @package SPF.Routing
 * @author  XiaodongPan
 * @version $Id: RouterBase.php 2017-04-18 $
 */
namespace spf\routing;

abstract class Router
{
    /**
     * @var string
     */
    protected $controller;

    /**
     * @var string
     */
    protected $action;

    /**
     * @var string
     */
    protected $path = '';

    /**
     * @var string
     */
    protected $errorRedirect = '';

    /**
     * @return mixed
     */
    abstract public function parse();

    /**
     * 获取Controller
     *
     * @return mixed
     */
    public function getController()
    {
        return $this->controller;
    }

    /**
     * 获取Action
     *
     * @return string
     */
    public function getAction()
    {
        return $this->action;
    }

    /**
     * 获取Url路径
     * /sports/live/show/?id=100 >> sports/live/show
     *
     * @return mixed|string
     */
    public function getPath()
    {
        if ($this->path) {
            return $this->path;
        }
        //文件真实地址 如:/api/index.php
        $scriptUrl = isset($_SERVER['SCRIPT_NAME']) ? $_SERVER['SCRIPT_NAME'] : $_SERVER['PHP_SELF'];
        //scriptUrl的path 如：/api
        $baseUrl = rtrim(dirname($scriptUrl), '\\/');
        //除域名外的完整url包括参数 如:/sports/live/show/?id=100
        $requestUri = $_SERVER['REQUEST_URI'];
        if (($pos = strpos($requestUri, '?')) !== false) {
            $requestUri = substr($requestUri, 0, $pos);
        }
        if ($scriptUrl && strpos($requestUri, $scriptUrl) === 0) {
            $path = substr($requestUri, strlen($scriptUrl));
        } elseif ($baseUrl && strpos($requestUri, $baseUrl) === 0) {
            $path = substr($requestUri, strlen($baseUrl));
        } else {
            $requestUri = parse_url($requestUri);
            $path = $requestUri['path'];
        }
        $path = trim($path, '/');
        $path = $path = preg_replace('/\/+/', '/', $path);
        $this->path = $path;
        return $this->path;
    }

    /**
     * 设置错误显示页面
     *
     * @param $redirect
     */
    public function setErrorRedirect($redirect)
    {
        $this->errorRedirect = $redirect;
    }

    /**
     * 控制器不存在处理
     */
    public function notFound()
    {
        header('HTTP/1.1 404 Not Found');
        header("status: 404 Not Found");
        if ($this->errorRedirect) {
            $this->redirect($this->errorRedirect);
        } else {
            echo '404 Page Not Found';
            exit;
        }
    }

    /**
     * url转向
     *
     * @param string $url
     */
    public function redirect($url)
    {
        header('Location: ' . $url);
        exit;
    }
}