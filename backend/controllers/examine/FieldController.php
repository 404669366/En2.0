<?php
/**
 * Created by PhpStorm.
 * User: miku
 * Date: 2019/6/18
 * Time: 12:22
 */

namespace app\controllers\examine;

use app\controllers\basis\CommonController;
use vendor\project\base\EnField;
use vendor\project\base\EnStock;
use vendor\project\helpers\Constant;
use vendor\project\helpers\Msg;

class FieldController extends CommonController
{

    /**
     * 场地审核列表
     * @return string
     */
    public function actionList()
    {
        return $this->render('list', [
            'status' => Constant::fieldStatus(),
            'online' => Constant::fieldOnline(),
        ]);
    }

    /**
     * 场地审核列表数据
     * @return string
     */
    public function actionData()
    {
        return $this->rTableData(EnField::getPageData([2, 3, 4]));
    }

    /**
     * 场地审核页
     * @param $no
     * @return string
     */
    public function actionInfo($no)
    {
        $model = EnField::findOne(['no' => $no]);
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
            'status' => Constant::fieldStatus(),
            'online' => Constant::fieldOnline(),
            'stock' => EnStock::getStockByFieldToArr($no),
            'stockType' => Constant::stockType()
        ]);
    }
}