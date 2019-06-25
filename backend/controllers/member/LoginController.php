<?php
/**
 * Created by PhpStorm.
 * User: miku
 * Date: 2019/5/29
 * Time: 14:33
 */

namespace app\controllers\member;


use app\controllers\basis\BasisController;
use vendor\project\base\EnMember;
use vendor\project\helpers\CaptchaCode;
use vendor\project\helpers\Msg;

class LoginController extends BasisController
{
    /**
     * 用户登录
     * @return string|\yii\web\Response
     */
    public function actionLogin()
    {
        Msg::setSize();
        $this->layout = false;
        if (\Yii::$app->request->isPost) {
            $data = \Yii::$app->request->post();
            Msg::set('验证码有误');
            if (CaptchaCode::validate($data['code'], 'Login')) {
                if (EnMember::accountLogin($data['username'], $data['pwd'])) {
                    Msg::set('登录成功');
                    return $this->redirect([\Yii::$app->params['defaultRoute']]);
                }
                Msg::set('账号或密码有误');
            }
        }
        return $this->render('login');
    }

    /**
     * 图形验证码
     */
    public function actionCode()
    {
        $model = new CaptchaCode();
        $model->doimg('Login');
    }

    /**
     * 用户退出
     * @return \yii\web\Response
     */
    public function actionLogout()
    {
        Msg::set('注销成功');
        \Yii::$app->user->logout();
        return $this->redirect(['login']);
    }
}