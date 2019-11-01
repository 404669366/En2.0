<?php
/**
 * Created by PhpStorm.
 * User: miku
 * Date: 2019/6/17
 * Time: 18:00
 */

namespace app\controllers\examine;


use app\controllers\basis\CommonController;
use vendor\project\base\EnIntention;
use vendor\project\helpers\Constant;
use vendor\project\helpers\Msg;

class IntentionController extends CommonController
{
    /**
     * 意向列表页
     * @return string
     */
    public function actionList()
    {
        return $this->render('list', [
            'status' => Constant::intentionStatus(),
            'fStatus' => Constant::fieldStatus(),
        ]);
    }

    /**
     * 意向列表数据
     * @return string
     */
    public function actionData()
    {
        return $this->rTableData(EnIntention::getPageData(false));
    }

    /**
     * 意向审核页
     * @param $no
     * @return string
     */
    public function actionInfo($no)
    {
        $model = EnIntention::findOne(['no' => $no]);
        if (\Yii::$app->request->isPost) {
            $post = \Yii::$app->request->post();
            $model->remark = $post['remark'];
            $model->status = $post['status'];
            if ($model->save(false)) {
                Msg::set('操作成功');
                return $this->redirect(['list']);
            }
            Msg::set($model->errors());
        }
        return $this->render('info', [
            'model' => $model,
            'status' => Constant::intentionStatus(),
        ]);
    }
}