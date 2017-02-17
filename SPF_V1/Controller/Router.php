<?php
/**
 * 路由解析
 * @package /SPF/Controller
 * @author XiaodongPan
 * @version $Id Router.php 2016-10-25$
 */
class SPF_Controller_Router
{
    const DEFAULT_GROUP = '';
    const DEFAULT_CONTROLLER = 'index';
    const DEFAULT_ACTION = 'index';

    const C_A_SEPARATE = '::';

    private $path;

    /**
     * @var path中的group,controller,action
     */
    private $group;
    private $controller;
    private $action;

    /**
     * @var controller完整类名称 Sports_Video_PlayController
     */
    private $controllerName;

    /**
     * @var controller不带后缀名称 Sports_Video_Play
     */
    private $controllerNameNoSuffix;

    /**
     * @var 带action后缀 showAction
     */
    private $actionName;

    private $controllerPath = '';

    private $mapping = [];

    private $matches;

    private $errorRedirect = '';

    public function setControllerPath($path)
    {
        $this->controllerPath = $path;
    }

    public function setMapping($mapping)
    {
        $this->mapping = $mapping;
    }

    /**
     * 根据url解析路由
     * 首先去route.php配置文件匹配，匹配到执行controller
     * 否则根据group/controller/action规则解析
     * /sports/video/show
     * 第一次会解析 /sports/VideoController::showAction()
     * 如果未找到解析 /sports/video/ShowController::indexAction()
     */
    public function parse()
    {
        $controllerName = '';
        $path = $this->getPath();
        foreach ($this->mapping as $key => $patterns) {
            foreach ($patterns as $pattern) {
                $fullPattern = '/' . $pattern . '/';
                if (preg_match($fullPattern, $path, $matches)) {
                    $this->matches = $matches;
                    $caArray = explode(self::C_A_SEPARATE, $key);
                    $controllerArray = explode('_', $caArray[0]);
                    $endName = end($controllerArray);
                    $endName[0] = strtolower($endName[0]);
                    $this->controller = $endName;
                    $this->action = $caArray[1];
                    array_pop($controllerArray);
                    $this->group = $controllerArray ? strtolower(implode('/', $controllerArray)) : '';
                    $controllerName = $caArray[0] . 'Controller';
                }
            }
        }
        if (!$controllerName) {
            $gca = $this->getGca($path, true);
            $controllerName = $this->requireController($gca);
            if ($controllerName == null) {
                $gca = $this->getGca($path, false);
                $controllerName = $this->requireController($gca);
            }
            if (!$controllerName) {
                $this->notFound();
            }
            $this->group = $gca['group'];
            $this->controller = $gca['controller'];
            $this->action = $gca['action'];
        }
        $this->actionName = $this->action . 'Action';
        $this->controllerName = $controllerName;
        $this->controllerNameNoSuffix = str_replace('Controller', '', $this->controllerName);
    }

    /**
     * 获取Url路径
     * /sports/live/show/?id=100 => sports/live/show
     * @return mixed|string
     */
    public function getPath()
    {
        if ($this->path) {
            return $this->path;
        }
        //文件真实地址 如：/api/index.php
        $scriptUrl = isset($_SERVER['SCRIPT_NAME']) ? $_SERVER['SCRIPT_NAME'] : $_SERVER['PHP_SELF'];
        //scriptUrl的path 如：/api
        $baseUrl = rtrim(dirname($scriptUrl), '\\/');
        //除域名外的完整url包括参数 如：/sports/live/show/?id=100
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
     * 根据url路径获取Group、Controller、Action
     * @param $path
     * @param bool|true $hasAction
     * @return array
     */
    private function getGca($path, $hasAction = true)
    {
        $gca = [
            'group' => self::DEFAULT_GROUP,
            'controller' => self::DEFAULT_CONTROLLER,
            'action' => self::DEFAULT_ACTION,
        ];
        if (empty($path)) {
            return $gca;
        }
        $path = explode('/', $path);
        $count = count($path);
        if ($hasAction) {
            //把最后一位当action，最后第二位当Controller，剩下的当Group
            $gca['action'] = $path[$count - 1];
            isset($path[$count - 2]) && $gca['controller'] = $path[$count - 2];
            if (isset($path[$count - 3])) {
                $array = array_slice($path, 0, $count - 2);
                $gca['group'] = implode('/', $array);
            }
        } else {
            //Action固定是index，最后第一位当Controller，剩下的当Group
            $gca['controller'] = $path[$count - 1];
            if (isset($path[$count - 2])) {
                $array = array_slice($path, 0, $count - 1);
                $gca['group'] = implode('/', $array);
            }
        }
        return $gca;
    }

    /**
     * 加载Controller
     * @param $gca
     * @return bool|string
     */
    private function requireController($gca)
    {
        $group = $gca['group'];
        $controller = $gca['controller'];
        $action = $gca['action'];

        $controllerName = ucfirst($controller) . 'Controller';
        $dirname = $group ? strtolower($group) . '/' : '';
        $file = $this->controllerPath . '/' . $dirname . $controllerName . '.php';
        if (!file_exists($file)) {
            return null;
        }
        require_once($file);

        $array = $group ? explode('/', $group) : [];

        array_walk($array,function(&$v,$k){$v = ucfirst($v);});

        $classPrefix = $array ? implode('_', $array) : '';

        $controllerClass = $classPrefix ? $classPrefix . '_' . $controllerName : $controllerName;

        if (method_exists($controllerClass, $action . 'Action')) {
            return $controllerClass;
        }
        return null;
    }

    /**
     * 获取group
     * @return string
     */
    public function getGroup()
    {
        return $this->group;
    }

    /**
     * 获取controller
     * @return string
     */
    public function getController()
    {
        return $this->controller;
    }

    /**
     * 获取action
     * @return string
     */
    public function getAction()
    {
        return $this->action;
    }

    /**
     * 获取正则匹配值
     * @return mixed
     */
    public function getMatches()
    {
        return $this->matches;
    }

    /**
     * 获取控制器类名称
     * @return controller完整类名称
     */
    public function getControllerName()
    {
        return $this->controllerName;
    }

    /**
     * 获取控制器不带后缀名称
     * @return controller不带后缀名称
     */
    public function getControllerNameNoSuffix()
    {
        return $this->controllerNameNoSuffix;
    }

    /**
     * 获取action名称
     * @return 带action后缀
     */
    public function getActionName()
    {
        return $this->actionName;
    }

    /**
     * 设置错误显示页面
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
     * @param string $url
     */
    public function redirect($url)
    {
        header('Location: ' . $url);
        exit;
    }
}