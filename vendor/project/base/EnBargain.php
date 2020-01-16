<?php

namespace vendor\project\base;

use vendor\project\helpers\Constant;
use Yii;

/**
 * This is the model class for table "en_bargain".
 *
 * @property string $id
 * @property int $type 关联类型 1充电订单2其他商品
 * @property string $key 关联键
 * @property string $user_id 用户ID
 * @property string $price 总金额
 * @property string $count 最低砍成数
 * @property string $created_at 创建时间
 */
class EnBargain extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'en_bargain';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['type', 'user_id', 'count', 'created_at'], 'integer'],
            [['key'], 'string', 'max' => 32],
            [['price'], 'number'],
            [['price'], 'compare', 'compareValue' => 0, 'operator' => '>'],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'type' => '关联类型 1充电订单2其他商品',
            'key' => '关联键',
            'user_id' => '用户ID',
            'price' => '总金额',
            'count' => '最低砍成数',
            'created_at' => '创建时间',
        ];
    }

    /**
     * 分页数据
     * @param int $type
     * @return mixed
     */
    public static function getPageData($type = 1)
    {
        $data = self::find()->alias('b')
            ->leftJoin(EnUser::tableName() . ' u', 'u.id=b.user_id')
            ->where(['b.type' => $type])
            ->select(['b.id', 'b.key as no', 'u.tel', 'b.price', 'b.count', 'b.created_at'])
            ->page(['keywords' => ['like', 'b.key', 'u.tel']]);
        foreach ($data['data'] as &$v) {
            $v['created_at'] = date('Y-m-d H:i:s', $v['created_at']);
            $v['nowCount'] = EnBargainRecord::find()->where(['b_id' => $v['id']])->count();
            $v['nowPrice'] = (string)round(EnBargainRecord::find()->where(['b_id' => $v['id']])->sum('price'), 2);
        }
        return $data;
    }

    /**
     * 获取免单砍价信息
     * @param int $id
     * @return array
     */
    public static function getOrderBargain($id = 0)
    {
        $model = self::findOne($id);
        $haveTime = $model->created_at + Constant::bargainTime() - time();
        $field = EnOrder::findOne(['no' => $model->key])->pileInfo->local->name;
        $bargainPrice = EnBargainRecord::find()->where(['b_id' => $model->id])->sum('price');
        return [
            'bargain' => $model->toArray(),
            'record' => EnBargainRecord::getRecord($model->id),
            'field' => $field,
            'haveTime' => $haveTime,
            'bargainPrice' => $bargainPrice,
            'isComplete' => $bargainPrice >= $model->price,
            'isSelf' => Yii::$app->user->id == $model->user_id,
            'isBargain' => (bool)EnBargainRecord::findOne(['b_id' => $model->id, 'user_id' => Yii::$app->user->id]),
        ];
    }
}
