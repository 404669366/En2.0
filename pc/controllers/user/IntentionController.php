<?php
/**
 * Created by PhpStorm.
 * User: miku
 * Date: 2019/7/23
 * Time: 10:55
 */

namespace app\controllers\user;


use app\controllers\basis\AuthController;
use vendor\project\base\EnField;
use vendor\project\base\EnFieldIntention;
use vendor\project\helpers\Constant;
use vendor\project\helpers\Msg;

class IntentionController extends AuthController
{
    /**
     * 我的意向
     * @return string
     */
    public function actionList()
    {
        $this->rUCenterUrl();
        return $this->render('list.html', ['data' => EnFieldIntention::getUserData()]);
    }

    /**
     * 编辑意向
     * @param $id
     * @return string
     */
    public function actionUpload($id)
    {
        if ($model = EnFieldIntention::findOne(['status' => [2, 5], 'id' => $id])) {
            if (\Yii::$app->request->isPost) {
                $post = \Yii::$app->request->post();
                $model->status = 3;
                if ($model->load(['EnFieldIntention' => $post]) && $model->validate() && $model->save()) {
                    Msg::set('保存成功');
                    return $this->redirect(['list']);
                }
                Msg::set($model->errors());
            }
            return $this->render('upload.html', [
                'model' => $model->attributes,
                '_csrf' => \Yii::$app->request->csrfToken,
            ]);
        }
        Msg::set('错误操作');
        return $this->redirect(['list']);
    }

    /**
     * 退还定金
     * @param $id
     * @return \yii\web\Response
     */
    public function actionBack($id)
    {
        Msg::set('错误操作');
        if ($model = EnFieldIntention::findOne(['status' => 2, 'id' => $id])) {
            Msg::set('退款超时');
            if (time() <= ($model->pay_at + Constant::orderBackTime())) {
                $model->status = 8;
                $model->save(false);
                Msg::set('申请成功');
            }
        }
        return $this->redirect(['list']);
    }

    /**
     * 撤销退款
     * @param $id
     * @return \yii\web\Response
     */
    public function actionRevoke($id)
    {
        Msg::set('错误操作');
        if ($model = EnFieldIntention::findOne(['status' => 8, 'id' => $id])) {
            $model->status = 2;
            $model->save(false);
            Msg::set('撤销成功');
        }
        return $this->redirect(['list']);
    }

    /**
     * 用户删除
     * @param $id
     * @return \yii\web\Response
     */
    public function actionDel($id)
    {
        Msg::set('错误操作');
        if ($model = EnFieldIntention::findOne(['status' => [10, 1], 'id' => $id])) {
            $model->status = 11;
            $model->save(false);
            Msg::set('删除成功');
        }
        return $this->redirect(['list']);
    }

    /**
     * 意向订单详情
     * @param string $id
     * @return string|\yii\web\Response
     */
    public function actionInfo($id)
    {
        if ($model = EnFieldIntention::findOne(['status' => 1, 'id' => $id])) {
            if ($detail = EnField::findOne(['status' => 4, 'no' => $model->field->no])) {
                if (\Yii::$app->request->isPost) {
                    $post = \Yii::$app->request->post();
                    $model->purchase_amount = $post['purchase_amount'];
                    $model->order_amount = $model->purchase_amount * Constant::orderRatio();
                    $model->part_ratio = $model->purchase_amount / $detail->budget_amount;
                    if ($model->save()) {
                        return $this->redirect(['field/intention/pay', 'id' => $model->id]);
                    }
                    Msg::set($model->errors());
                }
                return $this->render('info.html', [
                    'detail' => $detail->attributes,
                    'model' => $model->attributes,
                    'images' => explode(',', $detail->images)[0],
                    'business_type' => Constant::businessType()[$detail->business_type],
                    'invest_type' => Constant::investType()[$detail->invest_type],
                    '_csrf' => \Yii::$app->request->csrfToken,
                    'ratio'=>Constant::orderRatio()
                ]);
            }
        }
        Msg::set('错误操作');
        return $this->redirect(['list']);
    }

    /**
     * 推荐意向
     * @return string
     */
    public function actionRList()
    {
        $this->rUCenterUrl();
        return $this->render('r-list.html', ['data' => EnFieldIntention::getUserRData()]);
    }
}