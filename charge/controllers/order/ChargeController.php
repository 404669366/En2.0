<?php
/**
 * Created by PhpStorm.
 * User: miku
 * Date: 2019/8/21
 * Time: 12:43
 */

namespace app\controllers\order;


use app\controllers\basis\AuthController;
use vendor\project\base\EnOrder;
use vendor\project\helpers\Msg;

class ChargeController extends AuthController
{
    /**
     * 充电订单
     * @return string
     */
    public function actionList()
    {
        return $this->render('list.html', [
            'order' => EnOrder::getOrders(),
            'now' => date('Y-m-d H:i:s')
        ]);
    }

    /**
     * 订单付款
     * @param string $no
     * @param int $pay
     * @return string|\yii\web\Response
     */
    public function actionPay($no = '', $pay = 0)
    {
        if ($order = EnOrder::findOne(['no' => $no, 'status' => 2])) {
            if ($pay) {
                if (EnOrder::deduct($no)) {
                    return $this->redirect(['list']);
                }
            }
            $order->rules = '';
            return $this->render('pay.html', ['order' => $order->toArray()]);
        }
        Msg::set('订单不见啦');
        return $this->goBack();
    }
}