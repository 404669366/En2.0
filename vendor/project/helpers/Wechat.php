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
    //平台参数
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
            \Yii::$app->cache->set('UnifiedAccessToken', $re['access_token'], 60 * 60);
            return $re['access_token'];
        }
        return '';
    }

    /**
     * 获取用户授权code链接
     * @param string $redirect
     * @return string
     */
    public static function getUserAuthorizeCodeUrl($redirect = '')
    {
        $url = 'https://open.weixin.qq.com/connect/oauth2/authorize?';
        $url .= 'appid=' . self::APP_ID;
        $url .= '&redirect_uri=' . urlencode(Helper::spliceUrl($redirect));
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
     * @return array
     */
    public static function getJsApiParams()
    {
        $protocol = (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off' || $_SERVER['SERVER_PORT'] == 443) ? "https://" : "http://";
        $url = "$protocol$_SERVER[HTTP_HOST]$_SERVER[REQUEST_URI]";
        $nonceStr = Helper::randStr(6);
        $time = time();
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
     * @param string $body
     * @param string $order
     * @param int $money
     * @param string $backUrl
     * @return array|bool|mixed
     */
    public static function nativePay($body = '', $order = '', $money = 0, $backUrl = '')
    {
        $params = [
            'appid' => self::APP_ID,
            'body' => $body,
            'mch_id' => self::MCH_ID,
            'nonce_str' => Helper::randStr(6),
            'notify_url' => Helper::spliceUrl($backUrl),
            'openid' => '',
            'out_trade_no' => $order,
            'spbill_create_ip' => Helper::getIp(),
            'total_fee' => $money * 100,
            'trade_type' => 'NATIVE',
        ];
        $data = Helper::curlXml('https://api.mch.weixin.qq.com/pay/unifiedorder', self::addSign($params, self::MCH_SECRET));
        if (isset($data['result_code']) && $data['result_code'] == 'SUCCESS') {
            return $data;
        }
        return false;
    }

    /**
     * h5支付下单
     * @param string $body
     * @param string $order
     * @param int $money
     * @param string $backUrl
     * @param string $redirect
     * @return bool|string
     */
    public static function h5Pay($body = '', $order = '', $money = 0, $backUrl = '', $redirect = '')
    {

        $params = [
            'appid' => self::APP_ID,
            'body' => $body,
            'mch_id' => self::MCH_ID,
            'nonce_str' => Helper::randStr(6),
            'notify_url' => Helper::spliceUrl($backUrl),
            'openid' => '',
            'out_trade_no' => $order,
            'spbill_create_ip' => Helper::getIp(),
            'total_fee' => $money * 100,
            'trade_type' => 'MWEB',
        ];
        $data = Helper::curlXml('https://api.mch.weixin.qq.com/pay/unifiedorder', self::addSign($params, self::MCH_SECRET));
        var_dump($data);exit();
        if (isset($data['result_code']) && $data['result_code'] == 'SUCCESS') {
            return $data['mweb_url'] . '&redirect_url=' . Helper::spliceUrl($redirect);
        }
        return false;
    }

    /**
     * js支付下单
     * @param string $body
     * @param string $order
     * @param int $money
     * @param string $backUrl
     * @return array|bool|mixed
     */
    public static function jsPay($body = '', $order = '', $money = 0, $backUrl = '')
    {

        $params = [
            'appid' => self::APP_ID,
            'body' => $body,
            'mch_id' => self::MCH_ID,
            'nonce_str' => Helper::randStr(6),
            'notify_url' => Helper::spliceUrl($backUrl),
            'openid' => \Yii::$app->session->get('open_id', ''),
            'out_trade_no' => $order,
            'spbill_create_ip' => Helper::getIp(),
            'total_fee' => $money * 100,
            'trade_type' => 'JSAPI',
        ];
        $data = Helper::curlXml('https://api.mch.weixin.qq.com/pay/unifiedorder', self::addSign($params, self::MCH_SECRET));
        var_dump($data);exit();
        if (isset($data['result_code']) && $data['result_code'] == 'SUCCESS') {
            $data = [
                'appId' => $data['appid'],
                'nonceStr' => $data['nonce_str'],
                'package' => "prepay_id={$data['prepay_id']}",
                'signType' => 'MD5',
                'timeStamp' => time(),
            ];
            $str = '';
            foreach ($data as $k => $v) {
                $str .= $k . '=' . $v . '&';
            }
            $str .= 'key=' . self::MCH_SECRET;
            $sign = strtoupper(md5($str));
            $data['paySign'] = $sign;
            return $data;
        }
        return false;
    }

    /**
     * 追加签名并拼接xml
     * @param array $params
     * @param string $mch_secret
     * @return string
     */
    private static function addSign($params = [], $mch_secret = '')
    {
        $xml = '<xml>';
        $ascii_str = '';
        foreach ($params as $k => $v) {
            if ($v) {
                $xml .= "<$k>$v</$k>";
                $ascii_str .= $k . '=' . $v . '&';
            }
        }
        $sign = $ascii_str . 'key=' . $mch_secret;
        $sign = strtoupper(md5($sign));
        $xml .= "<sign>$sign</sign>";
        return $xml . '</xml>';
    }
}