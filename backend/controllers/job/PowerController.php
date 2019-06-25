<?php
/**
 * Created by PhpStorm.
 * User: miku
 * Date: 2019/5/30
 * Time: 15:39
 */

namespace app\controllers\job;


use app\controllers\basis\CommonController;
use vendor\project\base\EnPower;
use vendor\project\helpers\Constant;
use vendor\project\helpers\Msg;

class PowerController extends CommonController
{
    /**
     * 权限列表页渲染
     * @return string
     */
    public function actionList()
    {
        return $this->render('list', [
            'types' => Constant::powerType()
        ]);
    }

    /**
     * 权限列表页数据
     * @return string
     */
    public function actionData()
    {
        return $this->rTableData(EnPower::getPageData());
    }

    /**
     * 新增权限
     * @return string|\yii\web\Response
     */
    public function actionAdd()
    {
        $model = new EnPower();
        if (\Yii::$app->request->isPost) {
            $post = \Yii::$app->request->post();
            if ($model->load(['EnPower' => $post]) && $model->validate() && $model->save()) {
                Msg::set('保存成功');
                return $this->redirect(['list']);
            }
            Msg::set($model->errors());
        }
        return $this->render('add', [
            'types' => Constant::powerType(),
            'tops' => $model::getTopPowers(),
        ]);
    }

    /**
     * 修改权限
     * @param $id
     * @return string|\yii\web\Response
     */
    public function actionEdit($id)
    {
        $model = EnPower::findOne($id);
        if (\Yii::$app->request->isPost) {
            $post = \Yii::$app->request->post();
            if ($model->load(['EnPower' => $post]) && $model->validate() && $model->save()) {
                Msg::set('保存成功');
                return $this->redirect(['list']);
            }
            Msg::set($model->errors());
        }
        return $this->render('edit', [
            'model' => $model,
            'types' => Constant::powerType(),
            'tops' => $model::getTopPowers($id)
        ]);
    }

    /**
     * 删除权限
     * @param $id
     * @return \yii\web\Response
     */
    public function actionDel($id)
    {
        $model = EnPower::findOne($id);
        Msg::set('删除失败');
        if ($model) {
            $model->delete();
            Msg::set('删除成功');
        }
        return $this->redirect(['list']);
    }
}