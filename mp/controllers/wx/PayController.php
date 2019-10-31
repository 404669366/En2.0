<?php
/**
 * Created by PhpStorm.
 * User: miku
 * Date: 2019/7/22
 * Time: 18:09
 */

namespace app\controllers\wx;


use vendor\project\base\EnIntention;
use yii\web\Controller;

class PayController extends Controller
{
    public $enableCsrfValidation = false;

    /**
     * 返回xml数据
     * @param array $data
     * @return string
     */
    public function rXml($data = [])
    {
        $xml = '<xml>';
        foreach ($data as $k => $v) {
            $xml .= "<$k>$v</$k>";
        }
        $xml .= '</xml>';
        echo $xml;
        exit();
    }

    /**
     * 获取xml
     * @return array
     */
    public function getXml()
    {
        return (array)simplexml_load_string(file_get_contents("php://input"), 'SimpleXMLElement', LIBXML_NOCDATA);
    }

    /**
     * 支付回调
     * @return string
     */
    public function actionBack()
    {
        $data = $this->getXml();
        if (isset($data['result_code']) && $data['result_code'] == 'SUCCESS') {
            if ($model = EnIntention::findOne(['no' => $data['out_trade_no'], 'status' => 0])) {
                $model->status = 1;
                if ($model->save()) {
                    return $this->rXml(['return_code' => 'SUCCESS', 'return_msg' => 'OK']);
                }
            }
        }
        return $this->rXml(['return_code' => 'FAIL', 'return_msg' => '支付失败,请稍后重试']);
    }
}