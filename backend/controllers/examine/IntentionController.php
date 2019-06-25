<?php
/**
 * Created by PhpStorm.
 * User: miku
 * Date: 2019/6/18
 * Time: 12:22
 */

namespace app\controllers\examine;


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
        return $this->rTableData(EnFieldIntention::getExamineData());
    }

    /**
     * 详情页
     * @param $id
     * @return string
     */
    public function actionInfo($id)
    {
        $model = EnFieldIntention::findOne($id);
        if (\Yii::$app->request->isPost) {
            $post = \Yii::$app->request->post();
            $model->remark = $post['remark'];
            $model->status = $post['status'];
            $re = true;
            if ($model->source == 2 && $model->status == 4) {
                $re = EnField::updatePresentAmount($model->field_id, 'add', $model->purchase_amount);
            }
            if ($re) {
                if ($re && $model->save()) {
                    Msg::set('操作成功');
                    return $this->redirect(['list']);
                }
                Msg::set($model->errors());
            }
        }
        return $this->render('info', ['model' => $model]);
    }
}