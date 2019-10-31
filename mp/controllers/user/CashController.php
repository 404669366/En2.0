<?php
/**
 * Created by PhpStorm.
 * User: miku
 * Date: 2019/9/24
 * Time: 18:15
 */

namespace app\controllers\user;


use app\controllers\basis\AuthController;
use vendor\project\base\EnCash;
use vendor\project\base\EnIncome;

class CashController extends AuthController
{
    /**
     * 我的提现
     * @return string
     */
    public function actionList()
    {
        return $this->render('list.html', [
            'data' => EnCash::listDataByCenter(),
            'surplus' => EnIncome::getSurplus([3, 4], 2, \Yii::$app->user->id)
        ]);
    }

    /**
     * 用户提现
     * @param int $need
     * @return \yii\web\Response
     */
    public function actionCreate($need = 0)
    {
        return $this->goBack(EnCash::createBy2($need));
    }
}