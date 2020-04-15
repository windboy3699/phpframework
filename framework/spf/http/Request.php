<?php
/**
 * Request
 *
 * @package SPF.Http
 * @author  XiaodongPan
 * @version $Id: Request.php 2017-04-12 $
 */
namespace spf\http;

class Request
{
    public function __construct()
    {
        self::recurAddslashes($_GET);
        self::recurAddslashes($_POST);
        self::recurAddslashes($_COOKIE);
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

    public function getCurUrl()
    {
        $sys_protocal = isset($_SERVER['SERVER_PORT']) && $_SERVER['SERVER_PORT'] == '443' ? 'https://' : 'http://';
        $php_self = $_SERVER['PHP_SELF'] ? $_SERVER['PHP_SELF'] : $_SERVER['SCRIPT_NAME'];
        $path_info = isset($_SERVER['PATH_INFO']) ? $_SERVER['PATH_INFO'] : '';
        $relate_url = isset($_SERVER['REQUEST_URI']) ? $_SERVER['REQUEST_URI'] : $php_self.(isset($_SERVER['QUERY_STRING']) ? '?'.$_SERVER['QUERY_STRING'] : $path_info);
        return $sys_protocal.(isset($_SERVER['HTTP_HOST']) ? $_SERVER['HTTP_HOST'] : '').$relate_url;
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

    /**
     * addslashes
     * Key不允许出现引号
     */
    private static function recurAddslashes(&$var)
    {
        if (is_array($var)) {
            foreach ($var as $key=>$value) {
                if (preg_match('/[\"\'\\\]/', $key)) {
                    unset($var[$key]);
                } else {
                    self::recurAddslashes($var[$key]);
                }
            }
        } else {
            $var = addslashes($var);
        }
    }
}