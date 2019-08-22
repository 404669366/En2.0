<?php
/**
 * Created by PhpStorm.
 * User: miku
 * Date: 2019/8/21
 * Time: 11:46
 */

namespace app\controllers\c;


use app\controllers\basis\AuthController;
use vendor\project\base\EnPile;
use vendor\project\helpers\client;
use vendor\project\helpers\Constant;
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
        return $this->render('scan.html', Wechat::getJsApiParamsByScan());
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
     * 充电页
     * @param string $n
     * @return string|\yii\web\Response
     */
    public function actionC($n = '')
    {
        if ($info = EnPile::chargeInfo($n)) {
            return $this->render('charge.html', [
                'info' => $info,
                'code' => Constant::serverCode()
            ]);
        }
        return $this->goBack();
    }

    /**
     * 查询计费规则
     * @param string $no
     * @return string
     */
    public function actionRule($no = '')
    {
        $rule = json_decode((new client())->hGetField('PileInfo', $no, 'rules'), true);
        return $this->render('rule.html', ['rule' => $rule ?: [[0, 86399, 0.8, 0.6]]]);
    }
}