<?php
/**
 * Created by PhpStorm.
 * User: miku
 * Date: 2019/5/30
 * Time: 15:31
 */

namespace app\controllers\basis;


use vendor\project\helpers\Msg;
use vendor\project\helpers\Url;

class AuthController extends BasisController
{
    public function beforeAction($action)
    {
        $re = parent::beforeAction($action);
        if (\Yii::$app->user->isGuest) {
            Msg::set('请先登录');
            Url::remember();
            return $this->redirect([\Yii::$app->params['loginRoute']])->send();
        }
        return $re;
    }
}