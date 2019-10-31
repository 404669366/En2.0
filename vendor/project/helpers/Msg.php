<?php
/**
 * Created by PhpStorm.
 * User: miku
 * Date: 2018/10/15
 * Time: 14:41
 */

namespace vendor\project\helpers;

class Msg
{
    /**
     * 设置消息
     * @param string $msg
     * @param string $size
     */
    public static function set($msg = '', $size = '')
    {
        setcookie('message-data', $msg, time() + 60, '/');
        if ($size) {
            setcookie('message-size', $size, -1, '/');
        }
    }

    /**
     * 设置字体大小
     * @param string $size
     */
    public static function setSize($size = '1.5rem')
    {
        setcookie('message-size', $size, -1, '/');
    }
}