<?php
/**
 * Created by PhpStorm.
 * User: miku
 * Date: 2019/10/9
 * Time: 12:40
 */

namespace app\controllers\finance;


use app\controllers\basis\CommonController;
use vendor\project\base\EnIntention;

class FinanceController extends CommonController
{
    /**
     * 融资统计
     * @return string
     */
    public function actionReport()
    {
        return $this->render('report', [
            'finance' => EnIntention::statisticsReportInfo(),
            'data' => json_encode(EnIntention::statisticsYearData()),
        ]);
    }

    /**
     * 年报表数据
     * @param $year
     * @return string
     */
    public function actionYearData($year)
    {
        return $this->rJson(EnIntention::statisticsYearData($year));
    }

    /**
     * 月报表数据
     * @param $year
     * @param $month
     * @return string
     */
    public function actionMonthData($year, $month)
    {
        return $this->rJson(EnIntention::statisticsMonthData($year, $month));
    }

    /**
     * 日报表数据
     * @param $date
     * @return string
     */
    public function actionDayData($date)
    {
        return $this->rJson(EnIntention::statisticsDayData($date));
    }
}