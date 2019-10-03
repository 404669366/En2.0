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

    public function getPile()
    {
        return $this->hasOne(EnPile::class, ['no' => 'pile']);
    }

    /**
     * 获取当前用户订单
     * @return array|\yii\db\ActiveRecord[]
     */
    public static function getOrders()
    {
        $orders = self::find()->alias('o')
            ->leftJoin(EnPile::tableName() . ' p', 'p.no=o.pile')
            ->leftJoin(EnField::tableName() . ' f', 'f.id=p.field_id')
            ->where(['o.uid' => Yii::$app->user->id])
            ->select(['o.*', 'f.name'])
            ->orderBy('o.created_at desc')
            ->asArray()->all();
        foreach ($orders as &$v) {
            $v['electricQuantity'] = (float)$v['electricQuantity'];
            $v['money'] = $v['basisMoney'] + $v['serviceMoney'];
            $v['created_at'] = date('Y-m-d H:i:s', $v['created_at']);
            $v['st'] = 2;
        }
        if ($orderNo = Yii::$app->session->get('order', '')) {
            if ($order = (new client())->hGet('ChargeOrder', $orderNo)) {
                $order['st'] = 1;
                $order['electricQuantity'] = (float)$order['electricQuantity'];
                $order['money'] = $order['basisMoney'] + $order['serviceMoney'];
                $order['name'] = EnPile::findOne(['no' => $order['pile']])->local->name;
                array_unshift($orders, $order);
            }
        }
        return $orders;
    }
}
