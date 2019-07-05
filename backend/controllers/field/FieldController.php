<?php
/**
 * Created by PhpStorm.
 * User: miku
 * Date: 2019/6/12
 * Time: 13:58
 */

namespace app\controllers\field;


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
        return $this->rTableData(EnField::getPageData());
    }

    /**
     * 添加场地
     * @return string|\yii\web\Response
     */
    public function actionAdd()
    {
        $model = new EnField();
        $intro = '';
        if (\Yii::$app->request->isPost) {
            $post = \Yii::$app->request->post();
            $model->load(['EnField' => $post]);
            $intro = $post['intro'];
            Msg::set('场地介绍不能为空');
            if ($intro) {
                if ($model->validate() && $model->save()) {
                    \Yii::$app->cache->set('FieldIntro-' . $model->id, $intro);
                    Msg::set('操作成功');
                    return $this->redirect(['list']);
                }
                Msg::set($model->errors());
            }
        }
        return $this->render('add', ['model' => $model, 'intro' => $intro]);
    }

    /**
     * 修改场地
     * @param $id
     * @return string|\yii\web\Response
     */
    public function actionEdit($id)
    {
        $model = EnField::findOne($id);
        $intro = \Yii::$app->cache->get('FieldIntro-' . $model->id);
        if (\Yii::$app->request->isPost) {
            $post = \Yii::$app->request->post();
            $model->load(['EnField' => $post]);
            $intro = $post['intro'];
            if ($intro) {
                if ($model->validate() && $model->save()) {
                    \Yii::$app->cache->set('FieldIntro-' . $model->id, $intro);
                    Msg::set('操作成功');
                    return $this->redirect(['list']);
                }
                Msg::set($model->errors());
            }
        }
        return $this->render('edit', ['model' => $model, 'intro' => $intro]);
    }

    /**
     * 查看详情
     * @param $id
     * @return string
     */
    public function actionInfo($id)
    {
        return $this->render('info', ['model' => EnField::findOne($id)]);
    }

    /**
     * 用户绑定搜索
     * @param $tel
     * @return string
     */
    public function actionUserSearch($tel)
    {
        if ($data = EnField::userSearch($tel)) {
            return $this->rJson($data);
        }
        return $this->rJson([], false);
    }
}