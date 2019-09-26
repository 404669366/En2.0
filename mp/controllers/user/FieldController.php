<?php
/**
 * Created by PhpStorm.
 * User: miku
 * Date: 2019/9/24
 * Time: 18:13
 */

namespace app\controllers\user;


use app\controllers\basis\AuthController;

class FieldController extends AuthController
{
    public function actionAdd()
    {
        return $this->render('add.html', []);
    }

    public function actionList()
    {
        return $this->render('list,html', []);
    }
}