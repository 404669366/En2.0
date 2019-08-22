<?php
/**
 * Created by PhpStorm.
 * User: miku
 * Date: 2019/8/21
 * Time: 13:03
 */

namespace app\controllers\wx;


use vendor\project\helpers\Msg;
use yii\web\Controller;

class WxController extends Controller
{
    /**
     * 非微信页
     * @return string
     */
    public function actionNoWx()
    {
        Msg::set('请在微信中访问');
        return $this->render('no-wx.html');
    }
}