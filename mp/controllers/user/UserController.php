<?php
/**
 * Created by PhpStorm.
 * User: miku
 * Date: 2019/6/25
 * Time: 9:20
 */

namespace app\controllers\user;


use app\controllers\basis\AuthController;
use vendor\project\helpers\Constant;

class UserController extends AuthController
{
    /**
     * 个人中心
     * @return string
     */
    public function actionCenter()
    {
        return $this->render('center.html', [
            'moneys' => explode('.', (string)(sprintf("%.2f", \Yii::$app->user->identity->money))),
            'serviceTel' => Constant::serviceTel()
        ]);
    }
}