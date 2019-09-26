<?php
/**
 * Created by PhpStorm.
 * User: miku
 * Date: 2019/7/22
 * Time: 18:09
 */

namespace app\controllers\wx;


use app\controllers\basis\BasisController;
use vendor\project\base\EnIntention;
use vendor\project\helpers\Helper;

class PayController extends BasisController
{
    /**
     * 支付回调
     * @return string
     */
    public function actionBack()
    {
        $data = $this->getXml();
        if (isset($data['return_code']) && $data['return_code'] == 'SUCCESS') {
            if ($model = EnIntention::findOne(['no' => $data['out_trade_no'], 'status' => 1])) {
                $model->pay_at = time();
                $model->status = 2;
                if ($model->save()) {
                    Helper::curlPost(
                        'http://127.0.0.1:2121',
                        [
                            'token' => 'BC-9fdad4748325434b84e113ef10ad8b2e',
                            'do' => 'publish',
                            'group' => $model->no,
                            'content' => 1,
                        ]
                    );
                    return $this->rXml(['return_code' => 'SUCCESS', 'return_msg' => 'OK']);
                }
            }
        }
        Helper::curlPost(
            'http://127.0.0.1:2121',
            [
                'token' => 'BC-9fdad4748325434b84e113ef10ad8b2e',
                'do' => 'publish',
                'group' => $data['out_trade_no'],
                'content' => 0,
            ]

        );
        return $this->rXml(['return_code' => 'FAIL', 'return_msg' => '充值失败']);
    }
}