<?php
/**
 * Created by PhpStorm.
 * @package /
 * @author  XiaodongPan
 * @version $Id: Validator.php 2017-01-22 $
 */
class SPF_Validator
{
    public static function isEmail($string)
    {
        $pattern='/\w+@\w+\.\w+$/';
        return preg_match($pattern, $string) == true ? true : false;
    }
}