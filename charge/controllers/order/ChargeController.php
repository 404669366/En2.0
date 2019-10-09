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
use vendor\project\base\EnUser;
use vendor\project\helpers\client;
use vendor\project\helpers\Msg;
use yii\db\Exception;

class ChargeController extends AuthController
{
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
                $transaction = \Yii::$app->db->beginTransaction();
                try {
                    $order->status = 3;
                    $user = EnUser::findOne(\Yii::$app->user->id);
                    $money = $order['bm'] + $order['sm'];
                    if ($user->money < $money) {
                        throw new Exception('余额不足,请前往充值');
                    }
                    $user->money -= $money;
                    if ($user->save()) {
                        if ($order->save()) {
                            $transaction->commit();
                            Msg::set('支付扣款成功');
                            return $this->redirect(['user/user/center']);
                        }
                    }
                    throw new Exception('系统错误,请稍后再试');
                } catch (Exception $e) {
                    $transaction->rollBack();
                    Msg::set($e->getMessage());
                    return $this->redirect(['list']);
                }
            }
            return $this->render('pay.html', ['order' => $order]);
        }
        Msg::set('订单不见啦');
        return $this->goBack();
    }

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
}