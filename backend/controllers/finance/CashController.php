<?php
/**
 * Created by PhpStorm.
 * User: miku
 * Date: 2019/11/7
 * Time: 9:43
 */

namespace app\controllers\finance;


use app\controllers\basis\CommonController;
use vendor\project\base\EnCash;
use vendor\project\base\EnIncome;
use vendor\project\helpers\Constant;
use vendor\project\helpers\Helper;
use vendor\project\helpers\Msg;

class CashController extends CommonController
{
    /**
     * 提现列表
     * @return string
     */
    public function actionList()
    {
        return $this->render('list', [
            'surplus' => EnIncome::getSurplus(2, 1, \Yii::$app->user->identity->company_id),
            'status' => Constant::cashStatus()
        ]);
    }

    /**
     * 提现数据
     * @return string
     */
    public function actionData()
    {
        return $this->rTableData(EnCash::getPageDataByCompany());
    }

    /**
     * 提现操作
     * @param string $no
     * @return string|\yii\web\Response
     */
    public function actionEdit($no = '')
    {
        if ($company_id = \Yii::$app->user->identity->company_id) {
            $surplus = EnIncome::getSurplus(2, 1, $company_id);
            $model = EnCash::findOne(['no' => $no]);
            if (!$model) {
                if ($last = EnCash::findOne(['type' => 1, 'key' => $company_id, 'status' => 0])) {
                    Msg::set('您已有进行中的提现');
                    return $this->redirect(['list']);
                }
                $max = EnCash::find()->where(['type' => 1, 'key' => $company_id, 'status' => [1, 2]])->max('created_at');
                if (date('Y-m', $max) == date('Y-m')) {
                    Msg::set('本月您已发起过提现');
                    return $this->redirect(['list']);
                }
                $model = new EnCash();
                $model->no = Helper::createNo('C');
                $model->type = 1;
                $model->key = $company_id;
                $model->status = 0;
            }
            if (\Yii::$app->request->isPost) {
                $model->created_at = time();
                $model->load(['EnCash' => \Yii::$app->request->post()]);
                if ($model->money > $surplus) {
                    $model->addError('money', '提现金额大于本金');
                }
                if ($model->save()) {
                    Msg::set('申请成功');
                    return $this->redirect(['list']);
                }
                Msg::set($model->errors());
            }
            return $this->render('edit', [
                'model' => $model,
                'surplus' => $surplus
            ]);
        }
        Msg::set('非法操作');
        return $this->redirect(['list']);
    }
}