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
     * 非微信页
     * @return string
     */
    public function actionNoWx()
    {
        Msg::set('请在微信中访问');
        return $this->render('no-wx.html');
    }

    /**
     * 微信授权回调
     * @param string $code
     * @return \yii\web\Response
     */
    public function actionAuth($code = '')
    {
        if ($code) {
            if ($info = Wechat::getUserAuthorizeAccessToken($code)) {
                if ($model = EnUser::findOne(['open_id' => $info['openid']])) {
                    \Yii::$app->user->login($model);
                    return $this->redirect(Url::getUrl());
                }
                \Yii::$app->session->set('open_id', $info['openid']);
            }
        }
        return $this->redirect(['user/login/login']);
    }
}