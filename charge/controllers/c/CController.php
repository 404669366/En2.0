<?php
/**
 * Created by PhpStorm.
 * User: miku
 * Date: 2019/8/21
 * Time: 11:46
 */

namespace app\controllers\c;


use app\controllers\basis\AuthController;
use GatewayClient\Gateway;
use vendor\project\base\EnOrder;
use vendor\project\base\EnPile;
use vendor\project\base\EnUser;
use vendor\project\helpers\Helper;
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
            return $this->redirect(['c/s/c', 'no' => $order->no])->send();
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
     * 启动充电
     * @param string $n
     * @return \yii\web\Response
     */
    public function actionC($n = '')
    {
        if ($n) {
            if (EnUser::ampleMoney()) {
                $no = explode('-', $n);
                if (count($no) == 2) {
                    if ($pile = EnPile::find()->where(['no' => $no[0], 'online' => 1])->andWhere(['>=', 'count', $no[1]])->one()) {
                        if (Gateway::isUidOnline($no[0])) {
                            $order = new EnOrder();
                            $order->no = Helper::createNo('O');
                            $order->pile = $no[0];
                            $order->gun = $no[1];
                            $order->uid = \Yii::$app->user->id;
                            $order->rules = $pile->rules;
                            $order->status = 0;
                            $order->created_at = time();
                            if ($order->save()) {
                                Msg::set('充电启动中');
                                Gateway::sendToUid($order->pile, ['cmd' => 7, 'gun' => $order->gun, 'orderNo' => $order->no]);
                                return $this->redirect(['c/s/c', 'no' => $order->no]);
                            }
                            return $this->goBack($order->errorsInfo());
                        }
                    }
                    return $this->goBack('电桩离线');
                }
                return $this->goBack('编码有误');
            }
            Msg::set('余额不足<br>请先充值');
            return $this->redirect(['order/invest/invest']);
        }
        return $this->redirect(['user/user/center']);
    }
}