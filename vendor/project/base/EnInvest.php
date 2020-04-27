<?php

namespace vendor\project\base;

use vendor\project\helpers\Constant;
use vendor\project\helpers\Helper;
use vendor\project\helpers\Wechat;
use Yii;

/**
 * This is the model class for table "en_invest".
 *
 * @property string $id
 * @property string $no 充值编号
 * @property string $uid 用户ID
 * @property string $money 充值金额
 * @property string $balance 当前余额
 * @property int $source 充值来源 1微信2支付宝3银联
 * @property int $status 充值状态0等待支付1支付成功2支付失败
 * @property string $created_at 创建时间
 */
class EnInvest extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'en_invest';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['no'], 'unique'],
            [['no', 'uid', 'money', 'source'], 'required'],
            [['uid', 'source', 'status', 'created_at'], 'integer'],
            [['no'], 'string', 'max' => 20],
            [['money', 'balance'], 'number'],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'no' => '充值编号',
            'uid' => '用户ID',
            'money' => '充值金额',
            'balance' => '当前余额',
            'source' => '充值来源 1微信2支付宝3银联',
            'status' => '充值状态0等待支付1支付成功2支付失败',
            'created_at' => '创建时间',
        ];
    }

    /**
     * 发起充值
     * @param int $money
     * @param int $way
     * @return array|bool|mixed
     */
    public static function invest($money = 0, $way = 1)
    {
        $model = new self();
        $model->no = Helper::createNo('I');
        $model->uid = Yii::$app->user->id;
        $model->money = $model->uid == 5 ? 0.01 : $money;
        $model->balance = EnUser::getMoney();
        $model->source = $way;
        $model->created_at = time();
        if ($model->save()) {
            if ($way == 1) {
                if ($data = Wechat::jsPay('亿能充电-余额充值', $model->no, $model->money, '/wx/pay/back.html')) {
                    return $data;
                }
            }
        }
        return false;
    }

    /**
     * 用户充值记录
     * @param int $uid
     * @return array|\yii\db\ActiveRecord[]
     */
    public static function getUserInvest($uid = 0)
    {
        $uid = $uid ?: Yii::$app->user->id;
        $data = self::find()->where(['uid' => $uid, 'status' => [1, 2]])
            ->select(['money', 'balance', 'source', 'status', 'created_at'])
            ->orderBy('created_at desc')
            ->asArray()->all();
        foreach ($data as &$v) {
            $v['source'] = Constant::investSource()[$v['source']];
            $v['status'] = Constant::investStatus()[$v['status']];
            $v['created_at'] = date('Y-m-d H:i:s', $v['created_at']);
        }
        return $data;
    }

    /**
     * 报表数据
     * @return array
     */
    public static function reportInfo()
    {
        $minYear = self::find()->min("FROM_UNIXTIME(created_at,'%Y')") ?: date('Y');
        $data = [
            'all' => round(self::find()->where(['status' => 1])->sum('money'), 2),
            'year' => round(self::find()->where(["FROM_UNIXTIME(created_at,'%Y')" => date('Y'), 'status' => 1])->sum('money'), 2),
            'month' => round(self::find()->where(["FROM_UNIXTIME(created_at,'%Y-%m')" => date('Y-m'), 'status' => 1])->sum('money'), 2),
            'day' => round(self::find()->where(["FROM_UNIXTIME(created_at,'%Y-%m-%d')" => date('Y-m-d'), 'status' => 1])->sum('money'), 2),
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
        $res = self::find()
            ->where(["FROM_UNIXTIME(created_at,'%Y')" => $year, 'status' => 1])
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
        $res = self::find()
            ->where(["FROM_UNIXTIME(created_at,'%Y-%m')" => $year . '-' . $month, 'status' => 1])
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
     * 统计报表单月数据
     * @param string $date
     * @return array|\yii\db\ActiveRecord[]
     */
    public static function statisticsDateData($date = '')
    {
        $date = $date ?: date('Y-m-d');
        $data = self::find()->alias('i')
            ->leftJoin(EnUser::tableName() . ' u', 'u.id=i.uid')
            ->where(["FROM_UNIXTIME(i.created_at,'%Y-%m-%d')" => $date,])
            ->select(['i.*', 'u.tel'])
            ->orderBy('i.created_at desc')
            ->asArray()->all();
        foreach ($data as &$v) {
            $v['status'] = Constant::investStatus()[$v['status']];
            $v['source'] = Constant::investSource()[$v['source']];
            $v['created_at'] = date('Y-m-d H:i:s', $v['created_at']);
        }
        return $data;
    }
}
