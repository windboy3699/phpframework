<?php
/**
 * Response
 *
 * @package SPF.Http
 * @author  XiaodongPan
 * @version $Id: Response.php 2017-05-08 $
 */
namespace spf\http;

class Response
{
    public function setCookie($name, $value, $expired, $path = null, $domain = null)
    {
        setcookie($name, $value, time()+$expired, $path, $domain);
    }

    public function removeCookie($name, $path = null, $domain = null)
    {
        setcookie($name, 1, time()-3600, $path, $domain);
    }

    public function setSession($name, $value)
    {
        $_SESSION[$name] = $value;
    }

    public function removeSession($name)
    {
        unset($_SESSION[$name]);
    }

    public function sessionDestroy()
    {
        session_destroy();
    }

    public function redirect($url, $is301 = false)
    {
        if ($is301 == true) {
            Header("HTTP/1.1 301 Moved Permanently"); //无此句代表302，301永久，302暂时
        }
        header('Location: ' . $url);
        exit;
    }
}