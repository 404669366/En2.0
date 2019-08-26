<?php
/**
 * Created by PhpStorm.
 * User: miku
 * Date: 2019/8/26
 * Time: 16:50
 */

namespace app\controllers\basis;


use vendor\project\helpers\Msg;
use yii\web\Controller;

class ErrorController extends Controller
{
    /**
     * 自定义错误页
     * @return string
     */
    public function actionError()
    {
        return $this->render('error.html');
    }

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