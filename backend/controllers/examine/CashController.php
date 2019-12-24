<?php
/**
 * Created by PhpStorm.
 * User: miku
 * Date: 2019/6/18
 * Time: 12:22
 */

namespace app\controllers\examine;


use app\controllers\basis\CommonController;
use vendor\project\base\EnCash;
use vendor\project\base\EnIntention;
use vendor\project\helpers\Constant;
use vendor\project\helpers\Msg;

class CashController extends CommonController
{
    /**
     * 列表页
     * @return string
     */
    public function actionList()
    {
        return $this->render('list', [
            'types' => Constant::cashType(),
            'status' => Constant::cashStatus(),
        ]);
    }

    /**
     * 列表数据
     * @return string
     */
    public function actionData()
    {
        return $this->rTableData(EnCash::getPageData());
    }

    /**
     * 详情页
     * @param $no
     * @return string
     */
    public function actionInfo($no)
    {
        $model = EnCash::findOne(['no' => $no]);
        if (\Yii::$app->request->isPost) {
            $post = \Yii::$app->request->post();
            $model->remark = $post['remark'];
            $oldStatus = $model->status;
            $model->status = $post['status'];
            if ($model->save()) {
                Msg::set('操作成功');
                return $this->redirect(['list']);
            } else {
                $model->status = $oldStatus;
                Msg::set($model->errors());
            }
        }
        return $this->render('info', [
            'model' => $model,
            'types' => Constant::cashType(),
            'status' => Constant::cashStatus(),
        ]);
    }

    /**
     * 确认打款
     * @param $no
     * @return string
     */
    public function actionSure($no)
    {
        Msg::set('非法操作');
        if ($model = EnCash::findOne(['no' => $no, 'status' => 1])) {
            $model->status = 2;
            if ($model->save()) {
                Msg::set('操作成功');
            } else {
                Msg::set($model->errors());
            }
        }
        return $this->redirect(['list']);
    }
}