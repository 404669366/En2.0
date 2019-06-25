<?php
/**
 * Created by PhpStorm.
 * User: miku
 * Date: 2019/6/17
 * Time: 18:00
 */

namespace app\controllers\field;


use app\controllers\basis\CommonController;
use vendor\project\base\EnField;
use vendor\project\base\EnFieldIntention;
use vendor\project\helpers\Msg;

class IntentionController extends CommonController
{
    /**
     * 列表页
     * @return string
     */
    public function actionList()
    {
        return $this->render('list');
    }

    /**
     * 列表数据
     * @return string
     */
    public function actionData()
    {
        return $this->rTableData(EnFieldIntention::getPageData());
    }

    /**
     * 添加意向
     * @return string|\yii\web\Response
     */
    public function actionAdd()
    {
        if (\Yii::$app->request->isPost) {
            $post = \Yii::$app->request->post();
            $model = new EnFieldIntention();
            if ($model->load(['EnFieldIntention' => $post]) && $model->validate() && $model->save()) {
                Msg::set('操作成功');
                return $this->redirect(['list']);
            }
            Msg::set($model->errors());
        }
        return $this->render('add');
    }

    /**
     * 修改意向
     * @param $id
     * @return string|\yii\web\Response
     */
    public function actionEdit($id)
    {
        $model = EnFieldIntention::findOne($id);
        if (\Yii::$app->request->isPost) {
            $post = \Yii::$app->request->post();
            if ($model->load(['EnFieldIntention' => $post]) && $model->validate() && $model->save()) {
                Msg::set('操作成功');
                return $this->redirect(['list']);
            }
            Msg::set($model->errors());
        }
        return $this->render('edit', ['model' => $model]);
    }

    /**
     * 详情页
     * @param $id
     * @return string
     */
    public function actionInfo($id)
    {
        return $this->render('info', ['model' => EnFieldIntention::findOne($id)]);
    }

    /**
     * 用户违约
     * @param $id
     * @return \yii\web\Response
     */
    public function actionBreak($id)
    {
        Msg::set('错误操作');
        if ($model = EnFieldIntention::findOne(['status' => [2, 5], 'source' => 1, 'id' => $id])) {
            $model->status = 6;
            if (EnField::updatePresentAmount($model->field_id, 'cut', $model->purchase_amount)) {
                Msg::set('操作成功');
                $model->save();
            }
        }
        return $this->redirect(['list']);
    }

    /**
     * 场站搜索
     * @param $no
     * @return string
     */
    public function actionFieldSearch($no)
    {
        if ($re = EnFieldIntention::fieldSearch($no)) {
            return $this->rJson($re);
        }
        return $this->rJson([], false);
    }
}