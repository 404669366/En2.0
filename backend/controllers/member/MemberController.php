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
     * 后台用户列表页渲染
     * @return string
     */
    public function actionList()
    {
        return $this->render('list', [
            'jobs' => EnJob::getJobs(),
        ]);
    }

    /**
     * 后台用户列表页数据
     * @return string
     */
    public function actionData()
    {
        return $this->rTableData(EnMember::getPageData());
    }

    /**
     * 新增后台用户
     * @return string|\yii\web\Response
     */
    public function actionAdd()
    {
        $model = new EnMember();
        if (\Yii::$app->request->isPost) {
            $post = \Yii::$app->request->post();
            Msg::set('密码最少6位');
            if (strlen($post['passwordA']) >= 6) {
                $post['password'] = \Yii::$app->security->generatePasswordHash($post['passwordA']);
                $post['created_at'] = time();
                if ($model->load(['EnMember' => $post]) && $model->validate() && $model->save()) {
                    Msg::set('保存成功');
                    return $this->redirect(['list']);
                }
                Msg::set($model->errors());
            }
        }
        return $this->render('add', [
            'jobs' => EnJob::getJobTree(),
        ]);
    }

    /**
     * 修改后台用户
     * @param $id
     * @return string|\yii\web\Response
     */
    public function actionEdit($id)
    {
        $model = EnMember::findOne($id);
        if (\Yii::$app->request->isPost) {
            $post = \Yii::$app->request->post();
            if (isset($post['passwordA']) && strlen($post['passwordA']) >= 6) {
                $post['password'] = \Yii::$app->security->generatePasswordHash($post['passwordA']);
            }
            if ($model->load(['EnMember' => $post]) && $model->validate() && $model->save()) {
                Msg::set('保存成功');
                return $this->redirect(['list']);
            }
            Msg::set($model->errors());
        }
        return $this->render('edit', [
            'model' => $model,
            'jobs' => EnJob::getJobTree(),
        ]);
    }

    /**
     * 删除后台用户
     * @param $id
     * @return \yii\web\Response
     */
    public function actionDel($id)
    {
        $model = EnMember::findOne($id);
        Msg::set('删除失败');
        if ($model) {
            $model->delete();
            Msg::set('删除成功');
        }
        return $this->redirect(['list']);
    }

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