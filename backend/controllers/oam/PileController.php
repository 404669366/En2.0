<?php
/**
 * Created by PhpStorm.
 * User: miku
 * Date: 2019/8/13
 * Time: 10:39
 */

namespace app\controllers\oam;


use app\controllers\basis\CommonController;
use GatewayClient\Gateway;
use vendor\project\base\EnModel;
use vendor\project\base\EnOrder;
use vendor\project\base\EnPile;
use vendor\project\helpers\Constant;
use vendor\project\helpers\Msg;

class PileController extends CommonController
{
    /**
     * 电桩管理页
     * @return string
     */
    public function actionList()
    {
        return $this->render('list', [
            'online' => Constant::pileOnline(),
            'bind' => Constant::pileBind(),
        ]);
    }

    /**
     * 电桩管理页数据
     * @return string
     */
    public function actionData()
    {
        return $this->rTableData(EnPile::getPageData());
    }

    /**
     * 电桩信息页
     * @param $no
     * @return string
     */
    public function actionInfo($no)
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

    /**
     * 监控
     * @param string $pile
     * @param string $gun
     * @return string
     */
    public function actionLook($pile = '', $gun = '')
    {
        if ($order = EnOrder::findOne(['pile' => $pile, 'gun' => $gun, 'status' => [0, 1]])) {
            return $this->render('look', [
                'info' => json_encode([
                    'do' => 'seeCharge',
                    'pile' => $order->pile,
                    'gun' => $order->gun,
                ]),
            ]);
        }
        return $this->goBack('充电不存在或已结束');
    }

    /**
     * 制作二维码
     * @return string
     */
    public function actionQrcode()
    {
        return $this->render('qrcode');
    }
}