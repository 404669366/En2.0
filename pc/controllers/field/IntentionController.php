<?php
/**
 * Created by PhpStorm.
 * User: miku
 * Date: 2019/7/9
 * Time: 9:39
 */

namespace app\controllers\field;


use app\controllers\basis\AuthController;
use vendor\project\base\EnField;
use vendor\project\base\EnIntention;
use vendor\project\helpers\Helper;
use vendor\project\helpers\Msg;

class IntentionController extends AuthController
{
    /**
     * 意向下单页
     * @param string $no
     * @return string|\yii\web\Response
     */
    public function actionBuy($no = '')
    {
        if ($data = EnIntention::getBuyInfoByField($no)) {
            return $this->render('buy.html', ['data' => $data]);
        }
        Msg::set('该场站已完成融资');
        return $this->redirect(['field/field/detail', 'no' => $no]);
    }

    /**
     * 意向下单
     * @param string $no
     * @param int $num
     * @return \yii\web\Response
     */
    public function actionAdd($no = '', $num = 0)
    {
        Msg::set('该场站已完成融资');
        if ($field = EnField::findOne(['status' => 3, 'no' => $no])) {
            if ($field->canInvestByNum($num)) {
                $model = new EnIntention();
                $model->no = Helper::createNo('I');
                $model->field = $no;
                $model->user_id = \Yii::$app->user->id;
                $model->num = $num;
                $model->amount = $field->univalence * $num;
                $model->status = 0;
                $model->created_at = time();
                if ($model->save()) {
                    Msg::set('订单创建成功');
                    return $this->redirect(['pay', 'no' => $model->no]);
                }
                Msg::set($model->errors());
            }
        }
        return $this->redirect(['buy', 'no' => $no]);
    }

    /**
     * 跳转支付
     * @param string $no
     * @return string|\yii\web\Response
     */
    public function actionPay($no = '')
    {
        $msg = '错误操作';
        if ($model = EnIntention::findOne(['status' => 0, 'no' => $no])) {
            $msg = '该场站已完成融资';
            if ($model->local->status == 3) {
                $msg = '拉取支付信息失败';
                $model->pno = Helper::createNo('P');
                if ($model->save()) {
                    if ($url = $model->getPayDataByPc()) {
                        return $this->render('pay.html', [
                            'no' => $model->no,
                            'url' => $url
                        ]);
                    }
                }
            }
        }
        return $this->goBack($msg);
    }
}