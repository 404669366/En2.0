<?php

namespace vendor\project\base;

use vendor\project\helpers\client;
use vendor\project\helpers\Helper;
use vendor\project\helpers\Msg;
use Yii;

/**
 * This is the model class for table "en_pile".
 *
 * @property int $id
 * @property string $no 电桩编号
 * @property string $field_id 场站ID
 * @property string $field 场站编号
 * @property int $model_id 电桩型号
 * @property string $remark 备注
 * @property string $created_at 创建时间
 * @property string $rules 计费规则
 */
class EnPile extends \yii\db\ActiveRecord
{
    public $field;
    public $rules;

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
            [['field_id', 'model_id', 'created_at'], 'integer'],
            [['no'], 'string', 'max' => 32],
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
            'no' => '电桩编号',
            'field_id' => '场站ID',
            'field' => '场站编号',
            'model_id' => '电桩型号',
            'remark' => '备注',
            'created_at' => '创建时间',
            'rules' => '计费规则',
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
     * 查询计价规则
     * @return mixed
     */
    public function getRules()
    {
        return (new client())->hGetField('PileInfo', $this->no, 'rules');
    }

    public function validateField()
    {
        $model = EnField::findOne(['no' => $this->field, 'status' => [5]]);
        if ($model) {
            $this->field_id = $model->id;
        } else {
            $this->addError('field', '场站未开放或不存在');
        }
    }

    public function afterSave($insert, $changedAttributes)
    {
        (new client())->hSetField('PileInfo', $this->no, 'rules', $this->rules);
    }

    /**
     * 创建充电信息
     * @param string $no
     * @return array|bool
     */
    public static function chargeInfo($no = '')
    {
        $pile = substr($no, 0, strlen($no) - 1);
        if ($pile = self::findOne(['no' => $pile])) {
            $userInfo = (new client())->hGet('UserInfo', Yii::$app->user->id);
            if ($userInfo && isset($userInfo['order'])) {
                return [
                    'do' => 'seeCharge',
                    'orderNo' => $userInfo['order'],
                    'fieldName' => $pile->local->name,
                ];
            }
            $orderNo = Helper::createNo('O');
            (new client())->hSetField('UserInfo', Yii::$app->user->id, 'order', $orderNo);
            (new client())->hSetField('UserInfo', Yii::$app->user->id, 'money', Yii::$app->user->identity->money);
            return [
                'do' => 'beginCharge',
                'orderNo' => $orderNo,
                'pile' => $pile->no,
                'gun' => substr($no, -1),
                'uid' => Yii::$app->user->id,
                'fieldName' => $pile->local->name,
            ];
        }
        Msg::set('未查询到该电桩信息,请检查电桩编号');
        return false;
    }
}
