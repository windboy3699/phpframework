<?php
/**
 * Request
 *
 * @package SPF\Base
 * @author  XiaodongPan
 * @version $Id: Request.php 2017-04-12 $
 */
namespace SPF\Base;

class Request
{
    public function __construct()
    {
        //安全过虑
        Util::recurAddslashes($_GET);
        Util::recurAddslashes($_POST);
        Util::recurAddslashes($_COOKIE);
    }

    public function getParam($key, $default = null)
    {
        return isset($_POST[$key]) ? $this->getPost($key, $default) : $this->getGet($key, $default);
    }

    public function getParams()
    {
        $get = $_GET ? $_GET : [];
        $post = $_POST ? $_POST : [];
        $params = array_merge($get, $post);
        return $params;
    }

    public function getGet($key, $default = null)
    {
        return (isset($_GET[$key]) && $_GET[$key] !== '') ? $_GET[$key] : $default;
    }

    public function getPost($key, $default = null)
    {
        return (isset($_POST[$key]) && $_POST[$key] !== '') ? $_POST[$key] : $default;
    }

    public function getCookie($name)
    {
        return isset($_COOKIE[$name]) ? $_COOKIE[$name] : null;
    }

    public function getSession($key, $default = null)
    {
        return isset($_SESSION[$key]) ? $_SESSION[$key] : $default;
    }

    public function getBaseUrl()
    {
        $base_url = rtrim(dirname(self::getScriptUrl()), '\\/');
        return $base_url;
    }

    public function getScriptUrl()
    {
        return isset($_SERVER['SCRIPT_NAME']) ? $_SERVER['SCRIPT_NAME'] : $_SERVER['PHP_SELF'];
    }

    public function getMethod()
    {
        return $_SERVER['REQUEST_METHOD'];
    }

    public function isGetMethod()
    {
        return $this->getMethod() == 'GET';
    }

    public function isPostMethod()
    {
        return $this->getMethod() == 'POST';
    }

    public function getUserAgent()
    {
        return $_SERVER['HTTP_USER_AGENT'];
    }

    public function getIp()
    {
        if(isset($_SERVER['HTTP_X_REAL_IP'])) {
            $ip = $_SERVER['HTTP_X_REAL_IP'];
        } elseif (getenv('HTTP_CLIENT_IP')) {
            $ip = getenv('HTTP_CLIENT_IP');
        } elseif (getenv('HTTP_X_FORWARDED_FOR')) {
            $ip = getenv('HTTP_X_FORWARDED_FOR');
        } elseif (getenv('REMOTE_ADDR')) {
            $ip = getenv('REMOTE_ADDR');
        } elseif (isset($_SERVER['REMOTE_ADDR'])) {
            $ip = $_SERVER['REMOTE_ADDR'];
        } else {
            $ip = '';
        }
        if (true == ($p = strpos($ip, ','))) {
            $ip = substr($ip, 0, $p);
        }
        return $ip;
    }
}