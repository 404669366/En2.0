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
use vendor\project\helpers\Constant;

class ConsumeController extends CommonController
{
    /**
     * 消费页
     * @return string
     */
    public function actionReport()
    {
        return $this->render('report', [
            'month' => date('Y-m'),
            'order' => EnOrder::reportInfo()
        ]);
    }

    /**
     * 订单报表数据
     * @param string $year
     * @return string
     */
    public function actionReportData($year = '')
    {
        return $this->rJson(EnOrder::reportData($year));
    }

    /**
     * 场站单月报表数据
     * @param $month
     * @return string
     */
    public function actionMonthData($month)
    {
        return $this->rJson(EnField::statisticsMonthData($month));
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
            'month' => date('Y-m'),
            'data' => EnField::statisticsReportInfo($no)
        ]);
    }

    /**
     * 场站各月报表数据
     * @param $no
     * @param $year
     * @return string
     */
    public function actionFieldReportData($no, $year)
    {
        return $this->rJson(EnField::statisticsReportData($no, $year));
    }

    /**
     * 场站单月报表数据
     * @param $no
     * @param $month
     * @return string
     */
    public function actionFieldMonthData($no, $month)
    {
        return $this->rJson(EnField::statisticsMonthData($month, $no));
    }

    /**
     * 订单详情
     * @param $no
     * @return string
     */
    public function actionOrderDetail($no)
    {
        return $this->render('order-detail', ['no' => $no]);
    }

    /**
     * 订单列表
     * @return string
     */
    public function actionOrder()
    {
        return $this->render('order', ['status' => Constant::orderStatus()]);
    }

    /**
     * 列表数据
     * @return string
     */
    public function actionOrderData()
    {
        return $this->rTableData(EnOrder::getPageData());
    }

    /**
     * 列表导出
     */
    public function actionOrderExport()
    {
        EnOrder::export();
    }

    /**
     * 订单扣款
     * @param string $no
     * @return \yii\web\Response
     */
    public function actionOrderDeduct($no = '')
    {
        EnOrder::deduct($no);
        return $this->redirect(['order']);
    }
}