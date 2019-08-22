<?php
/**
 * Created by PhpStorm.
 * User: miku
 * Date: 2019/8/21
 * Time: 12:45
 */

namespace app\controllers\order;


use app\controllers\basis\AuthController;

class InvestController extends AuthController
{
    /**
     * 充值记录
     * @return string
     */
    public function actionList()
    {
        return $this->render('list.html');
    }
}