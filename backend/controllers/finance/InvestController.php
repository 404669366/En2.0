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
     * 充值列表
     * @return string
     */
    public function actionList()
    {
        return $this->render('list', [
            'status' => Constant::investStatus(),
            'source' => Constant::investSource()
        ]);
    }

    /**
     * 充值数据
     * @return string
     */
    public function actionData()
    {
        return $this->rTableData(EnInvest::getPageData());
    }
}