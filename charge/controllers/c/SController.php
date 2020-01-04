<?php
/**
 * Created by PhpStorm.
 * User: miku
 * Date: 2019/12/27
 * Time: 15:44
 */

namespace app\controllers\c;


use app\controllers\basis\AuthController;
use GatewayClient\Gateway;
use vendor\project\base\EnOrder;
use vendor\project\helpers\Constant;
use vendor\project\helpers\Msg;

class SController extends AuthController
{
    /**
     * 充电页
     * @return string|\yii\web\Response
     */
    public function actionS()
    {
        if ($order = EnOrder::findOne(['no' => \Yii::$app->request->get('no', ''), 'status' => [0, 1, 2]])) {
            if ($order->status == 2) {
                Msg::set('充电已结束!');
                return $this->redirect(['order/charge/pay', 'no' => $order->no]);
            }
            var_dump(222);exit();
            return $this->render('charge.html', [
                'no' => $order->no,
                'fieldName' => $order->pileInfo->local->name,
                'cmd' => [
                    'do' => 'joinCharge',
                    'pile' => $order->pile,
                    'gun' => $order->gun,
                ],
                'code' => Constant::serverCode(),
            ]);
        }
        return $this->goBack('错误操作!');
    }

    /**
     * 结束充电
     * @param string $no
     * @return string
     */
    public function actionEnd($no = '')
    {
        if ($order = EnOrder::findOne(['no' => $no, 'uid' => \Yii::$app->user->id, 'status' => [0, 1]])) {
            Gateway::sendToUid($order->pile, ['cmd' => 5, 'gun' => $order->gun, 'code' => 2, 'val' => 85]);
            return $this->rJson([], true, '充电结束中,请稍后');
        }
        return $this->rJson([], false, '充电已结束!');
    }
}