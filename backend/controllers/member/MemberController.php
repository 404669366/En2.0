<?php
/**
 * Created by PhpStorm.
 * User: miku
 * Date: 2019/6/3
 * Time: 11:52
 */

namespace app\controllers\member;


use app\controllers\basis\CommonController;
use vendor\project\helpers\Msg;
use vendor\project\base\EnJob;
use vendor\project\base\EnMember;

class MemberController extends CommonController
{
    /**
     * 修改密码
     * @return string
     */
    public function actionUpdate()
    {
        $model = EnMember::findOne(\Yii::$app->user->id);
        if (\Yii::$app->request->isPost) {
            $post = \Yii::$app->request->post();
            Msg::set('非法操作');
            if ($model) {
                Msg::set('请输入旧密码');
                if ($post['password']) {
                    Msg::set('旧密码输入错误');
                    if (\Yii::$app->security->validatePassword($post['password'], $model->password)) {
                        Msg::set('新密码格式错误');
                        if ($post['newPasswordA'] && strlen($post['newPasswordA']) >= 6) {
                            Msg::set('新密码输入不一致');
                            if ($post['newPasswordA'] == $post['newPasswordB']) {
                                $model->password = \Yii::$app->security->generatePasswordHash($post['newPasswordA']);
                                if ($model->save()) {
                                    Msg::set('密码修改成功');
                                } else {
                                    Msg::set($model->errors());
                                }
                            }
                        }
                    }
                }
            }
        }
        return $this->render('update');
    }
}