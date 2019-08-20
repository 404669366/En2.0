<?php
/**
 * Created by PhpStorm.
 * User: miku
 * Date: 2019/7/23
 * Time: 11:52
 */

namespace app\controllers\user;


use app\controllers\basis\AuthController;
use vendor\project\base\EnField;
use vendor\project\helpers\Helper;
use vendor\project\helpers\Msg;

class FieldController extends AuthController
{
    /**
     * 发布项目
     * @return string|\yii\web\Response
     */
    public function actionCreate()
    {
        $post = ['address' => '', 'lng' => '', 'lat' => '', 'remark' => '', 'cobberTel' => '', 'images' => ''];
        if (\Yii::$app->request->isPost) {
            $post = \Yii::$app->request->post();
            $model = new EnField();
            $model->no = Helper::createNo('F');
            $model->local_id = \Yii::$app->user->id;
            $model->status = 0;
            $model->source = 1;
            $model->created = \Yii::$app->user->id;
            $model->created_at = time();
            if ($model->load(['EnField' => $post]) && $model->validate() && $model->save()) {
                Msg::set('项目发布成功');
                return $this->redirect(['create']);
            }
            Msg::set($model->errors());
        }
        return $this->render('create.html', ['post' => $post, '_csrf' => \Yii::$app->request->csrfToken]);
    }

    /**
     * 我的场站
     * @return string
     */
    public function actionList()
    {
        $this->rUCenterUrl();
        return $this->render('list.html', ['data' => EnField::getUserField()]);
    }

    /**
     * 场站删除
     * @param $id
     * @return string
     */
    public function actionDel($id)
    {
        Msg::set('错误操作');
        if ($model = EnField::findOne(['id' => $id, 'status' => 0])) {
            $model->status = 6;
            if ($model->save()) {
                Msg::set('删除成功');
            } else {
                Msg::set($model->errors());
            }
        }
        return $this->redirect(['list']);
    }

    /**
     * 推荐场站
     * @return string
     */
    public function actionRList()
    {
        $this->rUCenterUrl();
        return $this->render('r-list.html', ['data' => EnField::getUserRField()]);
    }
}