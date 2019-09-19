<?php
/**
 * Created by PhpStorm.
 * User: miku
 * Date: 2019/9/19
 * Time: 17:08
 */

namespace app\controllers\index;


use app\controllers\basis\BasisController;

class IndexController extends BasisController
{
    /**
     * 首页
     * @return string
     */
    public function actionIndex()
    {
        return $this->render('index.html');
    }
}