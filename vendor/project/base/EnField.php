<?php

namespace vendor\project\base;

use vendor\project\helpers\Constant;
use vendor\project\helpers\Msg;
use Yii;

/**
 * This is the model class for table "en_field".
 *
 * @property int $id
 * @property string $commissioner_id 专员ID
 * @property string $local_id 场地方ID
 * @property string $cobber_id 场地推荐用户ID
 * @property int $source 来源 1平台2专员
 * @property string $no 场站编号
 * @property int $business_type 业务类型 1新建场站2转手场站
 * @property int $invest_type 投资类型 1托管运营2保底运营3保底回购
 * @property string $invest_ratio 投资方分成占比
 * @property string $field_ratio 场地方分成占比
 * @property string $name 场站名称
 * @property string $title 场站标题
 * @property string $trait 场站特色
 * @property string $images 场站图片
 * @property string $lng 经度
 * @property string $lat 纬度
 * @property string $address 场站位置
 * @property string $field_configure 场站配置
 * @property string $budget_amount 预算金额
 * @property string $lowest_amount 起投金额
 * @property string $present_amount 现投金额
 * @property string $field_contract 场地合同
 * @property string $field_prove 场站证明
 * @property string $record_file 备案文件
 * @property string $field_drawing 施工图纸
 * @property string $transformer_drawing 变压器图纸
 * @property string $power_contract 电力合同
 * @property string $remark 备注
 * @property int $status 场站状态 1挂起2审核中3审核不通过4正在融资5融资完成
 * @property string $created_at 创建时间
 */
