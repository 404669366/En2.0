<?php
/**
 * Created by PhpStorm.
 * User: miku
 * Date: 2019/9/6
 * Time: 17:02
 */

namespace app\controllers\pile;


use app\controllers\basis\CommonController;
use vendor\project\base\EnField;
use vendor\project\helpers\Helper;
use vendor\project\helpers\Msg;
use yii\db\Exception;

class FieldController extends CommonController
{
    /**
     * 场站上线管理列表
     * @return string
     */
    public function actionList()
    {
        return $this->render('list');
    }

    /**
     * 场站上线管理数据
     * @return string
     */
    public function actionData()
    {
        return $this->rTableData(EnField::getFieldByFinish());
    }

    /**
     * 上线操作
     * @param string $no
     * @return \yii\web\Response
     */
    public function actionUp($no = '')
    {
        Msg::set('非法操作');
        if ($model = EnField::findOne(['no' => $no, 'status' => 5, 'online' => 1])) {
            $transaction = \Yii::$app->db->beginTransaction();
            try {
                $data = [
                    'key' => 'NZ7BZ-VWQHX-2XV4F-75J2W-UDF42-Q2BM2',
                    'table_id' => '5d490255d31eea5b7b36b922',
                    'data' => [
                        [
                            'ud_id' => $model->no,
                            'title' => $model->name,
                            'address' => $model->address,
                            'location' => [
                                'lat' => (float)$model->lat,
                                'lng' => (float)$model->lng,
                            ]
                        ]
                    ]
                ];
                $re = Helper::curlPost('https://apis.map.qq.com/place_cloud/data/create', $data, true);
                $re = json_decode($re, true);
                if (!$re || $re['status'] != 0) {
                    throw new Exception('推送信息失败');
                }
                $model->online = 2;
                if (!$model->save()) {
                    throw new Exception('状态更新失败');
                }
                Msg::set('上线成功');
                $transaction->commit();
            } catch (Exception $e) {
                Msg::set($e->getMessage());
                $transaction->rollBack();
            }
        }
        return $this->redirect(['list']);
    }

    /**
     * 下线操作
     * @param string $no
     * @return \yii\web\Response
     */
    public function actionDown($no = '')
    {
        Msg::set('非法操作');
        if ($model = EnField::findOne(['no' => $no, 'status' => 5, 'online' => 2])) {
            $transaction = \Yii::$app->db->beginTransaction();
            try {
                $data = [
                    'key' => 'NZ7BZ-VWQHX-2XV4F-75J2W-UDF42-Q2BM2',
                    'table_id' => '5d490255d31eea5b7b36b922',
                    'filter' => 'ud_id=' . $no
                ];
                $re = Helper::curlPost('https://apis.map.qq.com/place_cloud/data/delete', $data, true);
                $re = json_decode($re, true);
                if (!$re || $re['status'] != 0) {
                    throw new Exception('推送信息失败');
                }
                $model->online = 1;
                if (!$model->save()) {
                    throw new Exception('状态更新失败');
                }
                Msg::set('下线成功');
                $transaction->commit();
            } catch (Exception $e) {
                Msg::set($e->getMessage());
                $transaction->rollBack();
            }
        }
        return $this->redirect(['list']);
    }
}