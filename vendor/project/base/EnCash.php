<?php

namespace vendor\project\base;

use vendor\project\helpers\Constant;
use vendor\project\helpers\Helper;
use Yii;

/**
 * This is the model class for table "en_cash".
 *
 * @property string $no 提现编号
 * @property int $type 提现类型 1企业提现2用户提现3余额提现
 * @property string $key 关联键(type=? 1企业ID2用户ID3用户ID)
 * @property string $money 提现金额
 * @property string $remark 备注
 * @property int $status 提现状态 0等待审核1审核通过2确认打款3驳回审核
 * @property string $created_at
 */
class EnCash extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'en_cash';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['no'], 'required'],
            [['type', 'key', 'status', 'created_at'], 'integer'],
            [['no'], 'string', 'max' => 32],
            [['money'], 'string', 'max' => 10],
            [['remark'], 'string', 'max' => 255],
            [['no'], 'unique'],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'no' => '提现编号',
            'type' => '提现类型 1企业提现2用户提现3余额提现',
            'key' => '关联键(type=? 1企业ID2用户ID3用户ID)',
            'money' => '提现金额',
            'remark' => '备注',
            'status' => '提现状态 0等待审核1审核通过2确认打款3驳回审核',
            'created_at' => 'Created At',
        ];
    }

    public function getCompany()
    {
        return $this->hasOne(EnCompany::class, ['id' => 'key']);
    }

    public function getUser()
    {
        return $this->hasOne(EnUser::class, ['id' => 'key']);
    }

    /**
     * 后台分页数据
     * @return mixed
     */
    public static function getPageData()
    {
        $data = self::find()->alias('c')
            ->leftJoin(EnCompany::tableName() . ' co', 'co.id=c.key')
            ->leftJoin(EnUser::tableName() . ' u', 'u.id=c.key')
            ->select(['c.*', 'co.name as cName', 'u.tel as uTel'])
            ->page([
                'keywords' => ['like', 'c.no', 'co.name', 'u.tel'],
                'type' => ['=', 'c.type'],
                'status' => ['=', 'c.status']
            ]);
        foreach ($data['data'] as &$v) {
            $v['typeName'] = Constant::cashType()[$v['type']];
            $v['user'] = $v['type'] == 1 ? $v['cName'] : $v['uTel'];
            $v['statusName'] = Constant::cashStatus()[$v['status']];
            $v['created_at'] = date('Y-m-d H:i:s', $v['created_at']);
        }
        return $data;
    }

    /**
     * 我的提现
     * @return array|\yii\db\ActiveRecord[]
     */
    public static function listDataByCenter()
    {
        $data = self::find()->where(['type' => 2, 'key' => Yii::$app->user->id])
            ->orderBy('created_at desc')
            ->asArray()->all();
        foreach ($data as &$v) {
            $v['statusName'] = Constant::cashStatus()[$v['status']];
            $v['created'] = date('Y-m-d H:i:s', $v['created_at']);
        }
        return $data;
    }

    /**
     * 用户提现
     * @param $need
     * @return string
     */
    public static function createBy2($need)
    {
        if ($last = self::findOne(['type' => 2, 'key' => Yii::$app->user->id, 'status' => 0])) {
            return '您已有进行中的提现';
        }
        $max = self::find()->where(['type' => 2, 'key' => Yii::$app->user->id, 'status' => [1, 2]])->max('created_at');
        if (date('Y-m', $max) == date('Y-m')) {
            return '本月您已发起过提现';
        }
        $have = EnIncome::getSurplus([3, 4], 2, Yii::$app->user->id);
        if ($need > $have) {
            return '提现金额大于本金';
        }
        $model = new self();
        $model->no = Helper::createNo('C');
        $model->type = 2;
        $model->key = Yii::$app->user->id;
        $model->money = $need;
        $model->created_at = time();
        if (!$model->save()) {
            return $model->errors();
        }
        return '提现申请成功';
    }
}
