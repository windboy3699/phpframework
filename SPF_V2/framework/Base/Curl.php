<?php
/**
 * Do Http Request
 *
 * @package SPF\Base
 * @author  XiaodongPan
 * @version $Id: Curl.php 2017-04-12 $
 */
namespace SPF\Base;

class Curl
{
    const DEFAULT_CONNECT_TIMEOUT_MS = 500;

    const DEFAULT_TIMEOUT_MS = 510;

    const HTTP_CODE_SUCCESS = 200;

    const HEADER_KEY_CONTENT_TYPE = 'Content-Type';

    const HEADER_VALUE_CONTENT_TYPE_JSON = 'application/json';

    private static $instance;

    private $curl;

    private $lastInfo = array();

    private $defaultHeaders = array();

    private $runTime;

    public static function getInstance()
    {
        if (empty(self::$instance)) {
            self::$instance = new self();
        }
        return self::$instance;
    }

    /**
     * 执行Get请求
     * @param string $url
     *     xxx.com/{foo}/{bar}/?foo1={foo1}
     * @param array $params
     *     array('foo'=>?,'bar'=>?)
     * @param array $header
     *     array('Cookie'=>'x=?')
     */
    public function get($url, $params = array(), $headers = array(), $timeout = null, $connectTimeout = null)
    {
        $this->initAttribute();
        return $this->doRequest($this->buildUrl($url, $params), $headers, $timeout, $connectTimeout);
    }

    /**
     * 执行Post请求
     * @param $url
     * @param $postParams
     * @param array $getParams
     * @param array $headers
     * @param null $timeout
     * @param null $connectTimeout
     * @return string
     */
    public function post($url, $postParams, $getParams = array(), $headers = array(), $timeout = null, $connectTimeout = null)
    {
        $postbody = is_array($postParams) ? http_build_query($postParams) : $postParams;
        return $this->doOriginPost($url, $postbody, $getParams, $headers, $timeout, $connectTimeout);
    }
    //上传文件
    public function postFile($url, $postParams, $getParams = array(), $headers = array(), $timeout = null, $connectTimeout = null)
    {
        return $this->doOriginPost($url, $postParams, $getParams, $headers, $timeout, $connectTimeout);
    }

    /**
     * 非字符串的body会以json的方式发送到服务器端
     * @param string $url
     * @param string|array $postParams
     * @param array $getParams
     * @param array $headers
     * @param array $timeout
     * @return string
     */
    public function postJson($url, $postParams, $getParams = array(), $headers = array(), $timeout = null, $connectTimeout = null)
    {
        $postbody = $postParams;
        if (!is_string($postParams)) {
            $postbody = json_encode($postParams, JSON_FORCE_OBJECT);
        }
        if (empty($headers) || empty($headers[self::HEADER_KEY_CONTENT_TYPE])) {
            $headers[self::HEADER_KEY_CONTENT_TYPE] = self::HEADER_VALUE_CONTENT_TYPE_JSON;
        }
        return $this->doOriginPost($url, $postbody, $getParams, $headers, $timeout, $connectTimeout);
    }

    /**
     * 获取执行信息
     * @return array
     */
    public function getLastInfo()
    {
        return $this->lastInfo;
    }

    /**
     * 执行Post
     * @param $url
     * @param string $postBody
     * @param array $getParams
     * @param array $headers
     * @param null $timeout
     * @param null $connectTimeout
     * @return string
     */
    private function doOriginPost($url, $postBody = "", $getParams = array(), $headers = array(), $timeout = null, $connectTimeout = null)
    {
        $this->initAttribute();
        $this->setAttribute(CURLOPT_POST, 1);
        $this->setAttribute(CURLOPT_POSTFIELDS, $postBody);
        return $this->doRequest($this->buildUrl($url, $getParams), $headers, $timeout, $connectTimeout);
    }

    /**
     * 约定只有返回码是200时，才返回数据，否则返回false
     * @param string $url
     * @param array $params
     * @param array $headers
     * @return string
     */
    private function doRequest($url, $headers, $timeout, $connectTimeout)
    {
        if (empty($url)) {
            return false;
        }
        $startTime = microtime(true);
        $this->setTimeout($timeout, $connectTimeout);
        $this->setAttribute(CURLOPT_URL, $url);
        $this->setHeaders($headers);
        $this->executeCurl();
        $endTime = microtime(true);
        $this->runTime = $endTime - $startTime;
        curl_close($this->curl);
        return $this->lastInfo['httpCode'] == self::HTTP_CODE_SUCCESS ? $this->lastInfo['responseText'] : false;
    }

