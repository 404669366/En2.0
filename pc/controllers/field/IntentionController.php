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
use vendor\project\base\EnUser;
use vendor\project\helpers\Constant;
use vendor\project\helpers\Helper;
use vendor\project\helpers\Msg;
use vendor\project\helpers\Wechat;

class IntentionController extends AuthController
{
    /**
     * 创建意向
     * @param string $no
     * @return \yii\web\Response
     */
    public function actionAdd($no = '')
    {
        if ($detail = EnField::findOne(['status' => 4, 'no' => $no])) {
            $model = new EnIntention();
            $model->no = Helper::createNo('I');
            $model->user = \Yii::$app->user->identity->tel;
            $model->created_at = time();
            $model->source = 1;
            $model->status = 1;
            $model->commissioner_id = 0;
            $model->field = $no;
            if ($model->save()) {
                Msg::set('意向保存成功,专员将尽快与您联系');
                return $this->redirect(['user/intention/list']);
            }
            return $this->goBack($model->errors());
        }
        return $this->goBack('该场站已完成融资');
    }

    /**
     * 意向下单
     * @param string $no
     * @return string|\yii\web\Response
     */
    public function actionBuy($no = '')
    {
        if ($detail = EnField::findOne(['status' => 4, 'no' => $no])) {
            if (\Yii::$app->request->isPost) {
                $cobber_id = 0;
                if (isset($_COOKIE['field-r-' . $detail->id]) && $_COOKIE['field-r-' . $detail->id]) {
                    $cobber = (($_COOKIE['field-r-' . $detail->id] - 666) * 2 - 520) / 2;
                    if ($cobber = EnUser::findOne($cobber)) {
                        $cobber_id = $cobber->id == \Yii::$app->user->id ? 0 : $cobber->id;
                    }
                }
                $post = \Yii::$app->request->post();
                $model = new EnIntention();
                $model->no = Helper::createNo('I');
                $model->purchase_amount = $post['purchase_amount'];
                $model->order_amount = $model->purchase_amount * Constant::orderRatio();
                $model->part_ratio = $model->purchase_amount / $detail->budget_amount;
                $model->field_id = $detail->id;
                $model->commissioner_id = $detail->commissioner_id;
                $model->user_id = \Yii::$app->user->id;
                $model->cobber_id = $cobber_id;
                $model->source = 1;
                $model->status = 1;
                $model->created_at = time();
                if ($model->save()) {
                    return $this->redirect(['pay', 'id' => $model->id]);
                }
                Msg::set($model->errors());
                return $this->redirect(['field/field/detail', 'no' => $detail->no]);
            }
            return $this->render('buy.html', [
                'detail' => $detail->attributes,
                'images' => explode(',', $detail->images)[0],
                'business_type' => Constant::businessType()[$detail->business_type],
                'invest_type' => Constant::investType()[$detail->invest_type],
                '_csrf' => \Yii::$app->request->csrfToken,
                'ratio' => Constant::orderRatio()
            ]);
        }
        return $this->goBack('该场站已完成融资');
    }

    /**
     * 跳转支付
     * @param int $id
     * @return string|\yii\web\Response
     */
    public function actionPay($id = 0)
    {
        if ($model = EnIntention::findOne(['status' => 1, 'id' => $id])) {
            $times = 0;
            if (isset($_COOKIE['pay-times-' . $id]) && $_COOKIE['pay-times-' . $id]) {
                $times = $_COOKIE['pay-times-' . $id];
                $model->no = Helper::createNo('I');
                $model->save(false);
            }
            setcookie('pay-times-' . $id, $times + 1, time() + (60 * 60 * 24), '/');
            if ($data = Wechat::nativePay($model->no, $model->order_amount)) {
                return $this->render('pay.html', [
                    'id' => $model->id,
                    'no' => $model->no,
                    'url' => $data['code_url'],
                    'fieldNo' => $model->field->no
                ]);
            }
        }
        return $this->goBack('错误操作');
    }

    /**
     * 放弃支付
     * @param string $no
     * @return \yii\web\Response
     */
    public function actionNoPay($no = '')
    {
        Msg::set('错误操作');
        $model = EnIntention::findOne(['no' => $no]);
        if ($model->status == 1) {
            $model->status = 7;
            $model->save(false);
            Msg::set('放弃成功');
            setcookie('pay-times-' . $model->id, 0, time() + 1, '/');
        }
        return $this->redirect(['field/field/detail', 'no' => $model->field->no]);
    }
}