<?php
/**
 * Created by PhpStorm.
 * User: miku
 * Date: 2019/11/7
 * Time: 9:43
 */

namespace app\controllers\finance;


use app\controllers\basis\CommonController;
use vendor\project\base\EnIncome;

class IncomeController extends CommonController
{
    /**
     * 收益列表
     * @return string
     */
    public function actionList()
    {
        return $this->render('list');
    }

    /**
     * 收益数据
     * @return string
     */
    public function actionData()
    {
        return $this->rTableData(EnIncome::getPageData());
    }
}