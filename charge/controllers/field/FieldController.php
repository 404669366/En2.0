<?php
/**
 * Created by PhpStorm.
 * User: miku
 * Date: 2019/8/21
 * Time: 9:23
 */

namespace app\controllers\field;


use app\controllers\basis\AuthController;
use vendor\project\helpers\Wechat;

class FieldController extends AuthController
{
    public function actionMap()
    {
        return $this->render('map.html', ['jsApi' => Wechat::getJsApiParams()]);
    }
}