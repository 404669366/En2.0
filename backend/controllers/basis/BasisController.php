<?php
/**
 * Created by PhpStorm.
 * User: 40466
 * Date: 2018/9/21
 * Time: 10:16
 */

namespace app\controllers\basis;


use vendor\project\helpers\Msg;
use yii\web\Controller;

class BasisController extends Controller
{
    /**
     * 返回json数据
     * @param array $data
     * @param bool $type
     * @param string $msg
     * @return string
     */
    public function rJson($data = [], $type = true, $msg = 'ok')
    {
        echo json_encode(['type' => $type, 'msg' => $msg, 'data' => $data], JSON_UNESCAPED_UNICODE);
        exit();
    }

    /**
     * 返回适合dataTable的数据
     * @param array $data
     * @return string
     */
    public function rTableData($data = [])
    {
        echo json_encode(['total' => $data['total'], 'data' => $data['data']], JSON_UNESCAPED_UNICODE);
        exit();
    }

    /**
     * 重写goBack
     * @param string $msg
     * @return \yii\web\Response
     */
    public function goBack($msg = '')
    {
        Msg::set($msg);
        return parent::goBack(\Yii::$app->request->getReferrer());
    }
}