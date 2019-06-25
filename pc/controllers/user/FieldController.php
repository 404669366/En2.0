<?php
/**
 * Created by PhpStorm.
 * User: miku
 * Date: 2019/6/24
 * Time: 14:32
 */

namespace app\controllers\user;


use app\controllers\basis\AuthController;

class FieldController extends AuthController
{
    /**
     * 发起项目
     * @return string
     */
    public function actionCreate()
    {
        return $this->render('create');
    }
}