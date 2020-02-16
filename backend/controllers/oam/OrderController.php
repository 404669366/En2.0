<?php
/**
 * Created by PhpStorm.
 * User: miku
 * Date: 2019/9/6
 * Time: 17:02
 */

namespace app\controllers\oam;


use app\controllers\basis\CommonController;
use vendor\project\base\EnOrder;
use vendor\project\helpers\Constant;

class OrderController extends CommonController
{


    /**
     * 订单列表
     * @return string
     */
    public function actionList()
    {
        return $this->render('list', ['status' => Constant::orderStatus()]);
    }

    /**
     * 列表数据
     * @return string
     */
    public function actionData()
    {
        return $this->rTableData(EnOrder::getPageData());
    }

    /**
     * 列表导出
     */
    public function actionExport()
    {
        EnOrder::export();
    }

    /**
     * 订单扣款
     * @param string $no
     * @return \yii\web\Response
     */
    public function actionDeduct($no = '')
    {
        EnOrder::deduct($no);
        return $this->redirect(['list']);
    }
}