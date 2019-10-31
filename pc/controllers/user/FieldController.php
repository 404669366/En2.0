<?php
/**
 * Created by PhpStorm.
 * User: miku
 * Date: 2019/9/24
 * Time: 18:13
 */

namespace app\controllers\user;


use vendor\project\base\EnField;
use vendor\project\helpers\Helper;
use vendor\project\helpers\Msg;

class FieldController extends UserController
{
    /**
     * 发布场站
     * @return string|\yii\web\Response
     */
    public function actionCreate()
    {
        $post = ['address' => '', 'lng' => '', 'lat' => '', 'remark' => '', 'images' => ''];
        if (\Yii::$app->request->isPost) {
            $post = \Yii::$app->request->post();
            $model = new EnField();
            $model->no = Helper::createNo('F');
            $model->user_id = \Yii::$app->user->id;
            $model->status = 0;
            $model->created_at = time();
            if ($model->load(['EnField' => $post]) && $model->validate() && $model->save()) {
                Msg::set('场站发布成功');
                return $this->redirect(['user/user/center']);
            }
            Msg::set($model->errors());
        }
        return $this->render('create.html', [
            'post' => $post,
            '_csrf' => \Yii::$app->request->csrfToken,
        ]);
    }
}