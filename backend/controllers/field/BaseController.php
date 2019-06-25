<?php
/**
 * Created by PhpStorm.
 * User: miku
 * Date: 2019/6/14
 * Time: 10:42
 */

namespace app\controllers\field;


use app\controllers\basis\CommonController;
use vendor\project\base\EnField;
use vendor\project\base\EnFieldBase;
use vendor\project\helpers\Msg;

class BaseController extends CommonController
{
    /**
     * 列表页
     * @return string
     */
    public function actionList()
    {
        return $this->render('list', ['count' => count(EnFieldBase::getNeed())]);
    }

    /**
     * 列表数据
     * @return string
     */
    public function actionData()
    {
        return $this->rTableData(EnFieldBase::getPageData());
    }

    /**
     * 详情
     * @param $id
     * @return string
     */
    public function actionInfo($id)
    {
        return $this->render('info', ['model' => EnFieldBase::findOne($id)]);
    }

    /**
     * 转化
     * @param $id
     * @return string|\yii\web\Response
     */
    public function actionChange($id)
    {
        $model = EnFieldBase::findOne(['status' => 1, 'id' => $id]);
        if (\Yii::$app->request->isPost) {
            $field = new EnField();
            $post = \Yii::$app->request->post();
            Msg::set('场地介绍不能为空');
            if ($post['intro']) {
                if ($field->load(['EnField' => $post]) && $field->validate() && $field->save()) {
                    $model->status = 2;
                    $model->field_id = $model->id;
                    $model->save();
                    \Yii::$app->cache->set('FieldIntro-' . $field->id, $post['intro']);
                    Msg::set('操作成功');
                    return $this->redirect(['list']);
                }
                Msg::set($field->errors());
            }
        }
        return $this->render('change', ['model' => $model]);
    }

    /**
     * 放弃
     * @param $id
     * @return \yii\web\Response
     */
    public function actionRenounce($id)
    {
        Msg::set(EnFieldBase::renounce($id));
        return $this->redirect(['list']);
    }

    /**
     * 抢单
     * @return \yii\web\Response
     */
    public function actionRob()
    {
        Msg::set(EnFieldBase::rob());
        return $this->redirect(['list']);
    }
}