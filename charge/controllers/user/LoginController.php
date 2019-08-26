<?php
/**
 * Created by PhpStorm.
 * User: miku
 * Date: 2019/6/21
 * Time: 16:52
 */

namespace app\controllers\user;


use app\controllers\basis\BasisController;
use vendor\project\base\EnUser;
use vendor\project\helpers\Msg;
use vendor\project\helpers\Sms;
use vendor\project\helpers\Url;

class LoginController extends BasisController
{
    /**
     * 游客登录
     * @return string|\yii\web\Response
     */
    public function actionLogin()
    {
        $post = ['tel' => '', 'code' => ''];
        if (\Yii::$app->request->isPost) {
            $post = \Yii::$app->request->post();
            Msg::set('验证码错误');
            if (Sms::validateCode($post['tel'], $post['code'])) {
                $model = EnUser::findOne(['tel' => $post['tel']]);
                Msg::set('登录成功');
                if (!$model) {
                    $model = new EnUser();
                    $model->tel = $post['tel'];
                    $model->token = \Yii::$app->security->generatePasswordHash($post['tel']);
                    $model->created_at = time();
                    Msg::set('注册成功');
                }
                $model->open_id = \Yii::$app->session->get('open_id', '');
                if ($model->save()) {
                    \Yii::$app->user->login($model);
                    return $this->redirect(Url::getUrl());
                }
                Msg::set($model->errors());
            }
        }
        return $this->render('login.html', [
            'post' => $post,
            '_csrf' => \Yii::$app->request->csrfToken,
        ]);
    }

    /**
     * 用户退出
     * @return \yii\web\Response
     */
    public function actionLogout()
    {
        $user = EnUser::findOne(\Yii::$app->user->id);
        $user->open_id = '';
        if ($user->save()) {
            \Yii::$app->user->logout();
            Msg::set('注销登录成功');
            return $this->redirect([\Yii::$app->params['defaultRoute']]);
        }
        Msg::set('系统错误');
        return $this->goBack();
    }
}