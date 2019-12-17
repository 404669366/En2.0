<?php
/**
 * Created by PhpStorm.
 * User: miku
 * Date: 2019/10/9
 * Time: 12:40
 */

namespace app\controllers\finance;


use app\controllers\basis\CommonController;
use vendor\project\base\EnIncome;
use vendor\project\base\EnIntention;
use vendor\project\base\EnInvest;
use vendor\project\base\EnOrder;

class FinanceController extends CommonController
{
    /**
     * 融资统计
     * @return string
     */
    public function actionInfo()
    {
        return $this->render('info', [
            'month' => date('Y-m'),
            'data' => EnIntention::statisticsReportInfo()
        ]);
    }

    /**
     * 融资统计各月报表数据
     * @param $year
     * @return string
     */
    public function actionInfoData($year)
    {
        return $this->rJson(EnIntention::statisticsReportData($year));
    }

    /**
     * 融资统计单月报表数据
     * @param $month
     * @return string
     */
    public function actionMonthData($month)
    {
        return $this->rJson(EnIntention::statisticsMonthData($month));
    }


    /**
     * 充值消费页
     * @return string
     */
    public function actionReport()
    {
        return $this->render('report', [
            'invest' => EnInvest::reportInfo(),
            'order' => EnOrder::reportInfo()
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

    /**
     * 订单报表数据
     * @param string $year
     * @return string
     */
    public function actionOrderData($year = '')
    {
        return $this->rJson(EnOrder::reportData($year));
    }

    /**
     * 收益报表页
     * @return string
     */
    public function actionIncome()
    {
        return $this->render('income', ['income' => EnIncome::reportInfo(),]);
    }

    /**
     * 收益报表数据
     * @param string $year
     * @return string
     */
    public function actionIncomeData($year = '')
    {
        return $this->rJson(EnIncome::reportData($year));
    }
}