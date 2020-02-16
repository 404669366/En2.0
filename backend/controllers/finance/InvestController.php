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
use vendor\project\helpers\Constant;

class InvestController extends CommonController
{

    /**
     * 充值页
     * @return string
     */
    public function actionReport()
    {
        return $this->render('report', [
            'month' => date('Y-m'),
            'invest' => EnInvest::reportInfo(),
        ]);
    }
    /**
     * 充值报表数据
     * @param string $year
     * @return string
     */
    public function actionReportData($year = '')
    {
        return $this->rJson(EnInvest::reportData($year));
    }

    /**
     *  充值单月报表数据
     * @param $month
     * @return string
     */
    public function actionMonthData($month)
    {
        return $this->rJson(EnInvest::statisticsMonthData($month));
    }
}