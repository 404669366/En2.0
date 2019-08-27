<?php
/**
 * Created by PhpStorm.
 * User: miku
 * Date: 2019/8/21
 * Time: 13:03
 */

namespace app\controllers\wx;


use vendor\project\base\EnInvest;
use vendor\project\base\EnUser;
use vendor\project\helpers\Helper;
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

    /**
     * 微信充值回调
     * @return string
     */
    public function actionInvest()
    {
        $data = Helper::getXml();
        if (isset($data['return_code']) && $data['return_code'] == 'SUCCESS') {
            if ($model = EnInvest::findOne(['no' => $data['out_trade_no'], 'status' => 0])) {
                if (EnUser::addMoney($model->uid, $model->money)) {
                    $model->status = 1;
                    $model->save();
                    return Helper::returnXml(['return_code' => 'SUCCESS', 'return_msg' => 'OK']);
                }
                $model->status = 2;
                $model->save();
            }
        }
        return Helper::returnXml(['return_code' => 'FAIL', 'return_msg' => '充值失败']);
    }
}