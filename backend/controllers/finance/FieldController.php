<?php
/**
 * Created by PhpStorm.
 * User: miku
 * Date: 2019/12/17
 * Time: 10:35
 */

namespace app\controllers\finance;


use app\controllers\basis\CommonController;
use vendor\project\base\EnField;

class FieldController extends CommonController
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
    public function actionInfo($no)
    {
        return $this->render('info', [
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
    public function actionInfoData($no, $year)
    {
        return $this->rJson(EnField::statisticsReportData($no, $year));
    }

    /**
     * 场站单月报表数据
     * @param $no
     * @param $month
     * @return string
     */
    public function actionMonthData($no, $month)
    {
        return $this->rJson(EnField::statisticsMonthData($no, $month));
    }
}