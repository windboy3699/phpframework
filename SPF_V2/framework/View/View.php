<?php
/**
 * View
 *
 * @package SPF.View
 * @author  XiaodongPan
 * @version $Id: View.php 2017-04-12 $
 */
namespace SPF\View;

require_once dirname(dirname(dirname(__FILE__))) . '/lib/Twig/Autoloader.php';
\Twig_Autoloader::register();

class View
{
    private static $options = [
        'debug' => false,
        'charset' => 'UTF-8',
        'base_template_class' => 'Twig_Template',
        'strict_variables' => false,
        'autoescape' => 'html',
        'cache' => false,
        'auto_reload' => null,
        'optimizations' => -1,
    ];

    /**
     * 创建模板引擎
     * @return Twig_Environment
     */
    public static function create($path, $options = array())
    {
        $options = array_merge(self::$options, $options);
        return new \Twig_Environment(new \Twig_Loader_Filesystem($path), $options);
    }
}