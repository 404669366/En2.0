<?php
/**
 * Created by PhpStorm.
 * User: miku
 * Date: 2019/6/18
 * Time: 12:22
 */

namespace app\controllers\examine;


use app\controllers\basis\CommonController;
use vendor\project\base\EnIntention;
use vendor\project\helpers\Msg;

class IntentionController extends CommonController
{
    /**
     * 列表页
     * @return string
     */
    public function actionContractList()
    {
        return $this->render('contract');
    }

    /**
     * 列表数据
     * @return string
     */
    public function actionContractData()
    {
        return $this->rTableData(EnIntention::getPageData(0, 3));
    }

    /**
     * 详情页
     * @param $id
     * @return string
     */
    public function actionContractInfo($id)
    {
        $model = EnIntention::findOne($id);
        if (\Yii::$app->request->isPost) {
            $post = \Yii::$app->request->post();
            $model->remark = $post['remark'];
            $model->status = $post['status'];
            $model->save(false);
            Msg::set('操作成功');
            return $this->redirect(['contract-list']);
        }
        return $this->render('contract-info', ['model' => $model]);
    }

    /**
     * 列表页
     * @return string
     */
    public function actionList()
    {
        return $this->render('list');
    }

    /**
     * 列表数据
     * @return string
     */
    public function actionData()
    {
        return $this->rTableData(EnIntention::getPageData(0, [5, 6]));
    }

    /**
     * 详情页
     * @param $id
     * @return string
     */
    public function actionInfo($id)
    {
        $model = EnIntention::findOne($id);
        if (\Yii::$app->request->isPost) {
            $post = \Yii::$app->request->post();
            $model->remark = $post['remark'];
            $model->status = $post['status'];
            $model->save(false);
            Msg::set('操作成功');
            return $this->redirect(['list']);
        }
        return $this->render('info', ['model' => $model]);
    }
}