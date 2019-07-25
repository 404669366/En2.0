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
    const MCH_ID = '1536153491';
    const MCH_SECRET = 'l8rimswnd2avx1ybroa4jveei9wkdfha';

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

    /**
     * 微信支付统一下单
     * @param string $order
     * @param int $money
     * @param string $callUrl
     * @param string $type
     * @return bool|mixed
     */
    public static function placeOrder($order = '', $money = 0, $callUrl = 'http://pc.en/ink/wx/pay/back.html', $type = 'NATIVE')
    {
        $data = self::addSign([
            'appid' => self::APP_ID,
            'body' => '场站认购支付定金',
            'mch_id' => self::MCH_ID,
            'nonce_str' => Helper::randStr(6),
            'notify_url' => $callUrl,
            'out_trade_no' => $order,
            'spbill_create_ip' => Helper::getIp(),
            'total_fee' => $money,
            'trade_type' => $type,
        ]);
        $re = Helper::curlXml('https://api.mch.weixin.qq.com/pay/unifiedorder', $data);
        if ($re['return_code'] == 'SUCCESS' && $re['result_code'] == 'SUCCESS') {
            return $re;
        }
        return false;
    }

    /**
     * 最加签名并拼接xml
     * @param array $params
     * @return string
     */
    private static function addSign($params = [])
    {
        $xml = '<xml>';
        $ascii_str = '';
        foreach ($params as $k => $v) {
            $xml .= "<$k>$v</$k>";
            $ascii_str .= $k . '=' . $v . '&';
        }
        $sign = $ascii_str . 'key=' . self::MCH_SECRET;
        $sign = strtoupper(md5($sign));
        $xml .= "<sign>$sign</sign>";
        return $xml . '</xml>';
    }
}