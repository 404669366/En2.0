<?php
/**
 * Created by PhpStorm.
 * User: miku
 * Date: 2019/6/3
 * Time: 11:52
 */

namespace app\controllers\job;


use app\controllers\basis\CommonController;
use vendor\project\base\EnCompany;
use vendor\project\helpers\Msg;
use vendor\project\base\EnJob;
use vendor\project\base\EnMember;

class MemberController extends CommonController
{
    /**
     * 员工列表页渲染
     * @return string
     */
    public function actionList()
    {
        return $this->render('list', [
            'company' => EnCompany::getCompany()
        ]);
    }

    /**
     * 员工列表页数据
     * @return string
     */
    public function actionData()
    {
        return $this->rTableData(EnMember::getPageData());
    }

    /**
     * 编辑员工
     * @param int $id
     * @return string|\yii\web\Response
     */
    public function actionEdit($id = 0)
    {
        $model = EnMember::findOne($id);
        if (!$model) {
            $model = new EnMember();
        }
        if (\Yii::$app->request->isPost) {
            $post = \Yii::$app->request->post();
            $model->load(['EnMember' => $post]);
            if ($post['passwordA']) {
                Msg::set('密码最少6位');
                if (strlen($post['passwordA']) >= 6) {
                    $model->password = \Yii::$app->security->generatePasswordHash($post['passwordA']);
                    if ($model->validate() && $model->save()) {
                        Msg::set('保存成功');
                        return $this->redirect(['list']);
                    }
                    Msg::set($model->errors());
                }
            } else {
                if ($model->load(['EnMember' => $post]) && $model->validate() && $model->save()) {
                    Msg::set('保存成功');
                    return $this->redirect(['list']);
                }
                Msg::set($model->errors());
            }
        }
        return $this->render('edit', [
            'model' => $model,
            'company' => EnCompany::getCompany(),
            'job' => EnJob::getJob($model->company_id ?: 0)
        ]);
    }

    /**
     * 查询公司职位
     * @param int $company_id
     * @return string
     */
    public function actionGetJobs($company_id = 0)
    {
        return $this->rJson(EnJob::getJob($company_id));
    }

    /**
     * 公司员工列表页渲染
     * @return string
     */
    public function actionMyList()
    {
        return $this->render('my-list');
    }

    /**
     * 公司员工列表页数据
     * @return string
     */
    public function actionMyData()
    {
        return $this->rTableData(EnMember::getMyPageData());
    }

    /**
     * 编辑公司员工
     * @param int $id
     * @return string|\yii\web\Response
     */
    public function actionMyEdit($id = 0)
    {
        $model = EnMember::findOne($id);
        if (!$model) {
            $model = new EnMember();
        }
        if (\Yii::$app->request->isPost) {
            $post = \Yii::$app->request->post();
            $model->load(['EnMember' => $post]);
            if ($post['passwordA']) {
                Msg::set('密码最少6位');
                if (strlen($post['passwordA']) >= 6) {
                    $model->password = \Yii::$app->security->generatePasswordHash($post['passwordA']);
                    if ($model->validate() && $model->save()) {
                        Msg::set('保存成功');
                        return $this->redirect(['my-list']);
                    }
                    Msg::set($model->errors());
                }
            } else {
                if ($model->load(['EnMember' => $post]) && $model->validate() && $model->save()) {
                    Msg::set('保存成功');
                    return $this->redirect(['my-list']);
                }
                Msg::set($model->errors());
            }
        }
        return $this->render('my-edit', [
            'model' => $model,
            'job' => EnJob::getJob(\Yii::$app->user->identity->company_id)
        ]);
    }
}