<?php
/**
 * Created by PhpStorm.
 * User: miku
 * Date: 2019/6/25
 * Time: 9:20
 */

namespace app\controllers\user;


use app\controllers\basis\AuthController;

class UserController extends AuthController
{
    /**
     * 个人中心
     * @return string
     */
    public function actionCenter()
    {
        var_dump(\Yii::$app->session->get('open_id', ''));exit();
        return $this->render('center.html', [
            'moneys' => explode('.', (string)(sprintf("%.2f", \Yii::$app->user->identity->money))),
            'tel' => \Yii::$app->user->identity->tel
        ]);
    }
}