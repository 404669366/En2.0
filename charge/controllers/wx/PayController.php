<?php
/**
 * Created by PhpStorm.
 * User: miku
 * Date: 2019/7/22
 * Time: 18:09
 */

namespace app\controllers\wx;


use vendor\project\base\EnInvest;
use vendor\project\base\EnUser;
use vendor\project\helpers\Helper;
use yii\web\Controller;

class PayController extends Controller
{
    public $enableCsrfValidation = false;

    /**
     * 支付回调
     * @return string
     */
    public function actionBack()
    {
        $data = Helper::getXml();
        if (isset($data['return_code']) && $data['return_code'] == 'SUCCESS') {
            if ($model = EnInvest::findOne(['no' => $data['out_trade_no'], 'status' => 0])) {
                if (EnUser::addMoney($model->uid, $model->money)) {
                    $model->status = 1;
                    $model->save();
                    return Helper::spliceXml(['return_code' => 'SUCCESS', 'return_msg' => 'OK']);
                }
                $model->status = 2;
                $model->save();
            }
        }
        return Helper::spliceXml(['return_code' => 'FAIL', 'return_msg' => '充值失败']);
    }
}