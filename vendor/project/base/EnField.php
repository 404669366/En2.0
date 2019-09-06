<?php

namespace vendor\project\base;

use vendor\project\helpers\client;
use vendor\project\helpers\Constant;
use vendor\project\helpers\Helper;
use vendor\project\helpers\Msg;
use Yii;

/**
 * This is the model class for table "en_field".
 *
 * @property int $id
 * @property string $commissioner_id 专员ID
 * @property int $business_type 业务类型 1新建场站2转手场站
 * @property int $invest_type 投资类型 1托管运营2保底运营3保底回购4第三方
 * @property string $invest_ratio 投资方总收益占比
 * @property string $field_ratio 场地方总收益占比
 * @property string $local_id 场地方ID
 * @property string $cobber_id 场地推荐用户ID
 * @property string $cobberTel 推荐用户
 * @property string $no 场站编号
 * @property string $name 场站名称
 * @property string $title 场站标题
 * @property string $trait 场站特色
 * @property string $intro 场站介绍
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
 * @property int $status 场站状态 0待处理1挂起2审核中3审核不通过4正在融资5融资完成6用户删除
 * @property int $source 来源 1用户发布2平台专员3三方专员
 * @property int $online 上线状态 1未上线2已上线
 * @property string $created 创建用户
 * @property string $created_at 创建时间
 */
class EnField extends \yii\db\ActiveRecord
{
    public $intro;

