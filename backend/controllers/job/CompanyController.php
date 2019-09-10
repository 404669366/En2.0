<?php
/**
 * Created by PhpStorm.
 * User: miku
 * Date: 2019/7/17
 * Time: 10:36
 */

namespace app\controllers\job;


use app\controllers\basis\CommonController;
use vendor\project\base\EnCompany;
use vendor\project\base\EnMember;
use vendor\project\base\EnPower;
use vendor\project\helpers\Msg;

class CompanyController extends CommonController
{
    /**
     * 公司列表页渲染
     * @return string
     */
    public function actionList()
    {
        return $this->render('list');
    }

    /**
     * 公司列表页数据
     * @return string
     */
    public function actionData()
    {
        return $this->rTableData(EnCompany::getPageData());
    }

    /**
     * 公司编辑
     * @param int $id
     * @return string|\yii\web\Response
     */
    public function actionEdit($id = 0)
    {
        $model = EnCompany::findOne($id);
        if (!$model) {
            $model = new EnCompany();
        } else {
            $model->intro = \Yii::$app->cache->get('CompanyIntro_' . $model->id);
        }
        if (\Yii::$app->request->isPost) {
            $post = \Yii::$app->request->post();
            if ($model->load(['EnCompany' => $post]) && $model->validate() && $model->save()) {
                Msg::set('保存成功');
                return $this->redirect(['list']);
            }
            Msg::set($model->errors());
        }
        return $this->render('edit', [
            'model' => $model,
            'powers' => json_encode(EnPower::getTreeData())
        ]);
    }

    /**
     * 我的公司
     * @return string
     */
    public function actionMy()
    {
        if ($company_id = EnMember::getCompanyId()) {
            $model = EnCompany::findOne($company_id);
            $model->intro = \Yii::$app->cache->get('CompanyIntro_' . $model->id);
            if (\Yii::$app->request->isPost) {
                $post = \Yii::$app->request->post();
                if ($model->load(['EnCompany' => $post]) && $model->validate() && $model->save()) {
                    Msg::set('保存成功');
                } else {
                    Msg::set($model->errors());
                }
            }
            return $this->render('my', [
                'model' => $model,
            ]);
        }
        Msg::set('非法操作');
        return $this->render('/index/index/first');
    }
}