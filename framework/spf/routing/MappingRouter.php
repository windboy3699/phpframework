<?php
/**
 * 通过配置里的正则定位Url对应的Controller
 *
 * @package SPF.Routing
 * @author  XiaodongPan
 * @version $Id: MappingRouter.php 2017-04-18 $
 */
namespace spf\routing;

class MappingRouter extends Router
{
    /**
     * @const string
     */
    const SEPARATE = '::';

    /**
     * @var string
     */
    private $defaultAction = 'indexAction';

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
    protected $action;

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
    public function parse()
    {
        $path = $this->getPath();
        foreach ($this->mappings as $method => $patterns) {
            foreach ($patterns as $pattern) {
                if (preg_match('/' . $pattern . '/', $path, $matches)) {
                    $this->matches = $matches;
                    $fullMethod = $this->appNamespace . '\\' . $method;
                    $segs = explode(self::SEPARATE, $fullMethod);
                    $controllerName = $segs[0];
                    $actionName = $segs[1] ? $segs[1] : $this->defaultAction;
                    if (class_exists($controllerName)) {
                        $this->controller = $controllerName;
                        $this->action = $actionName;
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