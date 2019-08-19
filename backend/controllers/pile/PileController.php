<?php
/**
 * Created by PhpStorm.
 * User: miku
 * Date: 2019/8/13
 * Time: 10:39
 */

namespace app\controllers\pile;


use app\controllers\basis\CommonController;
use vendor\project\base\EnModel;
use vendor\project\base\EnPile;
use vendor\project\helpers\Constant;
use vendor\project\helpers\Helper;
use vendor\project\helpers\Msg;

class PileController extends CommonController
{
    /**
     * 在线电桩页
     * @return string
     */
    public function actionList()
    {
        return $this->render('list');
    }

    /**
     * 电桩信息页
     * @param $no
     * @return string
     */
    public function actionInfo($no)
    {
        $model = EnPile::findOne(['no' => $no]);
        if (!$model) {
            $model = new EnPile();
            $model->no = Helper::createNo('P');
            $model->created_at = time();
        }
        if (\Yii::$app->request->isPost) {
            $model->load(['EnPile' => \Yii::$app->request->post()]);
            if ($model->validate() && $model->save()) {
                Msg::set('保存成功');
            } else {
                Msg::set($model->errors());
            }
        }
        return $this->render('info', [
            'rNo' => $no,
            'model' => $model,
            'models' => EnModel::getModels(),
            'code' => json_encode(Constant::serverCode()),
            'carStatus' => json_encode(Constant::carStatus()),
            'workStatus' => json_encode(Constant::workStatus()),
        ]);
    }

    /**
     * 修改编号
     * @param string $old
     * @param string $new
     * @return string
     */
    public function actionEditNo($old = '', $new = '')
    {
        if ($model = EnPile::findOne(['no' => $old])) {
            $model->no = $new;
            $model->save(false);
        }
        return $this->rJson();
    }
}