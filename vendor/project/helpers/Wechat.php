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
     * @return mixed|string
     */
    public static function getUnifiedAccessToken()
    {
        if ($access_token = \Yii::$app->cache->get('UnifiedAccessToken')) {
            return $access_token;
        }
        $url = 'https://api.weixin.qq.com/cgi-bin/token?grant_type=client_credential';
        $url .= '&appid=' . self::APP_ID;
        $url .= '&secret=' . self::SECRET;
        $re = json_decode(file_get_contents($url), true);
        if (isset($re['access_token'])) {
            \Yii::$app->cache->set('UnifiedAccessToken', $re['access_token'], $re['expires_in'] - 300);
            return $re['access_token'];
        }
        return '';
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
     * 获取ticket
     * @return mixed|string
     */
    public static function getTicket()
    {
        if ($ticket = \Yii::$app->cache->get('WeiXinTicket')) {
            return $ticket;
        }
        $access_token = self::getUnifiedAccessToken();
        $re = json_decode(file_get_contents("https://api.weixin.qq.com/cgi-bin/ticket/getticket?type=jsapi&access_token=$access_token"), true);
        if (isset($re['ticket'])) {
            \Yii::$app->cache->set('WeiXinTicket', $re['ticket'], 3600);
            return $re['ticket'];
        }
        return '';
    }

    /**
     * 获取扫码JsApi参数
     * @param int $time
     * @param string $nonceStr
     * @return array
     */
    public static function getJsApiParams($time = 0, $nonceStr = '')
    {
        $protocol = (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off' || $_SERVER['SERVER_PORT'] == 443) ? "https://" : "http://";
        $url = "$protocol$_SERVER[HTTP_HOST]$_SERVER[REQUEST_URI]";
        $nonceStr = $nonceStr ?: Helper::randStr(6);
        $time = $time ?: time();
        $ticket = self::getTicket();
        $signature = sha1("jsapi_ticket={$ticket}&noncestr={$nonceStr}&timestamp={$time}&url={$url}");
        return [
            'appId' => self::APP_ID,
            'nonceStr' => $nonceStr,
            'time' => $time,
            'signature' => $signature
        ];
    }

    /**
     * native支付下单
     * @param string $order
     * @param int $money
     * @param string $backUrl
     * @return array|bool|mixed|string
     */
    public static function nativePay($order = '', $money = 0, $backUrl = 'http://pc.en.ink/wx/pay/back.html')
    {
        $data = self::addSign([
            'appid' => self::APP_ID,
            'body' => '场站认购支付定金',
            'mch_id' => self::MCH_ID,
            'nonce_str' => Helper::randStr(6),
            'notify_url' => $backUrl,
            'openid' => '',
            'out_trade_no' => $order,
            'spbill_create_ip' => Helper::getIp(),
            'total_fee' => $money * 100,
            'trade_type' => 'NATIVE',
        ]);
        $data = Helper::curlXml('https://api.mch.weixin.qq.com/pay/unifiedorder', $data);
        if (isset($data['return_code']) && $data['return_code'] == 'SUCCESS' && isset($data['result_code']) && $data['result_code'] == 'SUCCESS') {
            return $data;
        }
        return false;
    }

    /**
     * h5支付下单
     * @param string $order
     * @param int $money
     * @param string $backUrl
     * @return array|bool|mixed
     */
    public static function h5Pay($order = '', $money = 0, $backUrl = 'http://c.en.ink/wx/wx/invest.html')
    {

        $params = [
            'appid' => self::APP_ID,
            'body' => '亿能充电-余额充值',
            'mch_id' => self::MCH_ID,
            'nonce_str' => Helper::randStr(6),
            'notify_url' => $backUrl,
            'openid' => '',
            'out_trade_no' => $order,
            'spbill_create_ip' => Helper::getIp(),
            'total_fee' => $money * 100,
            'trade_type' => 'MWEB',
        ];
        $data = Helper::curlXml('https://api.mch.weixin.qq.com/pay/unifiedorder', self::addSign($params));
        if (isset($data['return_code']) && $data['return_code'] == 'SUCCESS' && isset($data['result_code']) && $data['result_code'] == 'SUCCESS') {
            return $data;
        }
        return [];
    }

    /**
     * js支付下单
     * @param string $order
     * @param int $money
     * @param string $backUrl
     * @return array|bool|mixed
     */
    public static function jsPay($order = '', $money = 0, $backUrl = 'http://c.en.ink/wx/wx/invest.html')
    {

        $params = [
            'appid' => self::APP_ID,
            'body' => '亿能充电-余额充值',
            'mch_id' => self::MCH_ID,
            'nonce_str' => Helper::randStr(6),
            'notify_url' => $backUrl,
            'openid' => \Yii::$app->session->get('open_id', 'ouYAL6NYHx6AV6jWcGnYDesKt8WU'),
            'out_trade_no' => $order,
            'spbill_create_ip' => Helper::getIp(),
            'total_fee' => $money * 100,
            'trade_type' => 'JSAPI',
        ];
        $data = Helper::curlXml('https://api.mch.weixin.qq.com/pay/unifiedorder', self::addSign($params));
        if (isset($data['return_code']) && $data['return_code'] == 'SUCCESS' && isset($data['result_code']) && $data['result_code'] == 'SUCCESS') {
            return self::addSign([
                'appId' => $data['appid'],
                'timeStamp' => time(),
                'nonceStr' => $data['nonce_str'],
                'package' => "prepay_id={$data['prepay_id']}",
                'signType' => 'MD5',
            ], 'paySign');
        }
        return [];
    }

    /**
     * 追加签名并拼接xml
     * @param array $params
     * @param string $signName
     * @return string
     */
    private static function addSign($params = [], $signName = 'sign')
    {
        $xml = '<xml>';
        $ascii_str = '';
        foreach ($params as $k => $v) {
            if ($v) {
                $xml .= "<$k>$v</$k>";
                $ascii_str .= $k . '=' . $v . '&';
            }
        }
        $sign = $ascii_str . 'key=' . self::MCH_SECRET;
        $sign = strtoupper(md5($sign));
        $xml .= "<$signName>$sign</$signName>";
        return $xml . '</xml>';
    }
}