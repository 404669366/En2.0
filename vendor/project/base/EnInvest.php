<?php

namespace vendor\project\base;

use vendor\project\helpers\Constant;
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
     * 后台分页数据
     * @return mixed
     */
    public static function getPageData()
    {
        $data = self::find()->alias('i')
            ->leftJoin(EnUser::tableName() . ' u', 'u.id=i.uid')
            ->select(['i.*', 'u.tel'])
            ->page([
                'keywords' => ['like', 'i.no', 'u.tel'],
                'status' => ['=', 'i.status'],
                'source' => ['=', 'i.source'],
            ]);
        foreach ($data['data'] as &$v) {
            $v['status'] = Constant::investStatus()[$v['status']];
            $v['source'] = Constant::investSource()[$v['source']];
            $v['created_at'] = date('Y-m-d H:i:s');
        }
        return $data;
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

    /**
     * 用户充值记录
     * @param int $uid
     * @return array|\yii\db\ActiveRecord[]
     */
    public static function getUserInvest($uid = 0)
    {
        $uid = $uid ?: Yii::$app->user->id;
        $data = self::find()->where(['uid' => $uid, 'status' => [1, 2]])
            ->select(['money', 'balance', 'source', 'status', 'created_at'])
            ->orderBy('created_at desc')
            ->asArray()->all();
        foreach ($data as &$v) {
            $v['source'] = Constant::investSource()[$v['source']];
            $v['status'] = Constant::investStatus()[$v['status']];
            $v['created_at'] = date('Y-m-d H:i:s', $v['created_at']);
        }
        return $data;
    }
}
