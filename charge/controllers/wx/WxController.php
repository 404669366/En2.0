<?php
/**
 * Created by PhpStorm.
 * User: miku
 * Date: 2019/8/21
 * Time: 13:03
 */

namespace app\controllers\wx;


use vendor\project\base\EnUser;
use vendor\project\helpers\Msg;
use vendor\project\helpers\Url;
use vendor\project\helpers\Wechat;
use yii\web\Controller;

class WxController extends Controller
{
    /**
     * 微信授权回调
     * @param string $code
     * @return \yii\web\Response
     */
    public function actionAuth($code = '')
    {
        if ($code) {
            if ($info = Wechat::getUserAuthorizeAccessToken($code)) {
                \Yii::$app->session->set('open_id', $info['openid']);
                return $this->redirect(Url::getUrl());
            }
        }
        return $this->redirect(['base/error/error']);
    }
}