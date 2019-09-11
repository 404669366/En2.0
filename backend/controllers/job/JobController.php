<?php
/**
 * Created by PhpStorm.
 * User: miku
 * Date: 2019/5/30
 * Time: 16:14
 */

namespace app\controllers\job;


use app\controllers\basis\CommonController;
use vendor\project\base\EnCompany;
use vendor\project\base\EnJob;
use vendor\project\base\EnPower;
use vendor\project\helpers\Msg;

class JobController extends CommonController
{
    /**
     * 职位列表页渲染
     * @return string
     */
    public function actionList()
    {
        return $this->render('list', ['company' => EnCompany::getCompany()]);
    }

    /**
     * 职位列表页数据
     * @return string
     */
    public function actionData()
    {
        return $this->rTableData(EnJob::getPageData());
    }

    /**
     * 职位编辑
     * @param int $id
     * @return string|\yii\web\Response
     */
    public function actionEdit($id = 0)
    {
        $model = EnJob::findOne($id);
        if (!$model) {
            $model = new EnJob();
        }
        if (\Yii::$app->request->isPost) {
            $post = \Yii::$app->request->post();
            if ($model->load(['EnJob' => $post]) && $model->validate() && $model->save()) {
                Msg::set('保存成功');
                return $this->redirect(['list']);
            }
            Msg::set($model->errors());
        }
        return $this->render('edit', [
            'model' => $model,
            'company' => EnCompany::getCompany(),
            'powers' => json_encode(EnPower::getPowersDataByCompany($model->company_id))
        ]);
    }

    /**
     * 查询公司权限
     * @param int $company_id
     * @return string
     */
    public function actionGetPowers($company_id = 0)
    {
        return $this->rJson(EnPower::getPowersDataByCompany($company_id));
    }

    /**
     * 公司职位管理页
     * @return string
     */
    public function actionMyList()
    {
        return $this->render('my-list');
    }

    /**
     * 公司职位管理页数据
     * @return string
     */
    public function actionMyData()
    {
        return $this->rTableData(EnJob::getMyPageData());
    }

    /**
     * 公司职位编辑
     * @param int $id
     * @return string|\yii\web\Response
     */
    public function actionMyEdit($id = 0)
    {
        $model = EnJob::findOne($id);
        if (!$model) {
            $model = new EnJob();
            $model->company_id = \Yii::$app->user->identity->company_id;
        }
        if (\Yii::$app->request->isPost) {
            $post = \Yii::$app->request->post();
            if ($model->load(['EnJob' => $post]) && $model->validate() && $model->save()) {
                Msg::set('保存成功');
                return $this->redirect(['my-list']);
            }
            Msg::set($model->errors());
        }
        return $this->render('my-edit', [
            'model' => $model,
            'powers' => json_encode(EnPower::getPowersDataByCompany($model->company_id))
        ]);
    }
}