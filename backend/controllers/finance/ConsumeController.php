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

class ConsumeController extends CommonController
{
    /**
     * 消费页
     * @return string
     */
    public function actionReport()
    {
        return $this->render('report', [
            'order' => EnOrder::reportInfo(),
            'data' => json_encode(EnOrder::yearData()),
        ]);
    }

    /**
     * 场站统计列表
     * @return string
     */
    public function actionFieldList()
    {
        return $this->render('field-list');
    }

    /**
     * 场站统计列表数据
     * @return string
     */
    public function actionFieldData()
    {
        return $this->rTableData(EnField::getStatisticsData());
    }

    /**
     * 场站报表数据
     * @param $no
     * @return string
     */
    public function actionFieldReport($no)
    {
        return $this->render('field-report', [
            'no' => $no,
            'order' => EnOrder::reportInfo($no),
            'data' => json_encode(EnOrder::yearData('', $no))
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
        return $this->rJson(EnOrder::yearData($year, $no));
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
        return $this->rJson(EnOrder::monthData($year, $month, $no));
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

    /**
     * 订单详情
     * @param $no
     * @return string
     */
    public function actionOrderDetail($no)
    {
        $order = EnOrder::findOne(['no' => $no]);
        return $this->render('order-detail', [
            'no' => $no,
            'fno' => $order->pileInfo->field
        ]);
    }
}