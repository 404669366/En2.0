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
use vendor\project\helpers\Msg;

class FieldController extends CommonController
{
    /**
     * 场地列表页
     * @return string
     */
    public function actionList()
    {
        return $this->render('list');
    }

    /**
     * 场地列表数据页
     * @return string
     */
    public function actionData()
    {
        return $this->rTableData(EnField::getPageData(0, [2, 3]));
    }

    /**
     * 详情页
     * @param $id
     * @return string
     */
    public function actionInfo($id)
    {
        $model = EnField::findOne($id);
        if (\Yii::$app->request->isPost) {
            $post = \Yii::$app->request->post();
            $model->remark = $post['remark'];
            $model->status = $post['status'];
            if ($model->save()) {
                Msg::set('操作成功');
                return $this->redirect(['list']);
            }
            Msg::set($model->errors());
        }
        return $this->render('info', ['model' => $model]);
    }
}