<?php
/**
 * Created by PhpStorm.
 * User: miku
 * Date: 2019/8/21
 * Time: 11:46
 */

namespace app\controllers\c;


use app\controllers\basis\AuthController;
use vendor\project\base\EnOrder;
use vendor\project\base\EnPile;
use vendor\project\helpers\Constant;
use vendor\project\helpers\Msg;
use vendor\project\helpers\Wechat;

class CController extends AuthController
{
    public function beforeAction($action)
    {
        $re = parent::beforeAction($action);
        if ($order = EnOrder::findOne(['uid' => \Yii::$app->user->id, 'status' => [0, 1, 2]])) {
            if ($order['status'] == 2) {
                Msg::set('您有订单需要支付');
                return $this->redirect(['order/charge/pay', 'no' => $order['no']])->send();
            }
            Msg::set('您有订单正在进行');
            return $this->render('charge.html', [
                'info' => [
                    'do' => 'seeCharge',
                    'pile' => $order->pile,
                    'gun' => $order->gun,
                    'fieldName' => $order->pileInfo->local->name,
                ],
                'code' => Constant::serverCode(),
            ]);
        }
        return $re;
    }

    /**
     * 扫码页
     * @return string
     */
    public function actionScan()
    {
        return $this->render('scan.html', Wechat::getJsApiParams());
    }

    /**
     * 手动页
     * @return string
     */
    public function actionHand()
    {
        return $this->render('hand.html');
    }

    /**
     * 启动充电页
     * @param string $n
     * @return string|\yii\web\Response
     */
    public function actionC($n = '')
    {
        if ($info = EnPile::chargeInfo($n)) {
            return $this->render('charge.html', [
                'info' => $info,
                'code' => Constant::serverCode(),
            ]);
        }
        return $this->redirect(['user/user/center']);
    }
}