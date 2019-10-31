<?php

namespace vendor\project\base;

use vendor\project\helpers\Constant;
use Yii;

/**
 * This is the model class for table "en_income".
 *
 * @property string $id
 * @property string $order 订单编号
 * @property int $type 类型 1平台收益2企业收益3场地收益4投资收益
 * @property string $key 关联键(type=? 1无效2企业ID3场地用户ID4投资用户ID)
 * @property string $money 结算金额
 * @property string $created_at
 */
class EnIncome extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'en_income';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['type', 'key', 'created_at'], 'integer'],
            [['order'], 'string', 'max' => 32],
            [['money'], 'number'],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'order' => '订单编号',
            'type' => '类型 1平台收益2企业收益3场地收益4投资收益',
            'key' => '关联键(type=? 1无效2企业ID3场地用户ID4投资用户ID)',
            'money' => '结算金额',
            'created_at' => 'Created At',
        ];
    }

    /**
     * 计算分成
     * @param string $field
     * @param string $order
     * @param string $money
     */
    public static function count($field = '', $order = '', $money = '')
    {
        $stock = EnStock::find()->alias('s')
            ->leftJoin(EnField::tableName() . ' f', 'f.no=s.field')
            ->where(['s.field' => $field])
            ->select(['s.type', 's.key', 's.num', 'f.company_id'])
            ->asArray()->all();
        foreach ($stock as &$v) {
            $v = [
                'order' => $order,
                'type' => $v['type'],
                'key' => $v['key'],
                'money' => round($money * $v['num'] / 100, 2),
                'created_at' => time(),
            ];
        }
        Yii::$app->db->createCommand()->batchInsert(self::tableName(), ['order', 'type', 'key', 'money', 'created_at'], $stock)->execute();
    }

    /**
     * 我的收益
     * @return array|\yii\db\ActiveRecord[]
     */
    public static function listDataByCenter()
    {
        $data = self::find()->alias('i')
            ->leftJoin(EnOrder::tableName() . ' o', 'o.no=i.order')
            ->leftJoin(EnPile::tableName() . ' p', 'p.no=o.pile')
            ->leftJoin(EnField::tableName() . ' f', 'f.no=p.field')
            ->where(['type' => [3, 4], 'key' => Yii::$app->user->id])
            ->select(['i.*', 'o.sm', 'o.e', 'o.pile', 'f.name'])
            ->orderBy(['i.created_at' => 'desc'])
            ->asArray()->all();
        foreach ($data as &$v) {
            $v['typeName'] = Constant::IncomeType()[$v['type']];
            $v['created'] = date('Y-m-d H:i:s', $v['created_at']);
        }
        return $data;
    }

    /**
     * 返回所有收益
     * @param int $type
     * @param int $key
     * @return mixed
     */
    public static function getAll($type = 0, $key = 0)
    {
        return self::find()->where(['type' => $type, 'key' => $key])->sum('money') ?: 0;
    }

    /**
     * 返回剩余收益
     * @param int $iType
     * @param int $cType
     * @param int $key
     * @return mixed
     */
    public static function getSurplus($iType = 0, $cType = 0, $key = 0)
    {
        $all = self::find()->where(['type' => $iType, 'key' => $key])->sum('money') ?: 0;
        $cash = EnCash::find()->where(['type' => $cType, 'key' => $key, 'status' => [1, 2]])->sum('money') ?: 0;
        return $all - $cash;
    }

    /**
     * 返回提出收益
     * @param int $type
     * @param int $key
     * @return mixed
     */
    public static function getOut($type = 0, $key = 0)
    {
        return EnCash::find()->where(['type' => $type, 'key' => $key, 'status' => [1, 2]])->sum('money') ?: 0;
    }

    /**
     * 收益统计
     * @return array
     */
    public static function incomeByCenter()
    {
        $user_id = Yii::$app->user->id;
        $all = self::find()->where(['type' => [3, 4], 'key' => $user_id])->sum('money');
        $field = self::find()->where(['type' => 3, 'key' => $user_id])->sum('money');
        $beginYear = self::find()->where(['type' => [3, 4], 'key' => $user_id])->min('created_at') ?: time();
        $endYear = self::find()->where(['type' => [3, 4], 'key' => $user_id])->max('created_at') ?: time();
        $data = [
            'all' => $all,
            'invest' => round($all - $field, 2),
            'field' => $field,
            'years' => range(date('Y', $endYear), date('Y', $beginYear), 1),
            'e1' => self::incomeDataByCenter(),
            'e2' => self::incomeDataByCenter(4),
            'e3' => self::incomeDataByCenter(3),
        ];
        return $data;
    }

    /**
     * 每月收益统计
     * @param array $type
     * @param int $year
     * @return array
     */
    public static function incomeDataByCenter($type = [3, 4], $year = 0)
    {
        $year = $year ?: date('Y');
        $month = ['-01', '-02', '-03', '-04', '-05', '-06', '-07', '-08', '-09', '-10', '-11', '-12'];
        $modelBase = self::find()->where(['type' => $type, 'key' => Yii::$app->user->id]);
        foreach ($month as &$v) {
            $model = clone $modelBase;
            $v = $model->andWhere(["FROM_UNIXTIME(created_at,'%Y-%m')" => $year . $v])->sum('money') ?: 0;
        }
        return $month;
    }
}