    public $cobberTel;

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
            [['lng', 'lat'], 'validateAddress'],
            [['status'], 'validateStatus'],
            [['commissioner_id', 'business_type', 'invest_type', 'local_id', 'cobber_id', 'status', 'source', 'created', 'created_at'], 'integer'],
            [['invest_ratio', 'field_ratio', 'no', 'lng', 'lat'], 'string', 'max' => 20],
            [['name', 'title'], 'string', 'max' => 30],
            [['trait', 'remark'], 'string', 'max' => 255],
            [['images'], 'string', 'max' => 400],
            [['address'], 'string', 'max' => 60],
            [['field_configure'], 'string', 'max' => 500],
            [['budget_amount', 'lowest_amount', 'present_amount', 'online'], 'number'],
            [['field_contract', 'field_prove', 'field_drawing', 'transformer_drawing', 'power_contract'], 'string', 'max' => 240],
            [['record_file'], 'string', 'max' => 80],
            [['intro', 'cobberTel'], 'string'],
        ];
    }

    public function validateAddress()
    {
        if ($this->isNewRecord) {
            if (self::find()->where(['lng' => $this->lng, 'lat' => $this->lat])->one()) {
                $this->addError('address', '该位置已被发布');
            }
            if (self::find()->where(['address' => $this->address])->one()) {
                $this->addError('address', '该位置已被发布');
            }
        }
    }

    public function validateStatus()
    {
        if (in_array($this->status, [0, 1, 2])) {
            if (!$this->lng) {
                $this->addError('lng', '请标注场地位置');
            }
            if (!$this->lat) {
                $this->addError('lat', '请标注场地位置');
            }
            if (!$this->address) {
                $this->addError('address', '请填写场地地址');
            }
            if (!$this->images) {
                $this->addError('images', '请上传场地图片');
            }
            if ($this->source == 1) {
                if ($this->cobberTel) {
                    if ($user = EnUser::findOne(['tel' => $this->cobberTel])) {
                        if ($user->id != $this->created) {
                            $this->cobber_id = $user->id;
                        } else {
                            $this->addError('cobberTel', '推荐用户不能填写自己');
                        }
                    } else {
                        $this->addError('cobberTel', '推荐用户不存在,请检查手机号');
                    }
                }
            }
        }
        if (in_array($this->status, [1, 2])) {
            if (!$this->commissioner_id) {
                $this->addError('commissioner_id', '专拉取员错误');
            }
            if (!$this->business_type) {
                $this->addError('business_type', '请选择业务类型');
            }
            if (!$this->name) {
                $this->addError('name', '请填写场站名称');
            }
            if (!$this->title) {
                $this->addError('title', '请填写场站标题');
            }
            if (!$this->trait) {
                $this->addError('trait', '请填写场站特色');
            }
            if (!$this->intro) {
                $this->addError('intro', '请编写场站介绍');
            }
            if (!$this->field_configure) {
                $this->addError('field_configure', '请填写场站配置');
            } else {
                $this->field_configure = str_replace("\r\n", '<br>', $this->field_configure);
            }
            if (!$this->budget_amount) {
                $this->addError('budget_amount', '请填写预算金额');
            }
            if (!$this->lowest_amount) {
                $this->addError('lowest_amount', '请填写起投金额');
            }
            if (($this->budget_amount % $this->lowest_amount) != 0) {
                $this->addError('lowest_amount', '起投金额必须能被预算金额整除');
            }
            if ($this->source != 3) {
                if (!$this->invest_type) {
                    $this->addError('invest_type', '请选择投资类型');
                }
                if (!$this->invest_ratio) {
                    $this->addError('invest_ratio', '请填写投资方总收益占比');
                }
            }
        }
        if (in_array($this->status, [2])) {
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
        if (in_array($this->status, [3])) {
            if (!$this->remark) {
                $this->addError('remark', '备注不能为空');
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
            'commissioner_id' => '专员ID',
            'business_type' => '业务类型 1新建场站2转手场站',
            'invest_type' => '投资类型 1托管运营2保底运营3保底回购4第三方',
            'invest_ratio' => '投资方总收益占比',
            'field_ratio' => '场地方总收益占比',
            'local_id' => '场地方ID',
            'cobber_id' => '场地推荐用户ID',
            'cobberTel' => '推荐用户',
            'no' => '场站编号',
            'name' => '场站名称',
            'title' => '场站标题',
            'trait' => '场站特色',
            'intro' => '场站介绍',
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
            'status' => '场站状态 0待处理1挂起2审核中3审核不通过4正在融资5融资完成6用户删除',
            'source' => '来源 1用户发布2平台专员3三方专员',
            'online' => '上线状态 1未上线2已上线',
            'created' => '创建用户',
            'created_at' => '创建时间',
        ];
    }

    public function afterSave($insert, $changedAttributes)
    {
        if (in_array($this->status, [1, 2])) {
            Yii::$app->cache->set('FieldIntro-' . $this->id, $this->intro);
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
     * 返回公司专员分页数据
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
                'f.lowest_amount', 'f.invest_ratio', 'f.field_ratio'
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
            $v['ratio'] = '投资方总收益占比 : ' . $v['invest_ratio'] . '<br/>场地方总收益占比 : ' . $v['field_ratio'];
            $v['info'] = '预算金额 : ' . $v['budget_amount'] . '<br/>起投金额 : ' . $v['lowest_amount'] . '<br/>现投金额 : ' . $v['present_amount'];
        }
        return $data;
    }

    /**
     * 返回三方专员分页数据
     * @return mixed
     */
    public static function getThirdData()
    {
        $data = self::find()
            ->where(['commissioner_id' => Yii::$app->user->id])
            ->select([
                'id', 'no', 'business_type', 'invest_type', 'status',
                'name', 'title', 'address', 'created_at', 'source',
                'budget_amount', 'present_amount', 'lowest_amount',
            ])
            ->page([
                'content' => ['like', 'name', 'title', 'address', 'no'],
                'business_type' => ['=', 'business_type'],
                'status' => ['=', 'status'],
            ]);
        foreach ($data['data'] as &$v) {
            $v['business_type'] = Constant::businessType()[$v['business_type']];
            $v['status'] = Constant::fieldStatus()[$v['status']];
            $v['info'] = '预算金额 : ' . $v['budget_amount'] . '<br/>起投金额 : ' . $v['lowest_amount'] . '<br/>现投金额 : ' . $v['present_amount'];
        }
        return $data;
    }

    /**
     * 返回场站审核分页数据
     * @return mixed
     */
    public static function getExamineData()
    {
        $data = self::find()->alias('f')
            ->leftJoin(EnUser::tableName() . ' u1', 'u1.id=f.local_id')
            ->leftJoin(EnUser::tableName() . ' u2', 'u2.id=f.cobber_id')
            ->where(['f.status' => [2, 3]])
            ->select([
                'f.id', 'f.no', 'f.business_type', 'f.invest_type', 'f.status',
                'f.name', 'f.address', 'f.created_at', 'u1.tel as uTel', 'f.source',
                'u2.tel as cTel', 'f.budget_amount', 'f.present_amount',
                'f.invest_ratio', 'f.field_ratio'
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
            $v['ratio'] = '投资方总收益占比 : ' . $v['invest_ratio'] . '<br/>场地方总收益占比 : ' . $v['field_ratio'];
            $v['info'] = '预算金额 : ' . $v['budget_amount'] . '<br/>现投金额 : ' . $v['present_amount'];
        }
        return $data;
    }

    /**
     * 首页数据
     * @param int $length
     * @return array|\yii\db\ActiveRecord[]
     */
    public static function indexData($length = 6)
    {
        $data = self::find()->where(['status' => [1, 2, 3, 4]])
            ->limit($length)
            ->asArray()->all();
        foreach ($data as &$v) {
            $images = explode(',', $v['images']);
            $v['images'] = $images[array_rand($images)];
            $v['business_type'] = Constant::businessType()[$v['business_type']];
            $v['invest_type'] = Constant::investType()[$v['invest_type']];
        }
        return Helper::arraySort($data, 'status', SORT_DESC, 'present_amount', SORT_ASC, 'created_at', SORT_DESC);
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
        $data = self::find()->where(['status' => [1, 2, 3, 4]]);
        if ($invest_type) {
            $data->andWhere(['invest_type' => $invest_type]);
        }
        if ($business_type) {
            $data->andWhere(['business_type' => $business_type]);
        }
        $data = $data->offset(($pageNum - 1) * 6)->limit(6)
            ->asArray()->all();
        foreach ($data as &$v) {
            $images = explode(',', $v['images']);
            $v['images'] = $images[array_rand($images)];
            $v['business_type'] = Constant::businessType()[$v['business_type']];
            $v['invest_type'] = Constant::investType()[$v['invest_type']];
        }
        return Helper::arraySort($data, 'status', SORT_DESC, 'present_amount', SORT_ASC, 'created_at', SORT_DESC);
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
     * @param int $amount
     * @param string $do add加/cut减
     * @return bool
     */
    public static function updatePresentAmount($id = 0, $amount = 0, $do = 'add')
    {
        if ($id && $do && $amount) {
            $model = self::findOne($id);
            if ($do == 'add') {
                $model->present_amount = $amount + (int)$model->present_amount;
                if ($model->present_amount >= $model->budget_amount) {
                    $model->present_amount = $model->budget_amount;
                    $model->status = 5;
                }
                return $model->save();
            }
            if ($do == 'cut') {
                $model->present_amount = ($model->present_amount - $amount) ?: 0;
                if ($model->status == 5) {
                    $model->status = 4;
                }
                return $model->save();
            }
        }
        return false;
    }

    /**
     * 获取待处理场地
     * @return bool
     */
    public static function getNeedField()
    {
        if ($fields = self::find()->where(['status' => 0, 'commissioner_id' => 0])->select(['id'])->asArray()->all()) {
            $fields = array_column($fields, 'id');
            $commissioner_id = Yii::$app->user->id;
            if ($field = self::findOne(['id' => $fields[array_rand($fields)], 'status' => 0, 'commissioner_id' => 0])) {
                $field->commissioner_id = $commissioner_id;
                $field->save(false);
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
     * 返回用户场站
     * @return array|mixed
     */
    public static function getUserField()
    {
        $data = self::find()->where([
            'local_id' => Yii::$app->user->id,
            'status' => [0, 1, 2, 3, 4, 5]
        ])->asArray()->all();
        foreach ($data as &$v) {
            $v['images'] = explode(',', $v['images'])[0];
            $v['business_type'] = $v['status'] ? Constant::businessType()[$v['business_type']] : '----';
            $v['invest_type'] = $v['status'] ? Constant::investType()[$v['invest_type']] : '----';
            $v['status_val'] = $v['status'];
            $v['status'] = in_array($v['status'], [1, 2, 3, 4, 5]) ? '已处理' : Constant::fieldStatus()[$v['status']];
            $v['created_at'] = date('Y-m-d H:i:s', $v['created_at']);
        }
        return Helper::arraySort($data, 'status_val', SORT_ASC, 'created_at', SORT_DESC);
    }

    /**
     * 返回用户推荐场站
     * @return array|mixed
     */
    public static function getUserRField()
    {
        $data = self::find()->where([
            'cobber_id' => Yii::$app->user->id,
            'status' => [0, 1, 2, 3, 4, 5]
        ])->asArray()->all();
        foreach ($data as &$v) {
            $v['images'] = explode(',', $v['images'])[0];
            $v['business_type'] = $v['status'] ? Constant::businessType()[$v['business_type']] : '----';
            $v['invest_type'] = $v['status'] ? Constant::investType()[$v['invest_type']] : '----';
            $v['status_val'] = $v['status'];
            $v['status'] = in_array($v['status'], [1, 2, 3, 4, 5]) ? '已处理' : Constant::fieldStatus()[$v['status']];
            $v['created_at'] = date('Y-m-d H:i:s', $v['created_at']);
        }
        return Helper::arraySort($data, 'status_val', SORT_ASC, 'created_at', SORT_DESC);
    }

    /**
     * 获取待处理场地数量
     * @return int|string
     */
    public static function getNeedFieldCount()
    {
        return self::find()->where(['status' => 0, 'commissioner_id' => 0])->count();
    }

    /**
     * 获取
     * @return mixed
     */
    public static function getFieldByFinish()
    {
        $data = self::find()->alias('f')
            ->leftJoin(EnUser::tableName() . ' u', 'u.id=f.local_id')
            ->where(['f.status' => 5])
            ->select(['f.no', 'f.name', 'f.address', 'u.tel as local', 'f.online'])
            ->page([
                'content' => ['like', 'f.no', 'f.name', 'f.address', 'u.tel'],
                'online' => ['=', 'f.online'],
            ]);
        foreach ($data['data'] as &$v) {
            $v['online'] = Constant::fieldOnline()[$v['online']];
        }
        return $data;
    }

    /**
     * 返回电站信息
     * @param string $no
     * @return array|null|\yii\db\ActiveRecord
     */
    public static function getFieldInfo($no = '')
    {
        $data = self::find()->where(['no' => $no])
            ->select(['no', 'name', 'address', 'lng', 'lat', 'images'])
            ->asArray()->one();
        if ($data) {
            $data['images'] = explode(',', $data['images']);
            $data['guns'] = self::getGuns($no);
        }
        return $data;
    }

    /**
     * 查询枪口信息
     * @param string $no
     * @return array
     */
    public static function getGuns($no = '')
    {
        $guns = ['count' => 0, 'used' => 0];
        $piles = (new client())->hGet('FieldInfo', $no) ?: [];
        foreach ($piles as $v) {
            $v = json_decode($v, true);
            $guns['count'] += $v['count'];
            $guns['used'] += $v['used'];
        }
        return $guns;
    }

    /**
     * @param array $nos
     * @return array|\yii\db\ActiveRecord[]
     */
    public static function getStep($nos = [])
    {
        $data = self::find()->where(['no' => $nos])
            ->select(['no', 'name', 'address', 'lng', 'lat',])
            ->asArray()->all();
        foreach ($data as &$v) {
            $v['guns'] = self::getGuns($v['no']);
        }
        return $data;
    }
}
