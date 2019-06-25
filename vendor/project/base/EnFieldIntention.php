<?php

namespace vendor\project\base;

use vendor\project\helpers\Constant;
use Yii;

/**
 * This is the model class for table "en_field_intention".
 *
 * @property string $id
 * @property string $commissioner_id 专员ID
 * @property int $source 来源 1用户2平台
 * @property string $field_id 场站ID
 * @property string $user_id 用户ID
 * @property string $cobber_id 推荐用户ID
 * @property string $purchase_amount 认购金额
 * @property string $order_amount 定金金额
 * @property string $part_ratio 分成比例
 * @property string $voucher 打款凭条
 * @property string $contract 合同
 * @property string $remark 备注
 * @property int $status 状态 1待付定金2已付定金3审核中4审核通过5审核不通过6用户违约
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
            [['status'], 'validateStatus'],
            [['commissioner_id', 'source', 'field_id', 'user_id', 'purchase_amount', 'part_ratio', 'created_at'], 'required'],
            [['commissioner_id', 'source', 'field_id', 'user_id', 'cobber_id', 'status', 'created_at'], 'integer'],
            [['purchase_amount', 'order_amount'], 'string', 'max' => 10],
            [['part_ratio'], 'string', 'max' => 20],
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
            'commissioner_id' => '专员ID',
            'source' => '来源 1用户2平台',
            'field_id' => '场站ID',
            'user_id' => '用户ID',
            'cobber_id' => '推荐用户ID',
            'purchase_amount' => '认购金额',
            'order_amount' => '定金金额',
            'part_ratio' => '分成比例',
            'voucher' => '打款凭条',
            'contract' => '合同',
            'remark' => '备注',
            'status' => '状态 1待付定金2已付定金3审核中4审核通过5审核不通过6用户违约',
            'created_at' => '创建时间',
        ];
    }

    public function validateStatus()
    {
        if ($this->status == 3) {
            if (!$this->voucher) {
                $this->addError('voucher', '打款凭条不能为空');
            }
            if (!$this->contract) {
                $this->addError('contract', '意向合同不能为空');
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
                'i.order_amount', 'i.part_ratio', 'i.status', 'i.created_at', 'i.source'
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
                'i.order_amount', 'i.part_ratio', 'i.status', 'i.created_at', 'i.source'
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
     * 场站搜索
     * @param string $no
     * @return bool|int
     */
    public static function fieldSearch($no = '')
    {
        if ($model = EnField::findOne(['no' => $no, 'status' => 4])) {
            return $model->id;
        }
        return false;
    }
}
