<?php
/**
 * Created by PhpStorm.
 * User: miku
 * Date: 2019/6/17
 * Time: 18:00
 */

namespace app\controllers\field;


use app\controllers\basis\CommonController;
use vendor\project\base\EnField;
use vendor\project\base\EnIntention;
use vendor\project\base\EnMember;
use vendor\project\helpers\Helper;
use vendor\project\helpers\Msg;

class IntentionController extends CommonController
{
    /**
     * 列表页
     * @return string
     */
    public function actionList()
    {
        return $this->render('list', ['count' => EnIntention::getRobCount()]);
    }

    /**
     * 列表数据
     * @return string
     */
    public function actionData()
    {
        return $this->rTableData(EnIntention::getPageData(\Yii::$app->user->id));
    }

    /**
     * 意向抢单
     * @return \yii\web\Response
     */
    public function actionGet()
    {
        EnIntention::robIntention();
        return $this->redirect(['list']);
    }

    /**
     * 修改意向
     * @param int $id
     * @return string|\yii\web\Response
     */
    public function actionEdit($id = 0)
    {
        $model = EnIntention::findOne($id);
        if (!$model) {
            $model = new EnIntention();
            $model->source = 2;
            $model->commissioner_id = \Yii::$app->user->id;
            $model->no = Helper::createNo('I');
            $model->status = 1;
            $model->created_at = time();
        }
        if (\Yii::$app->request->isPost) {
            $post = \Yii::$app->request->post();
            if ($model->load(['EnIntention' => $post]) && $model->validate() && $model->save()) {
                Msg::set('操作成功');
                return $this->redirect(['list']);
            }
            Msg::set($model->errors());
        }
        return $this->render('edit', [
            'model' => $model,
            'fields' => EnField::getCompanyField(\Yii::$app->user->identity->company_id, [4, 5])
        ]);
    }

    /**
     * 详情页
     * @param $id
     * @return string
     */
    public function actionInfo($id)
    {
        return $this->render('info', ['model' => EnIntention::findOne($id)]);
    }

    /**
     * 用户违约
     * @param $id
     * @return \yii\web\Response
     */
    public function actionBreak($id)
    {
        Msg::set('错误操作');
        if ($model = EnIntention::findOne(['status' => [2, 3, 4], 'id' => $id])) {
            $model->status = 7;
            $model->save(false);
            Msg::set('操作成功');
        }
        return $this->redirect(['list']);
    }

    /**
     * 指派专员
     * @param $id
     * @param $tel
     * @return string
     */
    public function actionAppoint($id, $tel)
    {
        if ($model = EnIntention::findOne(['id' => $id, 'commissioner_id' => \Yii::$app->user->id])) {
            if ($commissioner = EnMember::findOne(['tel' => $tel, 'company_id' => \Yii::$app->user->identity->company_id])) {
                if ($commissioner->id != \Yii::$app->user->id) {
                    $model->commissioner_id = $commissioner->id;
                    $model->save(false);
                    return $this->rJson();
                }
                return $this->rJson('', false, '错误操作');
            }
            return $this->rJson('', false, '公司不存在该账户,请核对手机号');
        }
        return $this->rJson('', false, '错误操作');
    }
}