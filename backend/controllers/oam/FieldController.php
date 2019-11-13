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
     * 场站上线管理列表
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
     * 场站上线管理列表数据
     * @return string
     */
    public function actionData()
    {
        return $this->rTableData(EnField::getPageData(4, false));
    }

    /**
     * 场站电桩页
     * @param string $no
     * @param string $back
     * @return string
     */
    public function actionPile($no = '', $back = '/oam/field/list')
    {
        return $this->render('pile', ['no' => $no, 'back' => $back]);
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
     * @param string $back
     * @return string
     */
    public function actionPileInfo($no, $back = '')
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
            'back' => $back,
        ]);
    }

    /**
     * 上线操作
     * @param string $no
     * @return \yii\web\Response
     */
    public function actionUp($no = '')
    {
        Msg::set('非法操作');
        if ($model = EnField::findOne(['no' => $no, 'online' => 0])) {
            $model->online = 1;
            Msg::set('上线成功');
            if (!$model->save()) {
                Msg::set($model->errors());
            }
        }
        return $this->redirect(['list']);
    }

    /**
     * 下线操作
     * @param string $no
     * @return \yii\web\Response
     */
    public function actionDown($no = '')
    {
        Msg::set('非法操作');
        if ($model = EnField::findOne(['no' => $no, 'online' => 1])) {
            $model->online = 0;
            Msg::set('下线成功');
            if (!$model->save()) {
                Msg::set($model->errors());
            }
        }
        return $this->redirect(['list']);
    }

    /**
     * 电站地图
     * @return string
     */
    public function actionMap()
    {
        return $this->render('map', ['data' => json_encode(EnField::getMapData())]);
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
}