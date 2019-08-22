<?php
/**
 * Created by PhpStorm.
 * User: miku
 * Date: 2019/8/21
 * Time: 12:43
 */

namespace app\controllers\order;


use app\controllers\basis\AuthController;

class ChargeController extends AuthController
{
    /**
     * 充电订单
     * @return string
     */
    public function actionList()
    {
        return $this->render('list.html');
    }
}