    /**
     * 执行curl
     */
    private function executeCurl()
    {
        $result = curl_exec($this->curl);
        $lastInfo = [];
        $lastInfo['debugInfo'] = curl_getinfo($this->curl);
        $lastInfo['httpCode'] = $lastInfo['debugInfo']['http_code'];
        $lastInfo['errorNo'] = curl_errno($this->curl);
        $lastInfo['errorInfo'] = curl_error($this->curl);
        $lastInfo['responseText'] = $result;
        $this->lastInfo = $lastInfo;
    }

    /**
     * 初始化参数
     */
    private function initAttribute()
    {
        $this->curl = curl_init();
        $this->setAttribute(CURLOPT_POST, 0);
        $this->setAttribute(CURLOPT_RETURNTRANSFER, 1);
        $this->setAttribute(CURLOPT_NOSIGNAL, 1);
    }

    /**
     * 设置Header
     * @param $headers
     */
    private function setHeaders($headers)
    {
        if (empty($headers)) {
            return;
        }
        $headers = array_merge($headers, $this->defaultHeaders);
        $httpHeaders = array();
        foreach ($headers as $key => $val) {
            $httpHeaders[] = "$key: $val";
        }
        $this->setAttribute(CURLOPT_HTTPHEADER, $httpHeaders);
    }

    /**
     * 设置参数
     * @param $attribute
     * @param $value
     */
    private function setAttribute($attribute, $value)
    {
        curl_setopt($this->curl, $attribute, $value);
    }

    /**
     * 设置超时
     * @param $timeout
     * @param $connectTimeout
     */
    private function setTimeout($timeout, $connectTimeout)
    {
        $connTimeout = self::DEFAULT_CONNECT_TIMEOUT_MS;
        $execTimeout = self::DEFAULT_TIMEOUT_MS;
        if ($connectTimeout !== null && $connectTimeout > 0) {
            $connTimeout = $connectTimeout;
            if ($timeout !== null && $timeout > 0) {
                $execTimeout = $timeout;
            }
        } elseif ($timeout !== null && $timeout > 1) {
            $connTimeout = $timeout;
            $execTimeout = $timeout + 100;
        }
        $this->setAttribute(CURLOPT_CONNECTTIMEOUT_MS, $connTimeout);
        $this->setAttribute(CURLOPT_TIMEOUT_MS, $execTimeout);
    }

    /**
     * 构造url
     * @param $originUrl
     * @param $pairs
     * @return mixed|string
     */
    private function buildUrl($originUrl, $pairs)
    {
        $replaceSearch = array();
        $replaceReplace = array();
        foreach ($pairs as $key => $val) {
            $replaceSearch[] = '{' . $key . '}';
            $replaceReplace[] = urlencode($val);
        }
        $matches = array();
        preg_match_all('#{([^}]+)}#', $originUrl, $matches);

        $noRestPaires = array();
        if (!empty($matches[1])) {
            //有rest风格，找到剩下的参数
            foreach ($pairs as $key => $val) {
                if (!in_array($key, $matches[1])) {
                    $noRestPaires[$key] = $val;
                }
            }
        } else {
            //没有，全部是传统的?&方式的
            $noRestPaires = $pairs;
        }
        $url = $originUrl;
        if (!empty($replaceSearch)) {
            $url = str_replace($replaceSearch, $replaceReplace, $originUrl);
        }
        if (!empty($noRestPaires)) {
            $query = http_build_query($noRestPaires);
            $query = preg_replace('/%5B(?:[0-9]|[1-9][0-9]+)%5D=/', '=', $query); //foo=x&foo=y
            $url = $this->addSuffixParams($url, $query);
        }
        return $url;
    }

    /**
     * 增加后缀
     * @param $originUrl
     * @param $urlParams
     * @return string
     */
    private function addSuffixParams($originUrl, $urlParams)
    {
        if (strpos($originUrl, '?') !== false) {
            $lastChar = $originUrl[strlen($originUrl) - 1];
            $originUrl = $lastChar === '&' ? $originUrl .= $urlParams : $originUrl .= '&' . $urlParams;
        } else {
            $originUrl .= '?' . $urlParams;
        }
        return $originUrl;
    }
}
