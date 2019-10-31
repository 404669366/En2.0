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
        $msg = '系统错误';
        if (isset($data['result_code']) && $data['result_code'] == 'SUCCESS') {
            if ($model = EnIntention::findOne(['no' => $data['out_trade_no'], 'status' => 0])) {
                $model->status = 1;
                if ($model->save()) {
                    Helper::curlPost(
                        'http://47.99.36.149:2121',
                        [
                            'token' => 'BC-9fdad4748325434b84e113ef10ad8b2e',
                            'do' => 'publish',
                            'group' => $model->no,
                            'content' => json_encode(['type' => true, 'msg' => '支付成功']),
                        ]
                    );
                    return $this->rXml(['return_code' => 'SUCCESS', 'return_msg' => 'OK']);
                }
                $msg = $model->errors();
            }
        }
        Helper::curlPost(
            'http://47.99.36.149:2121',
            [
                'token' => 'BC-9fdad4748325434b84e113ef10ad8b2e',
                'do' => 'publish',
                'group' => $data['out_trade_no'],
                'content' => json_encode(['type' => false, 'msg' => $msg]),
            ]
        );
        return $this->rXml(['return_code' => 'FAIL', 'return_msg' => '支付失败,请稍后重试']);
    }
}