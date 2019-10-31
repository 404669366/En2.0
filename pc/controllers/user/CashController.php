<?php
/**
 * Created by PhpStorm.
 * User: miku
 * Date: 2019/9/24
 * Time: 18:15
 */

namespace app\controllers\user;


use vendor\project\base\EnCash;
use vendor\project\base\EnIncome;
use vendor\project\helpers\Msg;

class CashController extends UserController
{
    /**
     * 我的提现
     * @return string
     */
    public function actionList()
    {
        return $this->render('list.html', ['data' => EnCash::listDataByCenter()]);
    }

    /**
     * 用户提现
     * @return string|\yii\web\Response
     */
    public function actionCreate()
    {
        if (\Yii::$app->request->isPost) {
            Msg::set(EnCash::createBy2(\Yii::$app->request->post('need', 0)));
        }
        return $this->render('create.html', [
            'csrf' => \Yii::$app->request->csrfToken
        ]);
    }
}