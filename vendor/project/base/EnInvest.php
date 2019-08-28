<?php

namespace vendor\project\base;

use vendor\project\helpers\Helper;
use vendor\project\helpers\Wechat;
use Yii;

/**
 * This is the model class for table "en_invest".
 *
 * @property string $id
 * @property string $no 充值编号
 * @property string $uid 用户ID
 * @property string $money 充值金额
 * @property string $balance 当前余额
 * @property int $source 充值来源 1微信2支付宝3银联
 * @property int $status 充值状态0等待支付1支付成功2支付失败
 * @property string $created_at 创建时间
 */
class EnInvest extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'en_invest';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['no'], 'unique'],
            [['no', 'uid', 'money', 'source'], 'required'],
            [['uid', 'source', 'status', 'created_at'], 'integer'],
            [['no'], 'string', 'max' => 20],
            [['money', 'balance'], 'number'],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'no' => '充值编号',
            'uid' => '用户ID',
            'money' => '充值金额',
            'balance' => '当前余额',
            'source' => '充值来源 1微信2支付宝3银联',
            'status' => '充值状态0等待支付1支付成功2支付失败',
            'created_at' => '创建时间',
        ];
    }

    /**
     * 发起充值
     * @param int $money
     * @param int $way
     * @return array|bool|mixed
     */
    public static function invest($money = 0, $way = 1)
    {
        $model = new self();
        $model->no = Helper::createNo('I');
        $model->uid = Yii::$app->user->id;
        $model->money = $money;
        $model->balance = EnUser::getMoney();
        $model->source = $way;
        $model->created_at = time();
        if ($model->save()) {
            if ($way == 1) {
                if ($data = Wechat::jsPay($model->no, $money)) {
                    return $data;
                }
            }
        }
        return false;
    }
}
