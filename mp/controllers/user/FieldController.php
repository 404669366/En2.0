<?php
/**
 * Created by PhpStorm.
 * User: miku
 * Date: 2019/9/24
 * Time: 18:13
 */

namespace app\controllers\user;


use app\controllers\basis\AuthController;
use vendor\project\base\EnField;
use vendor\project\helpers\Helper;
use vendor\project\helpers\Msg;
use vendor\project\helpers\Wechat;

class FieldController extends AuthController
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
                return $this->redirect(['list']);
            }
            Msg::set($model->errors());
        }
        return $this->render('create.html', [
            'post' => $post,
            '_csrf' => \Yii::$app->request->csrfToken,
            'jsApi' => Wechat::getJsApiParams()
        ]);
    }

    /**
     * 我的场站
     * @return string
     */
    public function actionList()
    {
        return $this->render('list.html', ['data' => EnField::listDataByCenter()]);
    }
}