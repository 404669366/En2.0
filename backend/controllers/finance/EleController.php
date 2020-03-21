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
use vendor\project\base\EnOrder;

class EleController extends CommonController
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
     * 场站报表数据
     * @param $no
     * @return string
     */
    public function actionReport($no)
    {
        return $this->render('report', [
            'no' => $no,
            'order' => EnOrder::reportInfo($no, true),
            'data' => json_encode(EnOrder::yearData('', $no, true))
        ]);
    }

    /**
     * 年报表数据
     * @param string $year
     * @param string $no
     * @return string
     */
    public function actionYearData($year = '', $no = '')
    {
        return $this->rJson(EnOrder::yearData($year, $no, true));
    }

    /**
     * 月报表数据
     * @param string $year
     * @param string $month
     * @param string $no
     * @return string
     */
    public function actionMonthData($year = '', $month = '', $no = '')
    {
        return $this->rJson(EnOrder::monthData($year, $month, $no, true));
    }

    /**
     * 日报表数据
     * @param string $date
     * @param string $no
     * @return string
     */
    public function actionDayData($date = '', $no = '')
    {
        return $this->rJson(EnOrder::statisticsDateData($date, $no));
    }
}