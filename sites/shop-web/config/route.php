<?php
/**
 * Created by PhpStorm.
 *
 * @package
 * @author  XiaodongPan
 * @version $Id: route.php 2017-04-18 $
 */

return [
    'mappings' => [
        'App\\Controller\\IndexController' => ['^$'],
        'App\\Controller\\Mobile\\V1\\HelloController' => ['^dem(o)$'],

    ]
];