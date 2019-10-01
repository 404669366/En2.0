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
     * @param string $orderNo
     * @param int $pay
     * @return string|\yii\web\Response
     */
    public function actionPay($orderNo = '', $pay = 0)
    {
        if ($order = (new client())->hGet('ChargeOrder', $orderNo)) {
            if ($order['status'] == 3 && $pay) {
                $transaction = \Yii::$app->db->beginTransaction();
                try {
                    $model = new EnOrder();
                    if ($model->load(['EnOrder' => $order]) && $model->validate() && $model->save()) {
                        $user = EnUser::findOne(\Yii::$app->user->id);
                        $money = $order['basisMoney'] + $order['serviceMoney'];
                        if ($user->money < $order['basisMoney']) {
                            throw new Exception('余额不足,请前往充值');
                        }
                        $user->money -= $money;
                        if ($user->save()) {
                            $transaction->commit();
                            (new client())->hDel('ChargeOrder', $orderNo);
                            \Yii::$app->session->set('order', '');
                            Msg::set('扣款成功');
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
        var_dump((new client())->hGetAll('ChargeOrder'));exit();
        return $this->render('list.html', [
            'order' => EnOrder::getOrders(),
            'now' => date('Y-m-d H:i:s')
        ]);
    }
}