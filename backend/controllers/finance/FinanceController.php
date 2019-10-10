<?php
/**
 * Created by PhpStorm.
 * User: miku
 * Date: 2019/10/9
 * Time: 12:40
 */

namespace app\controllers\finance;


use app\controllers\basis\CommonController;
use vendor\project\base\EnInvest;

class FinanceController extends CommonController
{
    /**
     * 充值列表
     * @return string
     */
    public function actionReport()
    {
        return $this->render('report', [
            'invest' => EnInvest::reportInfo(),
            'order' => []
        ]);
    }

    /**
     * 充值报表数据
     * @param string $year
     * @return string
     */
    public function actionInvestData($year = '')
    {
        return $this->rJson(EnInvest::reportData($year));
    }

    public function actionOrderData()
    {

    }
}