<?php
/**
 * Created by PhpStorm.
 * User: miku
 * Date: 2019/10/9
 * Time: 12:40
 */

namespace app\controllers\finance;


use app\controllers\basis\CommonController;
use GatewayClient\Gateway;
use vendor\project\base\EnOrder;
use vendor\project\helpers\Constant;
use vendor\project\helpers\Msg;

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

    /**
     * 关闭订单
     * @param string $no
     * @return \yii\web\Response
     */
    public function actionEnd($no = '')
    {
        Msg::set('操作错误');
        if ($model = EnOrder::findOne(['no' => $no, 'status' => 0])) {
            $session = Gateway::getSessionByUid($model->pile);
            if ($session['status'][$model->gun]['workStatus'] == 0) {
                $model->status = 4;
                if ($model->save()) {
                    Gateway::sendToUid($model->pile, ['cmd' => 5, 'gun' => $model->gun, 'code' => 2, 'val' => 85]);
                    Msg::set('关闭成功');
                }
            }
        }
        return $this->redirect(['list']);
    }
}