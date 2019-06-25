<?php
/**
 * Created by PhpStorm.
 * User: miku
 * Date: 2019/2/13
 * Time: 9:39
 */

namespace vendor\project\helpers;


class Wechat
{
    const APP_ID = 'wxf7613d39c63057cd';
    const SECRET = '26efc892fbe387d62dd131f6eedc5fd1';

    /**
     * 判断是否微信访问
     * @return bool
     */
    public static function isWechat()
    {
        if (strpos($_SERVER['HTTP_USER_AGENT'], 'MicroMessenger') !== false) {
            return true;
        }
        return false;
    }

    /**
     * 获取统一接口调用access_token
     * @return bool|string
     */
    public static function getUnifiedAccessToken()
    {
        if ($access_token = redis::app()->get('UnifiedAccessToken')) {
            return $access_token;
        }
        $url = 'https://api.weixin.qq.com/cgi-bin/token?grant_type=client_credential';
        $url .= '&appid=' . self::APP_ID;
        $url .= '&secret=' . self::SECRET;
        $re = file_get_contents($url);
        $re = json_decode($re, true);
        if (isset($re['access_token'])) {
            redis::app()->set('UnifiedAccessToken', $re['access_token'], $re['expires_in'] - 200);
            return $re['access_token'];
        }
        return false;
    }

    /**
     * 获取用户授权code链接
     * @param string $redirect
     * @return string
     */
    public static function getUserAuthorizeCodeUrl($redirect = 'http://m.en.ink/login/login/login-w.html')
    {
        $url = 'https://open.weixin.qq.com/connect/oauth2/authorize?';
        $url .= 'appid=' . self::APP_ID;
        $url .= '&redirect_uri=' . urlencode($redirect);
        $url .= '&response_type=code&scope=snsapi_userinfo#wechat_redirect';
        return $url;
    }

    /**
     * 获取用户授权access_token
     * @param string $code 用户授权code
     * @return bool|mixed|string
     */
    public static function getUserAuthorizeAccessToken($code = '')
    {
        $url = 'https://api.weixin.qq.com/sns/oauth2/access_token?';
        $url .= 'appid=' . self::APP_ID;
        $url .= '&secret=' . self::SECRET;
        $url .= '&code=' . $code;
        $url .= '&grant_type=authorization_code';
        $re = file_get_contents($url);
        $re = json_decode($re, true);
        if (isset($re['errcode'])) {
            return false;
        }
        return $re;
    }
}