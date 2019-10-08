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
    /**
     * 扫码页
     * @return string
     */
    public function actionScan()
    {
        if ($order = EnOrder::findOne(['uid' => \Yii::$app->user->id, 'status' => [0, 1, 2]])) {
            if ($order['status'] == 2) {
                Msg::set('您有订单未支付');
                return $this->redirect(['order/charge/pay', 'no' => $order['no']]);
            }
            return $this->redirect(['c']);
        }
        return $this->render('scan.html', Wechat::getJsApiParams());
    }

    /**
     * 手动页
     * @return string
     */
    public function actionHand()
    {
        if ($order = EnOrder::findOne(['uid' => \Yii::$app->user->id, 'status' => [0, 1, 2]])) {
            if ($order['status'] == 2) {
                Msg::set('您有订单未支付');
                return $this->redirect(['order/charge/pay', 'no' => $order['no']]);
            }
            return $this->redirect(['c']);
        }
        return $this->render('hand.html');
    }

    /**
     * 充电页
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