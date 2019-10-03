<?php
/**
 * Created by PhpStorm.
 * User: miku
 * Date: 2019/6/25
 * Time: 9:20
 */

namespace app\controllers\user;


use app\controllers\basis\AuthController;
use vendor\project\helpers\client;

class UserController extends AuthController
{
    /**
     * 个人中心
     * @return string
     */
    public function actionCenter()
    {
        (new client())->__unset('GunInfo');
        (new client())->__unset('PileInfo');
        (new client())->__unset('FieldInfo');
        (new client())->__unset('ChargeOrder');
        return $this->render('center.html', [
            'moneys' => explode('.', (string)(sprintf("%.2f", \Yii::$app->user->identity->money))),
            'tel' => \Yii::$app->user->identity->tel
        ]);
    }
}