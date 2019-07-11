<?php
/**
 * Created by PhpStorm.
 * User: miku
 * Date: 2019/7/8
 * Time: 9:27
 */

namespace app\controllers\agreement;


use app\controllers\basis\BasisController;

class AgreementController extends BasisController
{
    /**
     * 注册协议
     * @return string
     */
    public function actionLogon()
    {
        return $this->render('logon');
    }
}