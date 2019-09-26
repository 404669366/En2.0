<?php
/**
 * Created by PhpStorm.
 * User: miku
 * Date: 2019/9/24
 * Time: 18:15
 */

namespace app\controllers\user;


use app\controllers\basis\AuthController;

class IncomeController extends AuthController
{
    public function actionList()
    {
        return $this->render('list.html', []);
    }
}