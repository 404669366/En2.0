<?php

namespace vendor\project\base;

use vendor\project\helpers\client;
use Yii;

/**
 * This is the model class for table "en_order".
 *
 * @property string $id
 * @property string $no 订单编号
 * @property string $pile 电桩编号
 * @property int $gun 电桩枪口号
 * @property string $uid 用户id
 * @property string $electricQuantity 充电电量
 * @property string $basisMoney 基础电费
 * @property string $serviceMoney 服务电费
 * @property string $duration 充电时长
 * @property string $created_at 创建时间
 */
class EnOrder extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'en_order';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['no'], 'unique'],
            [['no', 'pile', 'gun', 'uid'], 'required'],
            [['gun', 'uid', 'duration', 'created_at'], 'integer'],
            [['no', 'pile'], 'string', 'max' => 32],
            [['electricQuantity', 'basisMoney', 'serviceMoney'], 'number'],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'no' => '订单编号',
            'pile' => '电桩编号',
            'gun' => '电桩枪口号',
            'uid' => '用户id',
            'electricQuantity' => '充电电量',
            'basisMoney' => '基础电费',
            'serviceMoney' => '服务电费',
            'duration' => '充电时长',
            'created_at' => '创建时间',
        ];
    }

    /**
     * 获取用户订单
     * @param int $uid
     * @return array|\yii\db\ActiveRecord[]
     */
    public static function getOrders($uid = 0)
    {
        $uid = $uid ?: Yii::$app->user->id;
        $orders = self::find()->where(['uid' => $uid])
            ->orderBy('created_at desc')
            ->asArray()->all();
        foreach ($orders as &$v) {
            $v['created_at'] = date('Y-m-d H:i:s', $v['created_at']);
            $v['st'] = 2;
        }
        if ($order = self::getOnlineOrder()) {
            $order['st'] = 1;
            array_unshift($orders, $order);
        }
        $orders = [
            [
                'no' => '11111111',
                'pile' => '888',
                'gun' => '1',
                'uid' => 1,
                'status' => 1,
                'created_at' => date('Y-m-d H:i:s'),
                'soc' => 0,
                'power' => 0,
                'duration' => 0,
                'rule' => [0, 86400, 0.8, 0.6],
                'electricQuantity' => 10,
                'basisMoney' => 8,
                'serviceMoney' => 6,
                'st' => 1,
            ],
            [
                'no' => '222222222',
                'pile' => '888',
                'gun' => '1',
                'uid' => 1,
                'status' => 1,
                'created_at' => date('Y-m-d H:i:s'),
                'soc' => 0,
                'power' => 0,
                'duration' => 0,
                'rule' => [0, 86400, 0.8, 0.6],
                'electricQuantity' => 10,
                'basisMoney' => 8,
                'serviceMoney' => 6,
                'st' => 2,
            ],
            [
                'no' => '3333333333',
                'pile' => '888',
                'gun' => '1',
                'uid' => 1,
                'status' => 1,
                'created_at' => date('Y-m-d H:i:s'),
                'soc' => 0,
                'power' => 0,
                'duration' => 0,
                'rule' => [0, 86400, 0.8, 0.6],
                'electricQuantity' => 10,
                'basisMoney' => 8,
                'serviceMoney' => 6,
                'st' => 2,
            ],
        ];
        return $orders;
    }

    /**
     * 获取用户在线订单
     * @param int $uid
     * @return array|mixed
     */
    public static function getOnlineOrder($uid = 0)
    {
        $uid = $uid ?: Yii::$app->user->id;
        $userInfo = (new client())->hGet('UserInfo', $uid);
        if ($userInfo && isset($userInfo['order'])) {
            if ($order = (new client())->hGet('ChargeOrder', $userInfo['order'])) {
                return $order;
            }
        }
        return [];
    }
}
