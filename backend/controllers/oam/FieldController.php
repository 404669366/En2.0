<?php
/**
 * Created by PhpStorm.
 * User: miku
 * Date: 2019/9/6
 * Time: 17:02
 */

namespace app\controllers\oam;


use app\controllers\basis\CommonController;
use vendor\project\base\EnField;
use vendor\project\base\EnModel;
use vendor\project\base\EnPile;
use vendor\project\helpers\Constant;
use vendor\project\helpers\Msg;

class FieldController extends CommonController
{
    /**
     * 电站地图
     * @param string $key
     * @return string
     */
    public function actionMap($key = '')
    {
        return $this->render('map', [
            'data' => json_encode(EnField::getMapData($key)),
            'key' => $key
        ]);
    }

    /**
     * 电站地图信息
     * @param string $no
     * @return string
     */
    public function actionMapInfo($no = '')
    {
        return $this->rJson(EnField::getMapInfo($no));
    }

    /**
     * 上线操作
     * @param string $no
     * @return string
     */
    public function actionUp($no = '')
    {
        if ($model = EnField::findOne(['no' => $no, 'online' => 0, 'status' => 4])) {
            $model->online = 1;
            if ($model->save()) {
                return $this->rJson([], true, '上线成功');
            }
            return $this->rJson([], false, $model->errors());
        }
        return $this->rJson([], false, '非法操作');
    }

    /**
     * 下线操作
     * @param string $no
     * @return string
     */
    public function actionDown($no = '')
    {
        if ($model = EnField::findOne(['no' => $no, 'online' => 1, 'status' => 4])) {
            $model->online = 0;
            if ($model->save()) {
                return $this->rJson([], true, '下线成功');
            }
            return $this->rJson([], false, $model->errors());
        }
        return $this->rJson([], false, '非法操作');
    }

    /**
     * 场站电桩页
     * @param string $no
     * @return string
     */
    public function actionPile($no = '')
    {
        return $this->render('pile', ['no' => $no]);
    }

    /**
     * 场站电桩页数据
     * @param string $no
     * @return string
     */
    public function actionPileData($no = '')
    {
        return $this->rTableData(EnPile::getPageData($no));
    }

    /**
     * 场站电桩详情页
     * @param $no
     * @return string
     */
    public function actionPileInfo($no)
    {
        $model = EnPile::findOne(['no' => $no]);
        if (\Yii::$app->request->isPost) {
            $model->load(['EnPile' => \Yii::$app->request->post()]);
            if ($model->validate() && $model->save()) {
                Msg::set('保存成功');
            } else {
                Msg::set($model->errors());
            }
        }
        return $this->render('info', [
            'model' => $model,
            'models' => EnModel::getModels(),
            'code' => json_encode(Constant::serverCode()),
            'work' => json_encode(Constant::workStatus()),
            'link' => json_encode(Constant::linkStatus()),
        ]);
    }
}