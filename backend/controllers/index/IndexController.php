<?php
/**
 * Created by PhpStorm.
 * User: miku
 * Date: 2019/5/29
 * Time: 14:34
 */

namespace app\controllers\index;


use app\controllers\basis\AuthController;
use vendor\project\base\EnPower;

class IndexController extends AuthController
{

    public function actionIndex()
    {
        $this->layout = false;
        $data['tel'] = \Yii::$app->user->getIdentity()->tel;
        $data['menus'] = EnPower::getUserMenus();
        return $this->render('index', ['data' => $data]);
    }

    public function actionFirst()
    {
        return $this->render('first');
    }
}