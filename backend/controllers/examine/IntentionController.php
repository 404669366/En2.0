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
            $model->save(false);
            Msg::set('操作成功');
            return $this->redirect(['list']);
        }
        return $this->render('info', ['model' => $model]);
    }

    /**
     * 退款列表页
     * @return string
     */
    public function actionBackList()
    {
        return $this->render('back-list');
    }

    /**
     * 退款列表数据
     * @return string
     */
    public function actionBackData()
    {
        return $this->rTableData(EnFieldIntention::getExamineBackData());
    }

    /**
     * 退款详情页
     * @param $id
     * @return string
     */
    public function actionBackInfo($id)
    {
        return $this->render('back-info', ['model' => EnFieldIntention::findOne($id)]);
    }

    /**
     * 退款确认
     * @param $id
     * @return string|\yii\web\Response
     */
    public function actionSure($id)
    {
        Msg::set('错误操作');
        if ($model = EnFieldIntention::findOne(['status' => 8, 'id' => $id])) {
            $model->status = 9;
            if ($model->save()) {
                Msg::set('操作成功');
            }
            Msg::set($model->errors());
        }
        return $this->redirect(['back-list']);
    }
}