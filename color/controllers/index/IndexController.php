<?php

/**
 * Created by PhpStorm.
 * User: miku
 * Date: 2019/5/29
 * Time: 14:34
 */

namespace app\controllers\index;

use yii\web\Controller;

class IndexController extends Controller
{

    public function actionIndex()
    {
        return $this->render('index');
    }
}
