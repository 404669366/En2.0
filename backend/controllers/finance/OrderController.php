<?php
/**
 * Created by PhpStorm.
 * User: miku
 * Date: 2019/10/9
 * Time: 12:40
 */

namespace app\controllers\finance;


use app\controllers\basis\CommonController;
use vendor\project\base\EnOrder;
use vendor\project\helpers\Constant;

class OrderController extends CommonController
{
    /**
     * 订单列表
     * @return string
     */
    public function actionList()
    {
        return $this->render('list', ['status' => Constant::orderStatus()]);
    }

    /**
     * 列表数据
     * @return string
     */
    public function actionData()
    {
        return $this->rTableData(EnOrder::getPageData());
    }
}