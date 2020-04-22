<?php
/**
 * Created by PhpStorm.
 *
 * @package
 * @author  XiaodongPan
 * @version $Id: ShopServiceImpl.php 2020-04-21 $
 */
namespace services\shop;

class ShopServiceImpl implements ShopServiceIf
{
    public function sayHello($name)
    {
        return "Hello $name";
    }
}