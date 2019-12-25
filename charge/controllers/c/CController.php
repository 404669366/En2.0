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
use vendor\project\base\EnUser;
use vendor\project\helpers\Constant;
use vendor\project\helpers\Helper;
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
        return $this->render('hand.html');
    }

    /**
     * 启动充电页
     * @param string $n
     * @return string|\yii\web\Response
     */
    public function actionC($n = '')
    {
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
        if (EnUser::getMoney() > 1) {
            $no = explode('-', $n);
            if (count($no) == 2) {
                if ($pile = EnPile::find()->where(['no' => $no[0]])->andWhere(['>=', 'count', $no[1]])->one()) {
                    if ($pile->online == 1) {
                        $order = new EnOrder();
                        $order->no = Helper::createNo('O');
                        $order->pile = $no[0];
                        $order->gun = $no[1];
                        $order->uid = \Yii::$app->user->id;
                        $order->rules = $pile->rules;
                        $order->status = 0;
                        $order->created_at = time();
                        if ($order->save()) {
                            return $this->render('charge.html', [
                                'info' => [
                                    'do' => 'beginCharge',
                                    'orderNo' => $order->no,
                                    'pile' => $order->pile,
                                    'gun' => $order->gun,
                                    'fieldName' => $pile->local->name
                                ],
                                'code' => Constant::serverCode(),
                            ]);
                        }
                        return $this->goBack('创建订单失败,请稍后再试');
                    }
                    return $this->goBack('电桩离线,请稍后再试');
                }
            }
            return $this->goBack('编码有误,请检查');
        }
        Msg::set('余额不足,请先充值');
        return $this->redirect(['order/invest/invest']);
    }
}