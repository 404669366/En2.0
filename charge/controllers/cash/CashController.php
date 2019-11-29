<?php
/**
 * Created by PhpStorm.
 * User: miku
 * Date: 2019/11/29
 * Time: 12:37
 */

namespace app\controllers\cash;


use app\controllers\basis\AuthController;
use vendor\project\base\EnCash;

class CashController extends AuthController
{
    /**
     * 申请提现
     * @return string
     */
    public function actionCreate()
    {
        $money = '';
        if (\Yii::$app->request->isPost) {
            $money = \Yii::$app->request->post('money');
            if (EnCash::createBy3($money)) {
                return $this->redirect(['list']);
            }
        }
        return $this->render('create.html', [
            'csrf' => \Yii::$app->request->csrfToken,
            'have' => \Yii::$app->user->getIdentity()->money,
            'money' => $money
        ]);
    }

    /**
     * 提现记录
     * @return string
     */
    public function actionList()
    {
        return $this->render('list.html', [
            'data' => EnCash::listDataByCenter(3),
            'now' => date('Y-m-d H:i:s')
        ]);
    }
}