<?php
/**
 * Created by PhpStorm.
 * User: miku
 * Date: 2019/9/6
 * Time: 17:02
 */

namespace app\controllers\oam;


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
        return $this->rTableData(EnField::getPageData(0, [1, 2, 3, 4, 5]));
    }

    /**
     * 上线操作
     * @param string $no
     * @return \yii\web\Response
     */
    public function actionUp($no = '')
    {
        Msg::set('非法操作');
        if ($model = EnField::findOne(['no' => $no, 'status' => [1, 2, 3, 4, 5], 'online' => 1])) {
            $transaction = \Yii::$app->db->beginTransaction();
            try {
                $model->online = 2;
                $model->intro = '111';
                if ($model->save()) {
                    $point = Helper::bd09ToGcj02($model->lat, $model->lng);
                    $data = [
                        'key' => 'NZ7BZ-VWQHX-2XV4F-75J2W-UDF42-Q2BM2',
                        'table_id' => '5d490255d31eea5b7b36b922',
                        'data' => [
                            [
                                'ud_id' => $model->no,
                                'title' => $model->name,
                                'address' => $model->address,
                                'location' => [
                                    'lat' => (float)$point['lat'],
                                    'lng' => (float)$point['lng'],
                                ]
                            ]
                        ]
                    ];
                    $re = Helper::curlPost('https://apis.map.qq.com/place_cloud/data/create', $data, true);
                    $re = json_decode($re, true);
                    if ($re && $re['status'] == 0) {
                        $transaction->commit();
                        Msg::set('上线成功');
                        return $this->redirect(['list']);
                    }
                    throw new Exception('推送信息失败');
                }
                throw new Exception('状态更新失败');
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
        if ($model = EnField::findOne(['no' => $no, 'status' => [1, 2, 3, 4, 5], 'online' => 2])) {
            $transaction = \Yii::$app->db->beginTransaction();
            try {
                $model->online = 1;
                $model->intro = '111';
                if ($model->save()) {
                    $data = [
                        'key' => 'NZ7BZ-VWQHX-2XV4F-75J2W-UDF42-Q2BM2',
                        'table_id' => '5d490255d31eea5b7b36b922',
                        'filter' => 'ud_id in("' . $no . '")'
                    ];
                    $re = Helper::curlPost('https://apis.map.qq.com/place_cloud/data/delete', $data, true);
                    $re = json_decode($re, true);
                    if ($re && $re['status'] == 0) {
                        $transaction->commit();
                        Msg::set('下线成功');
                        return $this->redirect(['list']);
                    }
                    throw new Exception('推送信息失败');
                }
                throw new Exception('状态更新失败');
            } catch (Exception $e) {
                Msg::set($e->getMessage());
                $transaction->rollBack();
            }
        }
        return $this->redirect(['list']);
    }
}