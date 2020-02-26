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

class InvestController extends CommonController
{

    /**
     * 充值页
     * @return string
     */
    public function actionReport()
    {
        return $this->render('report', [
            'invest' => EnInvest::reportInfo(),
            'data' => json_encode(EnInvest::yearData()),
        ]);
    }

    /**
     * 年报表数据
     * @param string $year
     * @return string
     */
    public function actionYearData($year = '')
    {
        return $this->rJson(EnInvest::yearData($year));
    }

    /**
     * 月报表数据
     * @param string $year
     * @param $month
     * @return string
     */
    public function actionMonthData($year = '', $month = '')
    {
        return $this->rJson(EnInvest::monthData($year, $month));
    }

    /**
     * 日报表数据
     * @param $date
     * @return string
     */
    public function actionDayData($date)
    {
        return $this->rJson(EnInvest::statisticsDateData($date));
    }
}