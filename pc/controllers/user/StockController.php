<?php
/**
 * Created by PhpStorm.
 * User: miku
 * Date: 2019/9/24
 * Time: 18:15
 */

namespace app\controllers\user;


use vendor\project\base\EnStock;

class StockController extends UserController
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