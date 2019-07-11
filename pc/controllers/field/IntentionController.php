<?php
/**
 * Created by PhpStorm.
 * User: miku
 * Date: 2019/7/9
 * Time: 9:39
 */

namespace app\controllers\field;


use app\controllers\basis\AuthController;

class IntentionController extends AuthController
{
    public function actionAdd($no = '')
    {
        return $this->goBack('认购成功');
    }
}