<?php
/**
 * Created by PhpStorm.
 * User: miku
 * Date: 2019/5/30
 * Time: 15:31
 */

namespace app\controllers\basis;


use vendor\project\base\EnUser;
use vendor\project\helpers\Url;
use vendor\project\helpers\Wechat;

class AuthController extends BasisController
{
    public function beforeAction($action)
    {
        $re = parent::beforeAction($action);
        if (\Yii::$app->user->isGuest) {
            if ($model = EnUser::findOne(['open_id' => \Yii::$app->session->get('open_id')])) {
                \Yii::$app->user->login($model);
                return $re;
            }
            Url::remember();
            return $this->redirect(['user/login/login'])->send();
        }
        return $re;
    }
}