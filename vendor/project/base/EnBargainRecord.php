<?php

namespace vendor\project\base;

use vendor\project\helpers\Constant;
use Yii;
use yii\db\Exception;

/**
 * This is the model class for table "en_bargain_record".
 *
 * @property string $id
 * @property string $b_id 砍价ID
 * @property string $user_id 用户ID
 * @property string $price 砍价
 * @property string $created_at 创建时间
 */
class EnBargainRecord extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'en_bargain_record';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['b_id', 'user_id', 'created_at'], 'integer'],
            [['price'], 'number'],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'b_id' => '砍价ID',
            'user_id' => '用户ID',
            'price' => '砍价',
            'created_at' => '创建时间',
        ];
    }

    /**
     * 获取砍价记录
     * @param int $bargain_id
     * @return array|\yii\db\ActiveRecord[]
     */
    public static function getRecord($bargain_id = 0)
    {
        return self::find()->alias('r')
            ->leftJoin(EnUser::tableName() . ' u', 'u.id=r.user_id')
            ->where(['b_id' => $bargain_id])
            ->select(['u.tel', 'SUM(r.price) as price', 'r.created_at'])
            ->groupBy('u.tel')
            ->page();
    }

    /**
     * 砍价逻辑
     * @param int $id
     * @return array
     */
    public static function bargain($id = 0)
    {
        if (self::findOne(['user_id' => Yii::$app->user->id, 'b_id' => $id])) {
            return ['type' => false, 'msg' => '您已经砍过啦', 'data' => ''];
        }
        $bargain = EnBargain::find()->where(['id' => $id])->andWhere(['>', 'created_at', time() - Constant::bargainTime()])->one();
        if (!$bargain) {
            return ['type' => false, 'msg' => '砍价已经结束', 'data' => ''];
        }
        $nowPrice = self::find()->where(['b_id' => $id])->sum('price');
        if ($bargain->price <= $nowPrice) {
            return ['type' => false, 'msg' => '砍价已经完成', 'data' => ''];
        }
        $nowCount = self::find()->where(['b_id' => $id])->count() + 1;
        if ($nowCount == 1) {
            $price = self::randFloat($bargain->price * 0.4, $bargain->price * 0.6);
        } else {
            if ($nowCount > $bargain->count) {
                $price = self::randFloat(0, $bargain->price - $nowPrice);
            } else {
                $price = self::randFloat(0, ($bargain->price - $nowPrice) / $nowCount);
            }
        }
        $transaction = Yii::$app->db->beginTransaction();
        try {
            $record = new self();
            $record->b_id = $id;
            $record->user_id = Yii::$app->user->id;
            $record->price = $price;
            $record->created_at = time();
            if (!$record->save()) {
                throw new Exception($record->errorsInfo());
            }
            if ($nowPrice + $price >= $bargain->price) {
                $user = EnUser::findOne($bargain->user_id);
                $user->money += $bargain->price;
                if (!$user->save()) {
                    throw new Exception('同步用户数据失败');
                }
            }
            $transaction->commit();
            return ['type' => true, 'msg' => 'ok', 'data' => $price];
        } catch (Exception $e) {
            $transaction->rollBack();
            return ['type' => false, 'msg' => $e->getMessage(), 'data' => ''];
        }
    }

    /**
     * 小数取随机数
     * @param $min
     * @param $max
     * @return float
     */
    public static function randFloat($min, $max)
    {
        $num = $min + mt_rand() / mt_getrandmax() * ($max - $min);
        return (float)sprintf("%.2f", $num);
    }
}
