<?php

namespace vendor\project\base;

use vendor\project\helpers\Constant;
use vendor\project\helpers\Helper;
use vendor\project\helpers\Msg;
use vendor\project\helpers\Wechat;
use Yii;
use yii\db\Exception;

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
            [['status'], 'validateStatus'],
            [['no'], 'string', 'max' => 32],
            [['money'], 'number'],
            [['remark'], 'string', 'max' => 255],
            [['no'], 'unique'],
        ];
    }

    public function validateStatus()
    {
        if ($this->status == 1 && $this->type == 3) {
            if (EnUser::getMoney($this->key) < $this->money) {
                $this->addError('status', '账户余额不足');
            }
        }
        if ($this->status == 2 && $this->type == 3) {
            if (!EnUser::cutMoney($this->key, $this->money)) {
                $this->status = 3;
                $this->remark = '余额不足';
                $this->save();
                $this->addError('status', '账户余额不足');
            }
        }
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
            'status' => '提现状态',
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
            ->select(['c.*', 'co.name as cName', 'u.tel as uTel', 'u.money as haveMoney'])
            ->page([
                'keywords' => ['like', 'c.no', 'co.name', 'u.tel'],
                'type' => ['=', 'c.type'],
                'status' => ['=', 'c.status']
            ]);
        foreach ($data['data'] as &$v) {
            $v['user'] = $v['uTel'];
            if ($v['type'] == 1) {
                $v['user'] = $v['cName'];
                $v['haveMoney'] = (string)EnIncome::getSurplus(2, 1, $v['key']);
            }
            if ($v['type'] == 2) {
                $v['haveMoney'] = (string)EnIncome::getSurplus([3, 4], 2, $v['key']);
            }
            $v['typeName'] = Constant::cashType()[$v['type']];
            $v['statusName'] = Constant::cashStatus()[$v['status']];
            $v['created_at'] = date('Y-m-d H:i:s', $v['created_at']);
        }
        return $data;
    }

    /**
     * 后台分页数据
     * @return mixed
     */
    public static function getPageDataByCompany()
    {
        $data = self::find()
            ->where(['type' => 1, 'key' => Yii::$app->user->identity->company_id])
            ->page([
                'keywords' => ['like', 'no'],
                'status' => ['=', 'status']
            ]);
        foreach ($data['data'] as &$v) {
            $v['statusName'] = Constant::cashStatus()[$v['status']];
            $v['created_at'] = date('Y-m-d H:i:s', $v['created_at']);
        }
        return $data;
    }

    /**
     * 我的提现
     * @param int $type [2,3]
     * @return array|\yii\db\ActiveRecord[]
     */
    public static function listDataByCenter($type = 2)
    {
        $data = self::find()->where(['type' => $type, 'key' => Yii::$app->user->id])
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
        if ($need < 0.3) {
            return '提现金额不小于0.3元';
        }
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

    /**
     * 用户提现
     * @param $need
     * @return string
     */
    public static function createBy3($need)
    {
        if ($need < 0.3) {
            Msg::set('提现金额不小于0.3元');
            return false;
        }
        if ($need > EnUser::getMoney()) {
            Msg::set('提现金额大于余额');
            return false;
        }
        if (self::findOne(['type' => 3, 'key' => Yii::$app->user->id, 'status' => 0])) {
            Msg::set('您已有提现进行中');
            return false;
        }
        if (EnOrder::findOne(['uid' => Yii::$app->user->id, 'status' => 2])) {
            Msg::set('您有未支付订单');
            return false;
        }
        $model = new self();
        $model->no = Helper::createNo('C');
        $model->type = 3;
        $model->key = Yii::$app->user->id;
        $model->money = $need;
        $model->created_at = time();
        if (!$model->save()) {
            Msg::set($model->errors());
            return false;
        }
        Msg::set('提现申请成功');
        return true;
    }

    /**
     * 提现操作
     * @param string $no
     * @return string
     */
    public static function refund($no = '')
    {
        if ($model = self::findOne(['no' => $no, 'status' => 1])) {
            if ($model->type == 1) {
                $model->status = 2;
                if ($model->save()) {
                    return '操作成功';
                }
                return $model->errors();
            }
            if ($model->type == 2 || $model->type == 3) {
                $transaction = Yii::$app->db->beginTransaction();
                try {
                    if (!$model->user->open_id) {
                        new Exception('拉取openid失败');
                    }
                    $model->status = 2;
                    var_dump($model->save());exit();
                    if (!$model->save()) {
                        new Exception($model->errors());
                    }
                    if (!Wechat::refund($model->no, $model->user->open_id, $model->money, Constant::cashType()[$model->type])) {
                        new Exception('付款失败');
                    }
                    $transaction->commit();
                    return '操作成功';
                } catch (Exception $e) {
                    $transaction->rollBack();
                    return $e->getMessage();
                }
            }
        }
        return '非法操作';
    }
}