class EnField extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'en_field';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['no', 'name'], 'unique'],
            [['status'], 'validateStatus'],
            [['no', 'commissioner_id', 'budget_amount', 'lowest_amount', 'business_type', 'invest_type', 'invest_ratio', 'lng', 'lat', 'name', 'title', 'address', 'images', 'field_configure', 'status', 'trait'], 'required'],
            [['commissioner_id', 'business_type', 'invest_type', 'status', 'created_at', 'source', 'local_id', 'cobber_id'], 'integer'],
            [['no', 'invest_ratio', 'field_ratio', 'lng', 'lat'], 'string', 'max' => 20],
            [['name', 'title'], 'string', 'max' => 30],
            [['images'], 'string', 'max' => 400],
            [['address'], 'string', 'max' => 60],
            [['field_configure'], 'string', 'max' => 500],
            [['budget_amount', 'lowest_amount', 'present_amount'], 'string', 'max' => 10],
            [['field_prove', 'field_drawing', 'transformer_drawing', 'power_contract', 'field_contract'], 'string', 'max' => 240],
            [['record_file'], 'string', 'max' => 80],
            [['remark', 'trait'], 'string', 'max' => 255],
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
            'local_id' => '场地方ID',
            'cobber_id' => '场地推荐用户ID',
            'source' => '来源 1平台2专员',
            'no' => '场站编号',
            'business_type' => '业务类型 1新建场站2转手场站',
            'invest_type' => '投资类型 1托管运营2保底运营3保底回购',
            'invest_ratio' => '投资方分成占比',
            'field_ratio' => '场地方分成占比',
            'name' => '场站名称',
            'title' => '场站标题',
            'trait' => '场站特色',
            'images' => '场站图片',
            'lng' => '经度',
            'lat' => '纬度',
            'address' => '场站位置',
            'field_configure' => '场站配置',
            'budget_amount' => '预算金额',
            'lowest_amount' => '起投金额',
            'present_amount' => '现投金额',
            'field_contract' => '场地合同',
            'field_prove' => '场站证明',
            'record_file' => '备案文件',
            'field_drawing' => '施工图纸',
            'transformer_drawing' => '变压器图纸',
            'power_contract' => '电力合同',
            'remark' => '备注',
            'status' => '场站状态 1挂起2审核中3审核不通过4正在融资5融资完成',
            'created_at' => '创建时间',
        ];
    }

    public function validateStatus()
    {
        if ($this->source == 1) {
            if (!$this->local_id) {
                $this->addError('local_id', '拉取场地方信息错误');
            }
        }
        if ($this->status == 2) {
            if (!$this->field_contract) {
                $this->addError('field_contract', '场地合同不能为空');
            }
            if (!$this->field_prove) {
                $this->addError('field_prove', '场站证明不能为空');
            }
            if (!$this->record_file) {
                $this->addError('record_file', '备案文件不能为空');
            }
            if (!$this->field_drawing) {
                $this->addError('field_drawing', '施工图纸不能为空');
            }
            if (!$this->transformer_drawing) {
                $this->addError('transformer_drawing', '变压器图纸不能为空');
            }
            if (!$this->power_contract) {
                $this->addError('power_contract', '电力合同不能为空');
            }
        }
        if ($this->status == 3) {
            if (!$this->remark) {
                $this->addError('remark', '备注不能为空');
            }
        }
    }

    /**
     * 关联场地方
     * @return \yii\db\ActiveQuery
     */
    public function getLocal()
    {
        return $this->hasOne(EnUser::class, ['id' => 'local_id']);
    }

    /**
     * 关联推荐用户
     * @return \yii\db\ActiveQuery
     */
    public function getCobber()
    {
        return $this->hasOne(EnUser::class, ['id' => 'cobber_id']);
    }

    /**
     * 返回分页数据
     * @return mixed
     */
    public static function getPageData()
    {
        $data = self::find()->alias('f')
            ->leftJoin(EnUser::tableName() . ' u1', 'u1.id=f.local_id')
            ->leftJoin(EnUser::tableName() . ' u2', 'u2.id=f.cobber_id')
            ->where(['f.commissioner_id' => Yii::$app->user->id])
            ->select([
                'f.id', 'f.no', 'f.business_type', 'f.invest_type', 'f.status',
                'f.name', 'f.title', 'f.address', 'f.created_at', 'u1.tel as uTel',
                'u2.tel as cTel', 'f.source', 'f.budget_amount', 'f.present_amount',
                'f.lowest_amount'
            ])
            ->page([
                'content' => ['like', 'f.name', 'f.title', 'f.address', 'f.no', 'u1.tel', 'u2.tel'],
                'business_type' => ['=', 'f.business_type'],
                'invest_type' => ['=', 'f.invest_type'],
                'status' => ['=', 'f.status'],
                'source' => ['=', 'f.source'],
            ]);
        foreach ($data['data'] as &$v) {
            $v['business_type'] = Constant::businessType()[$v['business_type']];
            $v['invest_type'] = Constant::investType()[$v['invest_type']];
            $v['status'] = Constant::fieldStatus()[$v['status']];
            $v['source'] = Constant::fieldSource()[$v['source']];
            $v['info'] = '预算金额 : ' . $v['budget_amount'] . '<br/>起投金额 : ' . $v['lowest_amount'] . '<br/>现投金额 : ' . $v['present_amount'];
        }
        return $data;
    }

    /**
     * 返回分页数据
     * @return mixed
     */
    public static function getExamineData()
    {
        $data = self::find()->alias('f')
            ->leftJoin(EnUser::tableName() . ' u1', 'u1.id=f.local_id')
            ->leftJoin(EnUser::tableName() . ' u2', 'u2.id=f.cobber_id')
            ->where(['f.status' => [2, 3, 4, 5]])
            ->select([
                'f.id', 'f.no', 'f.business_type', 'f.invest_type', 'f.status',
                'f.name', 'f.title', 'f.address', 'f.created_at', 'u1.tel as uTel',
                'u2.tel as cTel', 'f.source', 'f.budget_amount', 'f.present_amount'
            ])
            ->page([
                'content' => ['like', 'f.name', 'f.title', 'f.address', 'f.no', 'u1.tel', 'u2.tel'],
                'business_type' => ['=', 'f.business_type'],
                'invest_type' => ['=', 'f.invest_type'],
                'status' => ['=', 'f.status'],
                'source' => ['=', 'f.source'],
            ]);
        foreach ($data['data'] as &$v) {
            $v['business_type'] = Constant::businessType()[$v['business_type']];
            $v['invest_type'] = Constant::investType()[$v['invest_type']];
            $v['status'] = Constant::fieldStatus()[$v['status']];
            $v['source'] = Constant::fieldSource()[$v['source']];
            $v['info'] = '预算金额 : ' . $v['budget_amount'] . '<br/>现投金额 : ' . $v['present_amount'];
        }
        return $data;
    }

    /**
     * 用户搜索
     * @param string $tel
     * @return bool|string
     */
    public static function userSearch($tel = '')
    {
        if ($user = EnUser::findOne(['tel' => $tel])) {
            return $user->id;
        }
        return false;
    }

    /**
     * 场站融资金额操作
     * @param int $id
     * @param string $do add/cut
     * @param int $amount
     * @return bool
     */
    public static function updatePresentAmount($id = 0, $do = 'add', $amount = 0)
    {
        Msg::set('错误操作');
        if ($id && $do && $amount) {
            if ($do == 'add') {
                Msg::set('融资已结束');
                if ($model = self::findOne(['status' => 4, 'id' => $id])) {
                    Msg::set('起投资金最少为' . $model->lowest_amount);
                    if ($amount >= $model->lowest_amount) {
                        $present_amount = $amount + (int)$model->present_amount;
                        Msg::set('本次项目最高融资' . $model->budget_amount);
                        if ($present_amount <= $model->budget_amount) {
                            $model->present_amount = (string)$present_amount;
                            if ($present_amount == $model->budget_amount) {
                                $model->status = 5;
                            }
                            return $model->save();
                        }
                    }
                }
            }
            if ($do == 'cut') {
                if ($model = self::findOne(['status' => [4, 5], 'id' => $id])) {
                    if ($amount <= $model->present_amount) {
                        $model->present_amount = (string)($model->present_amount - $amount);
                        if ($model->status == 5) {
                            $model->status = 4;
                        }
                        return $model->save();
                    }
                }
            }
        }
        return false;
    }

    /**
     * 首页推荐数据
     * @param int $length
     * @return array|\yii\db\ActiveRecord[]
     */
    public static function indexData($length = 6)
    {
        $data = self::find()
            ->orderBy('created_at desc')
            ->limit($length)
            ->asArray()->all();

        return $data;
    }

    /**
     * 列表页数据
     * @return array|\yii\db\ActiveQuery|\yii\db\ActiveRecord[]
     */
    public static function listData()
    {
        $pageNum = Yii::$app->request->get('pageNum', 1);
        $invest_type = Yii::$app->request->get('invest_type', '');
        $business_type = Yii::$app->request->get('business_type', '');
        $data = self::find();
        if ($invest_type) {
            $data->where(['invest_type' => $invest_type]);
        }
        if ($business_type) {
            $data->andWhere(['business_type' => $business_type]);
        }
        $data = $data->orderBy('created_at desc')
            ->offset(($pageNum - 1) * 6)->limit(6)
            ->asArray()->all();
        return $data;
    }
}
