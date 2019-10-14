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
use vendor\project\base\EnOrder;

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
     * 对账管理
     * @return string
     */
    public function actionReply()
    {
        return $this->render('reply', ['status' => []]);
    }
}