<?php
/**
 * Created by PhpStorm.
 * User: miku
 * Date: 2018/11/13
 * Time: 11:27
 */

namespace app\controllers\basis;

use vendor\project\helpers\Sms;

class SmsController extends BasisController
{
    /**
     * 发送验证码
     * @return string
     */
    public function actionSend()
    {
        $get = \Yii::$app->request->get();
        Sms::sendCode($get['tel']);
        return $this->rJson();
    }
}