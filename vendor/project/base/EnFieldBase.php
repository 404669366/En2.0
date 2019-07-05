<?php

namespace vendor\project\base;

use vendor\project\helpers\Constant;
use Yii;

/**
 * This is the model class for table "en_field_base".
 *
 * @property string $id
 * @property string $commissioner_id 专员ID
 * @property string $cobber_id 推荐用户ID
 * @property string $user_id 用户ID
 * @property string $field_id 转化场地ID
 * @property string $address 地址
 * @property string $lng 经度
 * @property string $lat 纬度
 * @property string $remark 备注
 * @property int $status 状态 1待转化2已转化
 * @property string $created_at 创建时间
 */
class EnFieldBase extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'en_field_base';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['address', 'lng', 'lat', 'user_id'], 'required'],
            [['commissioner_id', 'cobber_id', 'user_id', 'status', 'created_at', 'field_id'], 'integer'],
            [['address'], 'string', 'max' => 60],
            [['lng', 'lat'], 'string', 'max' => 20],
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
            'cobber_id' => '推荐用户ID',
            'user_id' => '用户ID',
            'field_id' => '转化场地ID',
            'address' => '地址',
            'lng' => '经度',
            'lat' => '纬度',
            'remark' => '备注',
            'status' => '状态 1待转化2已转化',
            'created_at' => '创建时间',
        ];
    }

    /**
     * 关联推荐人
     * @return \yii\db\ActiveQuery
     */
    public function getCobber()
    {
        return $this->hasOne(EnUser::class, ['id' => 'cobber_id']);
    }

    /**
     * 关联用户
     * @return \yii\db\ActiveQuery
     */
    public function getUser()
    {
        return $this->hasOne(EnUser::class, ['id' => 'user_id']);
    }

    /**
     * 关联用户
     * @return \yii\db\ActiveQuery
     */
    public function getField()
    {
        return $this->hasOne(EnField::class, ['id' => 'field_id']);
    }

    /**
     * 获取分页数据
     * @return mixed
     */
    public static function getPageData()
    {
        $data = self::find()->alias('b')
            ->leftJoin(EnUser::tableName() . ' u1', 'u1.id=b.user_id')
            ->leftJoin(EnUser::tableName() . ' u2', 'u2.id=b.cobber_id')
            ->leftJoin(EnField::tableName() . ' f', 'f.id=b.field_id')
            ->where(['b.commissioner_id' => Yii::$app->user->id])
            ->select([
                'b.id', 'f.no', 'u1.tel as uTel', 'u2.tel as cTel', 'b.address', 'b.status', 'b.created_at'
            ])
            ->page([
                'content' => ['like', 'u1.tel', 'u2.tel', 'b.address', 'f.no'],
                'status' => ['=', 'b.status']
            ]);
        foreach ($data['data'] as &$v) {
            $v['status'] = Constant::baseFieldStatus()[$v['status']];
        }
        return $data;
    }

    /**
     * 返回待抢场地
     * @return array|\yii\db\ActiveRecord[]
     */
    public static function getNeed()
    {
        $data = self::find()->where(['commissioner_id' => 0, 'status' => 1])->select(['id'])->asArray()->all();
        return array_column($data, 'id');
    }

    /**
     * 抢单
     * @return string
     */
    public static function rob()
    {
        if ($data = self::getNeed()) {
            if ($user_id = Yii::$app->user->id) {
                $key = array_rand($data);
                if ($model = self::findOne(['id' => $data[$key], 'commissioner_id' => 0, 'status' => 1])) {
                    $model->commissioner_id = $user_id;
                    if ($model->save()) {
                        return '抢单成功';
                    }
                    return $model->errors();
                }
                return '手慢啦...';
            }
            return '拉取用户信息错误';
        }
        return '已经没有啦...';
    }

    /**
     * 放弃
     * @param int $id
     * @return string
     */
    public static function renounce($id = 0)
    {
        if ($model = self::findOne(['id' => $id, 'commissioner_id' => Yii::$app->user->id, 'status' => 1])) {
            $model->commissioner_id = 0;
            if ($model->save()) {
                return '放弃成功';
            }
            return $model->errors();
        }
        return '拉取信息错误';
    }
}
