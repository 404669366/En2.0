<?php
/**
 * Created by PhpStorm.
 * User: miku
 * Date: 2019/8/19
 * Time: 9:56
 */

namespace app\controllers\pile;


use app\controllers\basis\CommonController;
use vendor\project\base\EnModel;
use vendor\project\helpers\Msg;

class ModelController extends CommonController
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
        return $this->rTableData(EnModel::getPageData());
    }

    /**
     * 编辑
     * @param $id
     * @return string
     */
    public function actionEdit($id = 0)
    {
        $model = EnModel::findOne($id);
        if (!$model) {
            $model = new EnModel();
        }
        if (\Yii::$app->request->isPost) {
            $model->load(['EnModel' => \Yii::$app->request->post()]);
            if ($model->validate() && $model->save()) {
                Msg::set('保存成功');
                return $this->redirect(['list']);
            }
            Msg::set($model->errors());
        }
        return $this->render('edit', ['model' => $model]);
    }
}