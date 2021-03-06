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
            ->where(['s.field' => $field])
            ->select(['s.type', 's.key', 's.num'])
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
            ->orderBy('i.created_at desc')
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
        $all = self::find()->where(['type' => [3, 4], 'key' => $user_id])->sum('money') ?: 0;
        $field = self::find()->where(['type' => 3, 'key' => $user_id])->sum('money') ?: 0;
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

    /**
     * 报表数据
     * @return array
     */
    public static function reportInfo()
    {
        $model = self::find();
        if ($company_id = Yii::$app->user->identity->company_id) {
            $model->where(['type' => 2, 'key' => $company_id]);
        } else {
            $model->where(['type' => 1]);
        }
        $model0 = clone $model;
        $model1 = clone $model;
        $model2 = clone $model;
        $model3 = clone $model;
        $model4 = clone $model;
        $minYear = $model0->min("FROM_UNIXTIME(created_at,'%Y')") ?: date('Y');
        $data = [
            'all' => round($model1->sum('money'), 2),
            'year' => round($model2->andWhere(["FROM_UNIXTIME(created_at,'%Y')" => date('Y')])->sum('money'), 2),
            'month' => round($model3->andWhere(["FROM_UNIXTIME(created_at,'%Y-%m')" => date('Y-m')])->sum('money'), 2),
            'day' => round($model4->andWhere(["FROM_UNIXTIME(created_at,'%Y-%m-%d')" => date('Y-m-d')])->sum('money'), 2),
            'years' => array_reverse(range($minYear, date('Y'))),
        ];
        return $data;
    }

    /**
     * 年报表数据
     * @param string $year
     * @return array
     */
    public static function yearData($year = '')
    {
        $year = $year ?: date('Y');
        $model = self::find();
        if ($company_id = Yii::$app->user->identity->company_id) {
            $model->where(['type' => 2, 'key' => $company_id]);
        } else {
            $model->where(['type' => 1]);
        }
        $res = $model->andWhere(["FROM_UNIXTIME(created_at,'%Y')" => $year])
            ->groupBy("month")
            ->select(["FROM_UNIXTIME(created_at,'%m') month", 'SUM(money) as money'])
            ->asArray()->all();
        $res = array_column($res, 'money', 'month');
        $data = ['01', '02', '03', '04', '05', '06', '07', '08', '09', '10', '11', '12'];
        foreach ($data as &$v) {
            if (isset($res[$v])) {
                $v = round($res[$v], 2);
            } else {
                $v = 0;
            }
        }
        return $data;
    }

    /**
     * 月报表数据
     * @param string $year
     * @param string $month
     * @return array
     */
    public static function monthData($year = '', $month = '')
    {
        $year = $year ?: date('Y');
        $month = $month ?: date('m');
        $days = cal_days_in_month(CAL_GREGORIAN, $month, $year);
        $data = range(1, $days);
        $days = [];
        $model = self::find();
        if ($company_id = Yii::$app->user->identity->company_id) {
            $model->where(['type' => 2, 'key' => $company_id]);
        } else {
            $model->where(['type' => 1]);
        }
        $res = $model->andWhere(["FROM_UNIXTIME(created_at,'%Y-%m')" => $year . '-' . $month])
            ->groupBy("days")
            ->select(["FROM_UNIXTIME(created_at,'%d') days", 'SUM(money) as money'])
            ->asArray()->all();
        $res = array_column($res, 'money', 'days');
        foreach ($data as &$v) {
            $day = str_pad($v, 2, "0", STR_PAD_LEFT);
            array_push($days, $day);
            $v = 0;
            if (isset($res[$day])) {
                $v = round($res[$day], 2);
            }
        }
        return ['days' => $days, 'data' => $data];
    }

    /**
     * 统计报表日数据
     * @param string $date
     * @return array|\yii\db\ActiveRecord[]
     */
    public static function statisticsDateData($date = '')
    {
        $date = $date ?: date('Y-m-d');
        $data = self::find()->alias('i')
            ->leftJoin(EnOrder::tableName() . ' o', 'o.no=i.order')
            ->leftJoin(EnPile::tableName() . ' p', 'p.no=o.pile')
            ->leftJoin(EnField::tableName() . ' f', 'f.no=p.field');
        if ($company_id = Yii::$app->user->identity->company_id) {
            $data->where(['i.type' => 2, 'i.key' => $company_id]);
        } else {
            $data->where(['i.type' => 1]);
        }
        $data = $data->andWhere(["FROM_UNIXTIME(i.created_at,'%Y-%m-%d')" => $date])
            ->select(['i.*', 'o.e', 'o.sm', 'o.pile', 'p.field'])
            ->orderBy('i.created_at desc')
            ->asArray()->all();
        foreach ($data as &$v) {
            $v['info1'] = '充电电量:' . $v['e'] . 'kwh<br>服务电费:' . $v['sm'];
            $v['info2'] = '电站编号:' . $v['field'] . '<br>电桩编号:' . $v['pile'];
            $v['created_at'] = date('Y-m-d H:i:s', $v['created_at']);
        }
        return $data;
    }

    /**
     * 年报表数据
     * @param string $sno
     * @param string $year
     * @return array
     */
    public static function yearDataByStock($sno = '', $year = '')
    {
        $year = $year ?: date('Y');
        $stock = EnStock::findOne(['no' => $sno]);
        $res = self::find()->alias('i')
            ->leftJoin(EnOrder::tableName() . ' o', 'o.no=i.order')
            ->leftJoin(EnPile::tableName() . ' p', 'p.no=o.pile')
            ->where(['p.field' => $stock->field, 'i.type' => $stock->type, 'i.key' => $stock->key, "FROM_UNIXTIME(i.created_at,'%Y')" => $year])
            ->groupBy("month")
            ->select(["FROM_UNIXTIME(i.created_at,'%m') month", 'SUM(i.money) as money'])
            ->asArray()->all();
        $res = array_column($res, 'money', 'month');
        $data = ['01', '02', '03', '04', '05', '06', '07', '08', '09', '10', '11', '12'];
        foreach ($data as &$v) {
            if (isset($res[$v])) {
                $v = round($res[$v], 2);
            } else {
                $v = 0;
            }
        }
        return $data;
    }

    /**
     * 月报表数据
     * @param string $sno
     * @param string $year
     * @param string $month
     * @return array
     */
    public static function monthDataByStock($sno = '', $year = '', $month = '')
    {
        $year = $year ?: date('Y');
        $month = $month ?: date('m');
        $days = cal_days_in_month(CAL_GREGORIAN, $month, $year);
        $data = range(1, $days);
        $days = [];
        $stock = EnStock::findOne(['no' => $sno]);
        $res = self::find()->alias('i')
            ->leftJoin(EnOrder::tableName() . ' o', 'o.no=i.order')
            ->leftJoin(EnPile::tableName() . ' p', 'p.no=o.pile')
            ->where(['p.field' => $stock->field, 'i.type' => $stock->type, 'i.key' => $stock->key, "FROM_UNIXTIME(i.created_at,'%Y-%m')" => $year . '-' . $month])
            ->groupBy("days")
            ->select(["FROM_UNIXTIME(i.created_at,'%d') days", 'SUM(i.money) as money'])
            ->asArray()->all();
        $res = array_column($res, 'money', 'days');
        foreach ($data as &$v) {
            $day = str_pad($v, 2, "0", STR_PAD_LEFT);
            array_push($days, $day);
            $v = 0;
            if (isset($res[$day])) {
                $v = round($res[$day], 2);
            }
        }
        return ['days' => $days, 'data' => $data];
    }

    /**
     * 统计报表日数据
     * @param string $sno
     * @param string $date
     * @return $this|array|\yii\db\ActiveRecord[]
     */
    public static function statisticsDateDataByStock($sno = '', $date = '')
    {
        $date = $date ?: date('Y-m-d');
        $stock = EnStock::findOne(['no' => $sno]);
        $data = self::find()->alias('i')
            ->leftJoin(EnOrder::tableName() . ' o', 'o.no=i.order')
            ->leftJoin(EnPile::tableName() . ' p', 'p.no=o.pile')
            ->where(['p.field' => $stock->field, 'i.type' => $stock->type, 'i.key' => $stock->key, "FROM_UNIXTIME(i.created_at,'%Y-%m-%d')" => $date])
            ->select(['i.*', 'o.e', 'o.sm', 'o.pile', 'p.field'])
            ->orderBy('i.created_at desc')
            ->asArray()->all();
        foreach ($data as &$v) {
            $v['info1'] = '充电电量:' . $v['e'] . 'kwh<br>服务电费:' . $v['sm'];
            $v['info2'] = '电站编号:' . $v['field'] . '<br>电桩编号:' . $v['pile'];
            $v['created_at'] = date('Y-m-d H:i:s', $v['created_at']);
        }
        return $data;
    }
}
