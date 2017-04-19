<?php
/**
 * 通过配置里的正则定位Url对应的Controller
 *
 * @package SPF.Routing
 * @author  XiaodongPan
 * @version $Id: MapRouter.php 2017-04-18 $
 */
namespace SPF\Routing;

class MapRouter extends Router
{
    /**
     * @const string
     */
    const DEFAULT_ACTION = 'handleRequest';

    /**
     * @var array
     */
    private $mappings = [];

    /**
     * @var array
     */
    private $matches;

    /**
     * @var string
     */
    private $controller;

    /**
     * @var string
     */
    private $action = self::DEFAULT_ACTION;

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
     * set mappings
     *
     * @param $mappings
     */
    public function setMappings($mappings)
    {
        $this->mappings = $mappings;
    }

    /**
     * 根据映射匹配Controller
     *
     * @return bool
     */
    public function mapping()
    {
        $path = $this->getPath();
        foreach ($this->mappings as $className => $patterns) {
            foreach ($patterns as $pattern) {
                if (preg_match('/' . $pattern . '/', $path, $matches)) {
                    $this->matches = $matches;
                    if (class_exists($className)) {
                        $this->controller = $className;
                        return true;
                    }
                }
            }
        }
        $this->notFound();
    }

    /**
     * 获取正则匹配值
     *
     * @return mixed
     */
    public function getMatches()
    {
        return $this->matches;
    }
}