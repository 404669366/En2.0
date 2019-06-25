<?php
/**
 * Created by PhpStorm.
 * User: 40466
 * Date: 2018/9/21
 * Time: 14:41
 */

namespace app\controllers\basis;


use vendor\project\base\EnPower;
use vendor\project\helpers\Msg;

class CommonController extends AuthController
{
    public function beforeAction($action)
    {
        if (!EnPower::pass()) {
            Msg::set('您没有该操作权限');
            return $this->redirect([\Yii::$app->params['firstRoute']])->send();
        }
        return parent::beforeAction($action);
    }
}