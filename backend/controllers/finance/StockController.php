<?php
/**
 * Created by PhpStorm.
 * User: miku
 * Date: 2019/10/9
 * Time: 12:40
 */

namespace app\controllers\finance;


use app\controllers\basis\CommonController;
use vendor\project\base\EnField;
use vendor\project\base\EnIncome;

class StockController extends CommonController
{

    /**
     * 场站统计列表
     * @return string
     */
    public function actionList()
    {
        return $this->render('list');
    }

    /**
     * 场站统计列表数据
     * @return string
     */
    public function actionData()
    {
        return $this->rTableData(EnField::getStatisticsData());
    }

    /**
     * 统计页
     * @param $no
     * @return string
     */
    public function actionReport($no)
    {
        return $this->render('report', [
            'no' => $no,
            'data' => json_encode(EnField::getStockInfo($no)),
            'years' => EnField::getIncomeYears($no)
        ]);
    }

    /**
     * 年报表数据
     * @param string $sno
     * @param string $year
     * @return string
     */
    public function actionYearData($sno = '', $year = '')
    {
        return $this->rJson(EnIncome::yearDataByStock($sno, $year));
    }

    /**
     * 月报表数据
     * @param string $sno
     * @param string $year
     * @param string $month
     * @return string
     */
    public function actionMonthData($sno = '', $year = '', $month = '')
    {
        return $this->rJson(EnIncome::monthDataByStock($sno, $year, $month));
    }

    /**
     * 日报表数据
     * @param string $sno
     * @param $date
     * @return string
     */
    public function actionDayData($sno = '', $date)
    {
        return $this->rJson(EnIncome::statisticsDateDataByStock($sno, $date));
    }
}