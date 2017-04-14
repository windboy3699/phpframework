<?php
/**
 * 公共方法
 *
 * @package SPF\Base
 * @author  XiaodongPan
 * @version $Id: Util.php 2017-04-12 $
 */
namespace SPF\Base;

class Util
{
    /**
     * 合并数组
     */
    public static function recurMerge($arr1, $arr2) {
        if (!is_array($arr1) || !is_array($arr2)) {
            $arr1 = $arr2;
        } else {
            foreach ($arr2 as $key => $item) {
                if (isset($arr1[$key]) && is_array($arr1[$key])) {
                    $arr1[$key] = self::recurMerge($arr1[$key], $item);
                } else {
                    $arr1[$key] = $item;
                }
            }
        }
        return $arr1;
    }

    /**
     * addslashes
     * Key不允许出现引号
     */
    public static function recurAddslashes(&$var)
    {
        if (is_array($var)) {
            foreach ($var as $key=>$value) {
                if (preg_match('/[\"\'\\\]/', $key)) {
                    unset($var[$key]);
                } else {
                    self::recurAddslashes($var[$key]);
                }
            }
        } else {
            $var = addslashes($var);
        }
    }

    /**
     * stripslashes
     */
    public static function recurStripslashes(&$var)
    {
        if (is_array($var)) {
            foreach ($var as $key=>$value) {
                self::recurStripslashes($var[$key]);
            }
        } else {
            $var = stripslashes($var);
        }
    }

    /**
     * BASE32编码
     * @param string $input     需要编码的字符串
     * @return string           编码后的字符串
     */
    public static function base32encode($input)
    {
        //Reference: http://www.ietf.org/rfc/rfc3548.txt
        $BASE32_ALPHABET = 'aBcDeFgHiJkLmNoPqRsTuVwXyZ234567';
        $output = '';
        $v = 0;
        $vbits = 0;
        for ($i = 0, $j = strlen($input); $i < $j; $i++) {
            $v <<= 8;
            $v += ord($input [$i]);
            $vbits += 8;
            while ($vbits >= 5) {
                $vbits -= 5;
                $output .= $BASE32_ALPHABET [$v >> $vbits];
                $v &= ( (1 << $vbits) - 1);
            }
        }
        if ($vbits > 0) {
            $v <<= ( 5 - $vbits);
            $output .= $BASE32_ALPHABET [$v];
        }
        return $output;
    }

    /**
     * BASE32解码
     * @param string $input     需要解码的字符串
     * @return string           解码后的字符串
     */
    public static function base32decode($input)
    {
        $output = '';
        $v = 0;
        $vbits = 0;
        $input = strtolower($input);
        for ($i = 0, $j = strlen($input); $i < $j; $i++) {
            $v <<= 5;
            if ($input [$i] >= 'a' && $input [$i] <= 'z') {
                $v += ( ord($input [$i]) - 97);
            } elseif ($input [$i] >= '2' && $input [$i] <= '7') {
                $v += ( 24 + $input [$i]);
            }
            $vbits += 5;
            while ($vbits >= 8) {
                $vbits -= 8;
                $output .= chr($v >> $vbits);
                $v &= ( (1 << $vbits) - 1);
            }
        }
        return $output;
    }
}