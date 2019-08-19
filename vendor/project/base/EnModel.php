<?php

namespace vendor\project\base;

use vendor\project\helpers\Constant;
use Yii;

/**
 * This is the model class for table "en_model".
 *
 * @property int $id
 * @property string $name 电桩名称
 * @property string $images 图片
 * @property string $power 功率
 * @property string $voltage 电压
 * @property string $current 电流
 * @property string $brand 电桩品牌
 * @property int $type 电桩类型1慢充2快充3均充4轮充
 * @property int $standard 标准1国标2011 2国标2015
 */
class EnModel extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'en_model';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['name', 'images', 'power', 'voltage', 'current', 'brand', 'type', 'standard'], 'required'],
            [['name'], 'unique'],
            [['type', 'standard'], 'integer'],
            [['name', 'brand'], 'string', 'max' => 20],
            [['images'], 'string', 'max' => 400],
            [['power', 'voltage', 'current'], 'string', 'max' => 10],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'name' => '电桩名称',
            'images' => '图片',
            'power' => '功率',
            'voltage' => '电压',
            'current' => '电流',
            'brand' => '电桩品牌',
            'type' => '电桩类型1慢充2快充3均充4轮充',
            'standard' => '标准1国标2011 2国标2015',
        ];
    }

    /**
     * 分页数据
     * @return mixed
     */
    public static function getPageData()
    {
        $data = self::find()->page([
            'name' => ['like', 'name', 'brand', 'power'],
            'type' => ['=', 'type'],
            'standard' => ['=', 'standard'],
        ]);
        foreach ($data['data'] as &$v) {
            $v['type'] = Constant::pileType()[$v['type']];
            $v['standard'] = Constant::pileStandard()[$v['standard']];
        }
        return $data;
    }

    /**
     * 查询电桩类型
     * @return array|\yii\db\ActiveRecord[]
     */
    public static function getModels()
    {
        return array_column(self::find()->select(['id', 'name'])->asArray()->all(), 'name', 'id');
    }
}
