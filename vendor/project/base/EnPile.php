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
 * @property string $rules 计费规则
 * @property int $field_id 场站ID
 * @property string $field 场站编号
 * @property int $model_id 电桩型号
 */
class EnPile extends \yii\db\ActiveRecord
{
    public $field;

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
            [['field_id', 'model_id', 'count', 'online'], 'integer'],
            [['no'], 'string', 'max' => 32],
            [['rules'], 'string', 'max' => 1000],
        ];
    }

    public function validateField()
    {
        if ($model = EnField::findOne(['no' => $this->field])) {
            $this->field_id = $model->id;
        } else {
            $this->addError('field', '场站不存在');
        }
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'no' => '电桩编号',
            'count' => '枪口数量',
            'online' => '在线状态 0离线1在线',
            'rules' => '计费规则',
            'field_id' => '场站ID',
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
        return $this->hasOne(EnField::class, ['id' => 'field_id']);
    }

    /**
     * 分页数据
     * @return mixed
     */
    public static function getPageData()
    {
        $data = self::find()->alias('p')
            ->leftJoin(EnField::tableName() . ' f', 'p.field_id=f.id')
            ->leftJoin(EnModel::tableName() . ' m', 'p.model_id=m.id')
            ->select(['p.*', 'f.no as fno', 'f.name', 'f.address', 'f.local', 'm.name as model'])
            ->page([
                'keywords' => ['like', 'p.no', 'f.no', 'f.name', 'f.address', 'f.local', 'm.name'],
                'online' => ['=', 'p.online'],
            ]);
        foreach ($data['data'] as &$v) {
            $v['online'] = Constant::pileOnline()[$v['online']];
            $v['fieldInfo'] = "场站业主: {$v['local']}<br>场站编号: {$v['fno']}<br>场站名称: {$v['name']}<br>场站地址: {$v['address']}";
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
        if ($order = EnOrder::findOne(['uid' => \Yii::$app->user->id, 'status' => [0, 1]])) {
            Msg::set('您有订单进行中');
            return [
                'do' => 'seeCharge',
                'pile' => $order->pile,
                'gun' => $order->gun,
                'fieldName' => $order->pileInfo->local->name,
            ];
        }
        if (EnUser::getMoney() > 5) {
            $no = explode('-', $no);
            if (count($no) == 2) {
                if ($pile = self::find()->where(['no' => $no[0]])->andWhere(['>=', 'count', $no[1]])->one()) {
                    $order = EnOrder::findOne(['pile' => $no[0], 'gun' => $no[1], 'status' => [0, 1]]);
                    if (!$order) {
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
                    Msg::set('枪口已占用,请稍后再试');
                    return false;
                }
            }
            Msg::set('未查询到该电桩信息,请检查电桩编号');
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
            ->leftJoin(EnField::tableName() . ' f', 'f.id=p.field_id')
            ->leftJoin(EnModel::tableName() . ' m', 'm.id=p.model_id')
            ->where(['f.no' => $no])
            ->select(['p.no', 'p.rules', 'm.*'])
            ->orderBy('p.no desc')
            ->asArray()->all();
        foreach ($data as &$v) {
            $images = explode(',', $v['images']);
            $v['image'] = $images[array_rand($images)];
            $v['standard'] = Constant::pileStandard()[$v['standard']];
            $v['rule'] = self::getNowRule($v['rules']);
        }
        var_dump($data);exit();
        return $data;
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
