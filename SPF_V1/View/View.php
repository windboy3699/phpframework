<?php
/**
 * Twig模版引擎包装
 * @package /SPF
 * @author  XiaodongPan
 * @version $Id: View.php 2016-11-04 $
 */
require_once dirname(__FILE__) . '/Base.php';

class SPF_View
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
        return new Twig_Environment(new Twig_Loader_Filesystem($path), $options);
    }
}