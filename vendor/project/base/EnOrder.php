<?php

namespace vendor\project\base;

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
}
