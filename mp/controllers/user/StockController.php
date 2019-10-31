<?php
/**
 * Created by PhpStorm.
 * User: miku
 * Date: 2019/9/24
 * Time: 18:15
 */

namespace app\controllers\user;


use app\controllers\basis\AuthController;
use vendor\project\base\EnStock;

class StockController extends AuthController
{
    /**
     * 我的股权
     * @return string
     */
    public function actionList()
    {
        return $this->render('list.html', ['data' => EnStock::listDataByCenter()]);
    }
}