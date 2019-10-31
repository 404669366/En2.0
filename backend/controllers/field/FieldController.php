<?php
/**
 * Created by PhpStorm.
 * User: miku
 * Date: 2019/6/12
 * Time: 13:58
 */

namespace app\controllers\field;


use app\controllers\basis\CommonController;
use vendor\project\base\EnField;
use vendor\project\base\EnMember;
use vendor\project\base\EnStock;
use vendor\project\helpers\Constant;
use vendor\project\helpers\Helper;
use vendor\project\helpers\Msg;

class FieldController extends CommonController
{
    /**
     * 场地列表页
     * @return string
     */
    public function actionList()
    {
        return $this->render('list', [
            'status' => Constant::fieldStatus(),
            'online' => Constant::fieldOnline(),
            'count' => EnField::getRobCount()
        ]);
    }

    /**
     * 场地列表数据
     * @return string
     */
    public function actionData()
    {
        return $this->rTableData(EnField::getPageData());
    }

    /**
     * 编辑场地
     * @param string $no
     * @return string|\yii\web\Response
     */
    public function actionEdit($no = '')
    {
        $model = EnField::findOne(['no' => $no]);
        if (!$model) {
            $model = new EnField();
            $model->no = Helper::createNo('F');
            $model->company_id = \Yii::$app->user->identity->company_id;
            $model->commissioner_id = \Yii::$app->user->id;
            $model->created_at = time();
        }
        if (\Yii::$app->request->isPost) {
            if ($model->load(['EnField' => \Yii::$app->request->post()]) && $model->save()) {
                Msg::set('操作成功');
                return $this->redirect(['list']);
            }
            Msg::set($model->errors());
        }
        return $this->render('edit', [
            'model' => $model,
            'status' => Constant::fieldStatus(),
            'online' => Constant::fieldOnline(),
            'stock' => EnStock::getStockByFieldToArr($no),
            'stockType' => Constant::stockType()
        ]);
    }

    /**
     * 查看详情
     * @param $no
     * @return string
     */
    public function actionInfo($no)
    {
        return $this->render('info', [
            'model' => EnField::findOne(['no' => $no]),
            'status' => Constant::fieldStatus(),
            'online' => Constant::fieldOnline(),
            'stock' => EnStock::getStockByFieldToArr($no),
            'stockType' => Constant::stockType()
        ]);
    }

    /**
     * 指派专员
     * @param $no
     * @param $tel
     * @return string
     */
    public function actionAppoint($no, $tel)
    {
        if ($model = EnField::findOne(['no' => $no])) {
            if ($commissioner = EnMember::findOne(['tel' => $tel, 'company_id' => \Yii::$app->user->identity->company_id])) {
                $model->commissioner_id = $commissioner->id;
                $model->save(false);
                return $this->rJson();
            }
            return $this->rJson('', false, '专员不存在,请核对手机号');
        }
        return $this->rJson('', false, '错误操作');
    }

    /**
     * 场站抢单
     * @return \yii\web\Response
     */
    public function actionGet()
    {
        if ($re = EnField::robField()) {
            return $this->redirect(['edit', 'no' => $re]);
        }
        return $this->redirect(['list']);
    }

    /**
     * 添加股权
     * @return string
     */
    public function actionStockAdd()
    {
        $model = new EnStock();
        $model->no = Helper::createNo('S');
        $model->created_at = time();
        $key = \Yii::$app->request->get('key', '----');
        if ($model->load(['EnStock' => \Yii::$app->request->get()]) && $model->save()) {
            $model->key = in_array($model->type, [3, 4]) ? $key : '----';
            $model->type = Constant::stockType()[$model->type];
            return $this->rJson($model->toArray());
        }
        return $this->rJson([], false, $model->errors());
    }

    /**
     * 删除股权
     * @param string $no
     * @return string
     */
    public function actionStockDel($no = '')
    {
        if ($model = EnStock::findOne(['no' => $no])) {
            $model->delete();
            return $this->rJson();
        }
        return $this->rJson([], false, '该股权不存在');
    }
}