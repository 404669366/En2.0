<?php
/**
 * Created by PhpStorm.
 * User: miku
 * Date: 2019/7/8
 * Time: 10:43
 */

namespace app\controllers\field;


use app\controllers\basis\AuthController;
use vendor\project\base\EnFieldBase;
use vendor\project\helpers\Msg;

class BaseController extends AuthController
{
    /**
     * 发布项目
     * @return string|\yii\web\Response
     */
    public function actionCreate()
    {
        $post = ['address' => '', 'lng' => '', 'lat' => '', 'remark' => '', 'tel' => ''];
        if (\Yii::$app->request->isPost) {
            $post = \Yii::$app->request->post();
            $post['user_id'] = \Yii::$app->user->id;
            $post['created_at'] = time();
            $model = new EnFieldBase();
            $model->tel = $post['tel'];
            if ($model->load(['EnFieldBase' => $post]) && $model->validate() && $model->save()) {
                Msg::set('发布项目成功');
                return $this->redirect(['create']);
            }
            Msg::set($model->errors());
        }
        return $this->render('create', ['post' => $post]);
    }
}