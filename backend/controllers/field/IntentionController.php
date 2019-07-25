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
use vendor\project\helpers\Helper;
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
     * 修改意向
     * @param int $id
     * @return string|\yii\web\Response
     */
    public function actionEdit($id = 0)
    {
        $model = EnFieldIntention::findOne($id);
        if (!$model) {
            $model = new EnFieldIntention();
            $model->status = 3;
            $model->source = 2;
            $model->cobber_id = 0;
            $model->created_at = time();
            $model->commissioner_id = \Yii::$app->user->id;
            $model->no = Helper::createNo('I');
        }
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
            $model->save(false);
            Msg::set('操作成功');
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