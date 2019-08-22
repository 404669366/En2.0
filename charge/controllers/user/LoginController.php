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
use vendor\project\helpers\Wechat;

class LoginController extends BasisController
{
    /**
     * 游客登录
     * @return string|\yii\web\Response
     */
    public function actionLogin()
    {
        $tel = '';
        $code = '';
        if (\Yii::$app->request->isPost) {
            $post = \Yii::$app->request->post();
            $tel = $post['tel'];
            $code = $post['code'];
            Msg::set('验证码错误');
            if (Sms::validateCode($post['tel'], $post['code'])) {
                if ($model = EnUser::findOne(['tel' => $post['tel']])) {
                    $model->open_id = \Yii::$app->session->get('open_id', '');
                    $model->save();
                    \Yii::$app->user->login($model);
                    Msg::set('登录成功');
                    return $this->redirect(Url::getUrl());
                } else {
                    $model = new EnUser();
                    $model->tel = $post['tel'];
                    $model->token = \Yii::$app->security->generatePasswordHash($post['tel']);
                    $model->open_id = \Yii::$app->session->get('open_id', '');
                    $model->created_at = time();
                    if ($model->save()) {
                        Msg::set('注册成功');
                        \Yii::$app->user->login($model);
                        return $this->redirect(Url::getUrl());
                    }
                    Msg::set($model->errors());
                }
            }
        }
        return $this->render('login.html', [
            'tel' => $tel,
            'code' => $code,
            '_csrf' => \Yii::$app->request->csrfToken,
        ]);
    }

    /**
     * 微信登录回调
     * @param string $code
     * @return \yii\web\Response
     */
    public function actionLoginW($code = '')
    {
        if ($code) {
            if ($info = Wechat::getUserAuthorizeAccessToken($code)) {
                if ($model = EnUser::findOne(['open_id' => $info['openid']])) {
                    \Yii::$app->user->login($model);
                    Msg::set('登录成功');
                    return $this->redirect(Url::getUrl());
                }
                \Yii::$app->session->set('open_id', $info['openid']);
            }
        }
        return $this->redirect(['login']);
    }

    /**
     * 用户退出
     * @return \yii\web\Response
     */
    public function actionLogout()
    {
        \Yii::$app->user->logout();
        Msg::set('退出成功');
        return $this->redirect([\Yii::$app->params['defaultRoute']]);
    }
}