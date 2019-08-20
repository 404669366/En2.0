<?php

namespace vendor\project\base;

use vendor\project\helpers\Constant;
use vendor\project\helpers\Helper;
use Yii;

/**
 * This is the model class for table "en_field_intention".
 *
 * @property string $id
 * @property string $no 意向编号
 * @property string $commissioner_id 专员ID
 * @property int $source 来源 1用户2专员
 * @property string $field_id 场站ID
 * @property string $user_id 用户ID
 * @property string $cobber_id 推荐用户ID
 * @property string $purchase_amount 认购金额
 * @property string $order_amount 定金金额
 * @property string $part_ratio 分成比例
 * @property string $voucher 打款凭条
 * @property string $contract 合同
 * @property string $remark 备注
 * @property int $status 状态 1待付定金2已付定金3审核凭证4审核通过5审核不通过6用户违约7放弃支付8申请退款9已退款10用户删除
 * @property string $pay_at 支付定金时间
 * @property string $created_at 创建时间
 */
class EnFieldIntention extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'en_field_intention';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['no'], 'unique'],
            [['status'], 'validateStatus'],
            [['commissioner_id', 'source', 'field_id', 'user_id', 'purchase_amount', 'created_at', 'no'], 'required'],
            [['commissioner_id', 'source', 'field_id', 'user_id', 'cobber_id', 'status', 'pay_at', 'created_at'], 'integer'],
            [['purchase_amount', 'order_amount', 'part_ratio'], 'number'],
            [['no'], 'string', 'max' => 20],
            [['voucher'], 'string', 'max' => 80],
            [['contract'], 'string', 'max' => 320],
            [['remark'], 'string', 'max' => 255],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'no' => '意向编号',
            'commissioner_id' => '专员ID',
            'source' => '来源 1用户2专员',
            'field_id' => '场站',
            'user_id' => '用户ID',
            'cobber_id' => '推荐用户ID',
            'purchase_amount' => '认购金额',
            'order_amount' => '定金金额',
            'part_ratio' => '分成比例',
            'voucher' => '打款凭条',
            'contract' => '合同',
            'remark' => '备注',
            'status' => '状态 1待付定金2已付定金3审核凭证4审核通过5审核不通过6用户违约7放弃支付8申请退款9已退款10用户删除',
            'pay_at' => '支付定金时间',
            'created_at' => '创建时间',
        ];
    }

    public function afterSave($insert, $changedAttributes)
    {
        if ($this->status == 2) {
            EnField::updatePresentAmount($this->field_id, $this->purchase_amount);
        }
        if (in_array($this->status, [6, 9])) {
            EnField::updatePresentAmount($this->field_id, $this->purchase_amount, 'cut');
        }
        if ($this->status == 3 && $this->source == 2) {
            EnField::updatePresentAmount($this->field_id, $this->purchase_amount);
        }
        if ($this->status == 5 && $this->source == 2) {
            EnField::updatePresentAmount($this->field_id, $this->purchase_amount, 'cut');
        }
    }

    public function validateStatus()
    {
        if (in_array($this->status, [1, 2, 3])) {
            $field = EnField::findOne(['status' => 4, 'id' => $this->field_id]);
            if ($this->status != 3 || $this->source != 1) {
                if (!$field) {
                    $this->addError('field_id', '该场站融资已结束');
                }
            }
            if ($this->status == 3) {
                if (!$this->voucher) {
                    $this->addError('voucher', '打款凭条不能为空');
                }
                if (!$this->contract) {
                    $this->addError('contract', '意向合同不能为空');
                }
                if ($this->source == 2 && $field) {
                    $surplus = $field->budget_amount - $field->present_amount;
                    if (!$this->purchase_amount) {
                        $this->addError('purchase_amount', '认购金额必须不小于0');
                    }
                    if ($this->purchase_amount % $field->lowest_amount != 0) {
                        $this->addError('purchase_amount', '认购金额必须是起投金额(' . $field->lowest_amount . ')的倍数');
                    }
                    if ($this->purchase_amount > $surplus) {
                        $this->addError('purchase_amount', '当前场站剩余最多可投' . $surplus);
                    }
                    $this->part_ratio = $this->purchase_amount / $field->budget_amount;
                    $this->order_amount = 0;
                }
            }
        }
    }

    public function getField()
    {
        return $this->hasOne(EnField::class, ['id' => 'field_id']);
    }

    public function getUser()
    {
        return $this->hasOne(EnUser::class, ['id' => 'user_id']);
    }

    public function getCobber()
    {
        return $this->hasOne(EnUser::class, ['id' => 'cobber_id']);
    }

    /**
     * 分页数据
     * @return mixed
     */
    public static function getPageData()
    {
        $data = self::find()->alias('i')
            ->leftJoin(EnField::tableName() . ' f', 'f.id=i.field_id')
            ->leftJoin(EnUser::tableName() . ' u1', 'u1.id=i.user_id')
            ->leftJoin(EnUser::tableName() . ' u2', 'u2.id=i.cobber_id')
            ->where(['i.commissioner_id' => Yii::$app->user->id])
            ->select([
                'f.no', 'u1.tel as uTel', 'u2.tel as cTel', 'i.purchase_amount', 'i.id',
                'i.order_amount', 'i.part_ratio', 'i.status', 'i.created_at', 'i.source',
                'i.pay_at'
            ])
            ->page([
                'content' => ['like', 'f.no', 'u1.tel', 'u2.tel'],
                'source' => ['=', 'i.source'],
                'status' => ['=', 'i.status'],
            ]);
        foreach ($data['data'] as &$v) {
            $v['source'] = Constant::intentionSource()[$v['source']];
            $v['status'] = Constant::intentionStatus()[$v['status']];
            $v['outTime'] = time() > ($v['pay_at'] + \vendor\project\helpers\Constant::orderBackTime());
        }
        return $data;
    }

    /**
     * 审核数据
     * @return mixed
     */
    public static function getExamineData()
    {
        $data = self::find()->alias('i')
            ->leftJoin(EnField::tableName() . ' f', 'f.id=i.field_id')
            ->leftJoin(EnUser::tableName() . ' u1', 'u1.id=i.user_id')
            ->leftJoin(EnUser::tableName() . ' u2', 'u2.id=i.cobber_id')
            ->where(['i.status' => [3, 4, 5]])
            ->select([
                'f.no', 'u1.tel as uTel', 'u2.tel as cTel', 'i.purchase_amount', 'i.id',
                'i.order_amount', 'i.part_ratio', 'i.status', 'i.created_at', 'i.source', 'i.pay_at'
            ])
            ->page([
                'content' => ['like', 'f.no', 'u1.tel', 'u2.tel'],
                'source' => ['=', 'i.source'],
                'status' => ['=', 'i.status'],
            ]);
        foreach ($data['data'] as &$v) {
            $v['source'] = Constant::intentionSource()[$v['source']];
            $v['status'] = Constant::intentionStatus()[$v['status']];
        }
        return $data;
    }


    /**
     * 退款审核数据
     * @return mixed
     */
    public static function getExamineBackData()
    {
        $data = self::find()->alias('i')
            ->leftJoin(EnField::tableName() . ' f', 'f.id=i.field_id')
            ->leftJoin(EnUser::tableName() . ' u1', 'u1.id=i.user_id')
            ->leftJoin(EnUser::tableName() . ' u2', 'u2.id=i.cobber_id')
            ->where(['i.status' => [8, 9]])
            ->select([
                'f.no', 'u1.tel as uTel', 'u2.tel as cTel', 'i.purchase_amount', 'i.id',
                'i.order_amount', 'i.part_ratio', 'i.status', 'i.created_at', 'i.source', 'i.pay_at'
            ])
            ->page([
                'content' => ['like', 'f.no', 'u1.tel', 'u2.tel'],
                'source' => ['=', 'i.source'],
                'status' => ['=', 'i.status'],
            ]);
        foreach ($data['data'] as &$v) {
            $v['source'] = Constant::intentionSource()[$v['source']];
            $v['status'] = Constant::intentionStatus()[$v['status']];
        }
        return $data;
    }

    /**
     * 返回用户意向
     * @return array|\yii\db\ActiveRecord[]
     */
    public static function getUserData()
    {
        $data = self::find()->alias('i')
            ->leftJoin(EnField::tableName() . ' f', 'f.id=i.field_id')
            ->where(['i.user_id' => Yii::$app->user->id, 'i.status' => [1, 2, 3, 4, 5, 6, 8, 9, 10]])
            ->select([
                'f.no', 'f.status as fStatus', 'i.id', 'i.purchase_amount', 'i.order_amount',
                'i.part_ratio', 'i.status', 'i.created_at', 'i.pay_at', 'f.images'
            ])
            ->asArray()->all();
        foreach ($data as &$v) {
            $v['fStatus'] = Constant::fieldStatus()[$v['fStatus']];
            $v['status_val'] = $v['status'];
            $v['status'] = Constant::intentionStatus()[$v['status']];
            $v['images'] = explode(',', $v['images'])[0];
            $v['pay_at'] = $v['pay_at'] ? date('Y-m-d H:i:s', $v['pay_at']) : '------';
            $v['created_at'] = date('Y-m-d H:i:s', $v['created_at']);
            $v['canBack'] = time() <= ($v['pay_at'] + Constant::orderBackTime());
        }
        return Helper::arraySort($data, 'status_val', SORT_ASC, 'created_at', SORT_DESC);
    }

    /**
     * 返回用户推荐意向
     * @return array|\yii\db\ActiveRecord[]
     */
    public static function getUserRData()
    {
        $data = self::find()->alias('i')
            ->leftJoin(EnField::tableName() . ' f', 'f.id=i.field_id')
            ->where(['i.cobber_id' => Yii::$app->user->id, 'i.status' => [1, 2, 3, 4, 5, 6, 8, 9]])
            ->select([
                'f.no', 'f.status as fStatus', 'i.id', 'i.purchase_amount', 'i.order_amount',
                'i.part_ratio', 'i.status', 'i.created_at', 'i.pay_at', 'f.images'
            ])
            ->asArray()->all();
        foreach ($data as &$v) {
            $v['fStatus'] = Constant::fieldStatus()[$v['fStatus']];
            $v['status_val'] = $v['status'];
            $v['status'] = Constant::intentionStatus()[$v['status']];
            $v['images'] = explode(',', $v['images'])[0];
            $v['pay_at'] = $v['pay_at'] ? date('Y-m-d H:i:s', $v['pay_at']) : '------';
            $v['created_at'] = date('Y-m-d H:i:s', $v['created_at']);
        }
        return Helper::arraySort($data, 'status_val', SORT_ASC, 'created_at', SORT_DESC);
    }

    /**
     * 场站搜索
     * @param string $no
     * @return bool|int
     */
    public static function fieldSearch($no = '')
    {
        if ($model = EnField::findOne(['no' => $no])) {
            return $model->id;
        }
        return false;
    }
}
