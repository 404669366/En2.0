<?php

namespace vendor\project\base;

use vendor\project\helpers\Constant;
use vendor\project\helpers\Helper;
use vendor\project\helpers\Msg;
use Yii;

/**
 * This is the model class for table "en_pile".
 *
 * @property string $no 电桩编号
 * @property int count 场站ID
 * @property int online 在线状态 0离线1在线
 * @property int bind 绑定状态 0未绑定1已绑定
 * @property string $rules 计费规则
 * @property string $field 场站编号
 * @property int $model_id 电桩型号
 */
class EnPile extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'en_pile';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['no'], 'unique'],
            [['no', 'model_id', 'field', 'rules'], 'required'],
            [['field'], 'validateField'],
            [['model_id', 'count', 'online', 'bind'], 'integer'],
            [['no', 'field'], 'string', 'max' => 32],
            [['rules'], 'string', 'max' => 1000],
        ];
    }

    public function validateField()
    {
        $model = EnField::findOne(['no' => $this->field, 'status' => 4]);
        if (!$model) {
            $this->addError('field', '场站不存在或状态有误');
        }
        $this->bind = 1;
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'no' => '电桩编号',
            'count' => '枪口数量',
            'online' => '在线状态',
            'bind' => '绑定状态',
            'rules' => '计费规则',
            'field' => '场站编号',
            'model_id' => '电桩型号',
        ];
    }

    /**
     * 关联场站
     * @return \yii\db\ActiveQuery
     */
    public function getLocal()
    {
        return $this->hasOne(EnField::class, ['no' => 'field']);
    }

    /**
     * 分页数据
     * @param string $no
     * @return $this|mixed
     */
    public static function getPageData($no = '')
    {
        $data = self::find()->alias('p')
            ->leftJoin(EnField::tableName() . ' f', 'p.field=f.no')
            ->leftJoin(EnModel::tableName() . ' m', 'p.model_id=m.id');
        if ($no) {
            $data->where(['p.field' => $no]);
        }
        $data = $data->select(['p.*', 'f.no as fno', 'f.name', 'f.address', 'm.name as model'])
            ->page([
                'keywords' => ['like', 'p.no', 'f.no', 'f.name', 'f.address', 'f.local', 'm.name'],
                'online' => ['=', 'p.online'],
                'bind' => ['=', 'p.bind'],
            ]);
        foreach ($data['data'] as &$v) {
            $v['online'] = Constant::pileOnline()[$v['online']];
            $v['bind'] = Constant::pileBind()[$v['bind']];
            $v['fieldInfo'] = "场站编号: {$v['fno']}<br>场站名称: {$v['name']}<br>场站地址: {$v['address']}";
        }
        return $data;
    }

    /**
     * 创建充电信息
     * @param string $no
     * @return array|bool
     */
    public static function chargeInfo($no = '')
    {
        if (EnUser::getMoney() > 5) {
            $no = explode('-', $no);
            if (count($no) == 2) {
                if ($pile = self::find()->where(['no' => $no[0]])->andWhere(['>=', 'count', $no[1]])->one()) {
                    $order = new EnOrder();
                    $order->no = Helper::createNo('O');
                    $order->pile = $no[0];
                    $order->gun = $no[1];
                    $order->uid = Yii::$app->user->id;
                    $order->created_at = time();
                    if ($order->save()) {
                        return [
                            'do' => 'beginCharge',
                            'orderNo' => $order->no,
                            'pile' => $no[0],
                            'gun' => $no[1],
                            'fieldName' => $pile->local->name,
                        ];
                    }
                    Msg::set('创建订单失败,请稍后再试');
                    return false;
                }
            }
            Msg::set('编码输入有误,请检查');
            return false;
        }
        Msg::set('您的余额不足,请前往充值');
        return false;
    }

    /**
     * 查询电桩归属桩信息
     * @param string $no
     * @return array
     */
    public static function getPilesByField($no = '')
    {
        $data = self::find()->alias('p')
            ->leftJoin(EnModel::tableName() . ' m', 'm.id=p.model_id')
            ->where(['p.field' => $no])
            ->select(['p.no', 'p.rules', 'm.*'])
            ->orderBy('p.no desc')
            ->asArray()->all();
        foreach ($data as &$v) {
            $images = explode(',', Yii::$app->cache->get('EnField-images-' . $no));
            $v['image'] = $images[array_rand($images)];
            $v['standard'] = Constant::pileStandard()[$v['standard']];
            $v['rules'] = self::getNowRule($v['rules']);
        }
        return $data;
    }

    /**
     * 查询枪口信息
     * @param string $no
     * @return array
     */
    public static function getGunsInfoByField($no = '')
    {
        $guns = ['count' => 0, 'used' => 0];
        $piles = self::find()->where(['field' => $no])->select(['no', 'count'])->asArray()->all();
        foreach ($piles as $v) {
            $guns['count'] += $v['count'];
            $guns['used'] += EnOrder::find()->where(['pile' => $v['no'], 'status' => [0, 1]])->count();
        }
        return $guns;
    }

    /**
     * 返回当前计价规则
     * @param string $rules
     * @return array
     */
    public static function getNowRule($rules = '')
    {
        $now = time() - strtotime(date('Y-m-d'));
        $rules = json_decode($rules, true);
        foreach ($rules as $v) {
            if ($now >= $v[0] && $now < $v[1]) {
                return $v;
            }
        }
        return [0, 86400, 0.8, 0.6];
    }
}
