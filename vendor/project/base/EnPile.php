<?php

namespace vendor\project\base;

use vendor\project\helpers\client;
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
}
