<?php

namespace vendor\project\base;

use vendor\project\helpers\Constant;
use vendor\project\helpers\Helper;
use vendor\project\helpers\Msg;
use Yii;

/**
 * This is the model class for table "en_intention".
 *
 * @property string $id
 * @property int $source 来源 1用户2平台
 * @property string $commissioner_id 专员ID
 * @property string $field 场站
 * @property string $user 用户
 * @property string $no 意向编号
 * @property string $amount 认购金额
 * @property string $ratio 分成比例
 * @property string $voucher 打款凭条
 * @property string $contract 合同
 * @property string $remark 备注
 * @property int $status 状态 1等待沟通2合同签署3合同审核4等待打款5打款审核6审核通过7用户违约
 * @property int $delete 用户删除状态 1未删除2已删除
 * @property string $created_at 创建时间
 */
class EnIntention extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'en_intention';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['no'], 'unique'],
            [['status'], 'validateStatus'],
            [['source', 'field', 'user', 'no'], 'required'],
            [['user'], 'validateUser'],
            [['source', 'commissioner_id', 'status', 'delete', 'created_at'], 'integer'],
            [['amount', 'ratio'], 'number'],
            [['no', 'field', 'user'], 'string', 'max' => 20],
            [['voucher'], 'string', 'max' => 80],
            [['contract'], 'string', 'max' => 320],
            [['remark'], 'string', 'max' => 255],
        ];
    }

    public function validateStatus()
    {
        $oldStatus = array_key_exists('status', $this->oldAttributes) ? (int)$this->oldAttributes['status'] : 1;
        if (in_array($this->status, [2, 3])) {
            $field = EnField::findOne(['status' => [4, 5], 'no' => $this->field]);
            if (!$this->contract) {
                $this->addError('contract', '意向合同不能为空');
                $this->status = $oldStatus;
            }
            $oldAmount = array_key_exists('amount', $this->oldAttributes) ? (int)$this->oldAttributes['amount'] : 0;
            $surplus = (int)$field->budget_amount - (int)$field->present_amount + $oldAmount;
            if (!$this->amount) {
                $this->addError('amount', '认购金额必须大于0');
                $this->status = $oldStatus;
            }
            if ($this->amount % $field->lowest_amount != 0) {
                $this->addError('amount', '认购金额必须是起投金额( ' . $field->lowest_amount . ' )的倍数');
                $this->status = $oldStatus;
            }
            if ($this->amount > $surplus) {
                $this->addError('amount', '当前场站剩余可投' . $surplus);
                $this->status = $oldStatus;
            }
            $this->ratio = $this->amount / $field->budget_amount;
        }
        if ($this->status == 5) {
            if (!$this->voucher) {
                $this->addError('voucher', '打款凭条不能为空');
                $this->status = $oldStatus;
            }
        }
    }

    public function validateUser()
    {
        if ($this->isNewRecord) {
            if (self::findOne(['user' => $this->user, 'field' => $this->field, 'status' => [1, 2, 3, 4, 5, 6]])) {
                $this->addError('user', '该用户已投资过该项目');
            }
        } else {
            if (self::find()->where(['user' => $this->user, 'field' => $this->field, 'status' => [1, 2, 3, 4, 5, 6]])->andWhere(['<>', 'id', $this->id])->one()) {
                $this->addError('user', '该用户已投资过该项目');
            }
        }
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'source' => '来源 1用户2平台',
            'commissioner_id' => '专员ID',
            'field' => '场站',
            'user' => '意向用户',
            'no' => '意向编号',
            'amount' => '认购金额',
            'ratio' => '分成比例',
            'voucher' => '打款凭条',
            'contract' => '合同',
            'remark' => '备注',
            'status' => '状态 1等待沟通2合同签署3合同审核4等待打款5打款审核6审核通过7用户违约',
            'delete' => '用户删除状态 1未删除2已删除',
            'created_at' => '创建时间',
        ];
    }

    public function getFieldInfo()
    {
        return $this->hasOne(EnField::class, ['no' => 'field']);
    }

    public function getUserInfo()
    {
        return $this->hasOne(EnUser::class, ['tel' => 'user']);
    }

    public function afterSave($insert, $changedAttributes)
    {
        EnField::updatePresentAmount($this->field);
        if (!EnUser::findOne(['tel' => $this->user])) {
            $local = new EnUser();
            $local->tel = $this->user;
            $local->token = \Yii::$app->security->generatePasswordHash($this->user);
            $local->created_at = time();
            $local->save();
        }
    }

    /**
     * 分页数据
     * @param array $status
     * @param int $commissioner_id
     * @return $this|mixed
     */
    public static function getPageData($commissioner_id = 0, $status = [])
    {
        $data = self::find();
        if ($commissioner_id) {
            $data->andWhere(['commissioner_id' => $commissioner_id]);
        }
        if ($status) {
            $data->andWhere(['status' => $status]);
        }
        $data = $data->page([
            'content' => ['like', 'no', 'field', 'user'],
            'source' => ['=', 'source'],
            'status' => ['=', 'status'],
            'delete' => ['=', 'delete'],
        ]);
        foreach ($data['data'] as &$v) {
            $v['source'] = Constant::intentionSource()[$v['source']];
            $v['status'] = Constant::intentionStatus()[$v['status']];
            $v['delete'] = Constant::intentionDelete()[$v['delete']];
        }
        return $data;
    }

    /**
     * 意向抢单
     * @return bool
     */
    public static function robIntention()
    {
        $ids = self::find()->alias('i')
            ->leftJoin(EnField::tableName() . ' f', 'f.no=i.field')
            ->leftJoin(EnMember::tableName() . ' m', 'f.commissioner_id=m.id')
            ->where(['i.status' => 1, 'i.commissioner_id' => 0, 'm.company_id' => Yii::$app->user->identity->company_id])
            ->select(['i.id'])->asArray()->all();
        if ($ids) {
            $ids = array_column($ids, 'id');
            if ($intention = self::findOne(['id' => $ids[array_rand($ids)], 'status' => 1, 'commissioner_id' => 0])) {
                $intention->commissioner_id = Yii::$app->user->id;
                $intention->save(false);
                Msg::set('抢单成功');
                return true;
            }
            Msg::set('手慢了啦!!!');
            return false;
        }
        Msg::set('已经没有待处理信息了!!!');
        return false;
    }

    /**
     * 获取待抢意向数量
     * @return int|string
     */
    public static function getRobCount()
    {
        return self::find()->alias('i')
            ->leftJoin(EnField::tableName() . ' f', 'f.no=i.field')
            ->leftJoin(EnMember::tableName() . ' m', 'f.commissioner_id=m.id')
            ->where(['i.status' => 1, 'i.commissioner_id' => 0, 'm.company_id' => Yii::$app->user->identity->company_id])
            ->count();
    }

    /**
     * 返回用户意向
     * @return array|\yii\db\ActiveRecord[]
     */
    public static function getUserData()
    {
        $data = self::find()->alias('i')
            ->leftJoin(EnField::tableName() . ' f', 'f.no=i.field')
            ->where(['i.user' => Yii::$app->user->identity->tel, 'i.status' => [1, 2, 3, 4, 5, 6]])
            ->select([
                'f.no', 'f.status as fStatus', 'i.id', 'i.amount',
                'i.ratio', 'i.status', 'i.created_at', 'f.images'
            ])
            ->asArray()->all();
        foreach ($data as &$v) {
            $v['fStatus'] = Constant::fieldStatus()[$v['fStatus']];
            $v['status_val'] = $v['status'];
            $v['status'] = Constant::intentionStatus()[$v['status']];
            $v['images'] = explode(',', $v['images'])[0];
            $v['created_at'] = date('Y-m-d H:i:s', $v['created_at']);
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
}
