<?php

namespace vendor\project\base;

use GatewayClient\Gateway;
use vendor\project\helpers\client;
use vendor\project\helpers\Constant;
use vendor\project\helpers\Helper;
use vendor\project\helpers\Msg;
use Yii;
use yii\db\Exception;

/**
 * This is the model class for table "en_order".
 *
 * @property string $no 订单编号
 * @property string $pile 电桩编号
 * @property int $gun 电桩枪口号
 * @property string $uid 用户id
 * @property string $e 充电电量
 * @property string $bm 基础电费
 * @property string $sm 服务电费
 * @property string $duration 充电时长
 * @property string $rules 计费规则
 * @property int $status 订单状态 0启动中1充电中2充电结束3完成支付4启动失败
 * @property string $created_at 创建时间
 */
class EnOrder extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'en_order';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['no'], 'unique'],
            [['no', 'pile', 'gun', 'uid'], 'required'],
            [['gun', 'uid', 'duration', 'status', 'created_at'], 'integer'],
            [['status'], 'validateStatus'],
            [['no', 'pile'], 'string', 'max' => 32],
            [['e', 'bm', 'sm'], 'number'],
            [['rules'], 'string', 'max' => 300],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'no' => '订单编号',
            'pile' => '电桩编号',
            'gun' => '电桩枪口号',
            'uid' => '用户id',
            'e' => '充电电量',
            'bm' => '基础电费',
            'sm' => '服务电费',
            'duration' => '充电时长',
            'rules' => '计费规则',
            'status' => '订单状态',
            'created_at' => '创建时间',
        ];
    }

    public function validateStatus()
    {
        if ($this->status == 0) {
            $session = Gateway::getSessionByUid($this->pile);
            if ($session['status'][$this->gun]['workStatus']) {
                $this->addError('status', '枪口故障中');
            }
            if (!$session['status'][$this->gun]['linkStatus']) {
                $this->addError('status', '枪口未连接');
            }
            if (self::findOne(['pile' => $this->pile, 'gun' => $this->gun, 'status' => [0, 1]])) {
                $this->addError('status', '枪口已占用');
            }
        }
        if ($this->status == 3) {
            EnIncome::count($this->pileInfo->local->no, $this->no, $this->sm);
        }
    }

    /**
     * 关联电桩表
     * @return \yii\db\ActiveQuery
     */
    public function getPileInfo()
    {
        return $this->hasOne(EnPile::class, ['no' => 'pile']);
    }

    /**
     * 后台订单列表数据
     * @return mixed
     */
    public static function getPageData()
    {
        $data = self::find()->alias('o')
            ->leftJoin(EnUser::tableName() . ' u', 'u.id=o.uid')
            ->select(['o.*', 'u.tel', 'u.money'])
            ->page([
                'keywords' => ['like', 'o.no', 'o.pile', 'u.tel'],
                'status' => ['=', 'o.status']
            ]);
        foreach ($data['data'] as &$v) {
            $v['statusV'] = $v['status'];
            $v['status'] = Constant::orderStatus()[$v['status']];
            $v['created'] = $v['created_at'];
            $v['created_at'] = date('Y-m-d H:i:s', $v['created_at']);
            $v['userInfo'] = '充电用户:' . $v['tel'] . '<br>账户余额:' . $v['money'];
            $v['info'] = '基础电费:' . $v['bm'] . '<br>服务电费:' . $v['sm'] . '<br>订单总额:' . round($v['bm'] + $v['sm'], 2);
        }
        return $data;
    }

    /**
     * 订单导出
     */
    public static function export()
    {
        $get = Yii::$app->request->get();
        $orders = self::find()->alias('o')
            ->leftJoin(EnUser::tableName() . ' u', 'u.id=o.uid')
            ->select(['o.*', 'u.tel', 'u.money']);
        if (isset($get['keywords'])) {
            $orders->andFilterWhere(['or', ['like', 'o.no', $get['keywords']], ['like', 'o.pile', $get['keywords']], ['like', 'u.tel', $get['keywords']]]);
        }
        if (isset($get['status'])) {
            $orders->andFilterWhere(['=', 'o.status', $get['status']]);
        }
        $orders = $orders->asArray()->all();
        $data = [];
        foreach ($orders as $v) {
            $info = '基础电费:' . $v['bm'] . '<br>服务电费:' . $v['sm'] . '<br>订单总额:' . round($v['bm'] + $v['sm'], 2);
            $userInfo = '充电用户:' . $v['tel'] . '<br>账户余额:' . $v['money'];
            $created_at = date('Y-m-d H:i:s');
            $status = Constant::orderStatus()[$v['status']];
            array_push($data, [$v['no'], $v['pile'], $v['gun'], $v['e'], $info, $userInfo, $created_at, $status]);
        }
        Helper::excel(['订单编号', '电桩编号', '充电枪口', '充电电量', '费用信息', '用户信息', '创建时间', '订单状态'], $data, '充电订单');
    }

    /**
     * 获取当前用户订单
     * @return array|\yii\db\ActiveRecord[]
     */
    public static function getOrders()
    {
        $orders = self::find()->alias('o')
            ->leftJoin(EnPile::tableName() . ' p', 'p.no=o.pile')
            ->leftJoin(EnField::tableName() . ' f', 'f.no=p.field')
            ->where(['o.uid' => Yii::$app->user->id])
            ->select(['o.*', 'f.name'])
            ->orderBy('o.created_at desc')
            ->asArray()->all();
        foreach ($orders as &$v) {
            $v['e'] = (float)$v['e'];
            $v['money'] = $v['bm'] + $v['sm'];
            $v['created_at'] = date('Y-m-d H:i:s', $v['created_at']);
            $v['status'] = Constant::orderStatus()[$v['status']];
            $v['rules'] = '';
        }
        return $orders;
    }

    /**
     * 报表数据
     * @param string $no
     * @param bool $onlyEle
     * @return array
     */
    public static function reportInfo($no = '', $onlyEle = false)
    {
        $model = EnOrder::find()->alias('o')->leftJoin(EnPile::tableName() . ' p', 'p.no=o.pile');
        if ($no) {
            $model->where(['p.field' => $no]);
        }
        $model1 = clone $model;
        $model2 = clone $model;
        $model3 = clone $model;
        $model4 = clone $model;
        $model5 = clone $model;
        $minYear = $model1->min("FROM_UNIXTIME(o.created_at,'%Y')") ?: date('Y');
        $sum = 'o.bm + o.sm';
        if ($onlyEle) {
            $sum = 'o.bm';
        }
        $data = [
            'all' => round($model2->andWhere(['o.status' => [2, 3]])->sum($sum), 2),
            'year' => round($model3->andWhere(["FROM_UNIXTIME(o.created_at,'%Y')" => date('Y'), 'o.status' => [2, 3]])->sum($sum), 2),
            'month' => round($model4->andWhere(["FROM_UNIXTIME(o.created_at,'%Y-%m')" => date('Y-m'), 'o.status' => [2, 3]])->sum($sum), 2),
            'day' => round($model5->andWhere(["FROM_UNIXTIME(o.created_at,'%Y-%m-%d')" => date('Y-m-d'), 'o.status' => [2, 3]])->sum($sum), 2),
            'years' => array_reverse(range($minYear, date('Y'))),
        ];
        return $data;
    }

    /**
     * 报表数据
     * @param string $year
     * @param string $no
     * @param bool $onlyEle
     * @return array
     */
    public static function yearData($year = '', $no = '', $onlyEle = false)
    {
        $year = $year ?: date('Y');
        $sum = 'SUM(o.bm + o.sm) as money';
        if ($onlyEle) {
            $sum = 'SUM(o.bm) as money';
        }
        $res = self::find()->alias('o')->leftJoin(EnPile::tableName() . ' p', 'p.no=o.pile');
        if ($no) {
            $res->where(['p.field' => $no]);
        }
        $res = $res->andWhere(["FROM_UNIXTIME(o.created_at,'%Y')" => $year, 'o.status' => [2, 3]])
            ->groupBy("month")
            ->select(["FROM_UNIXTIME(o.created_at,'%m') month", $sum])
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
     * @param string $no
     * @param bool $onlyEle
     * @return array
     */
    public static function monthData($year = '', $month = '', $no = '', $onlyEle = false)
    {
        $year = $year ?: date('Y');
        $month = $month ?: date('m');
        $days = cal_days_in_month(CAL_GREGORIAN, $month, $year);
        $data = range(1, $days);
        $days = [];
        $res = self::find()->alias('o')->leftJoin(EnPile::tableName() . ' p', 'p.no=o.pile');
        if ($no) {
            $res->where(['p.field' => $no]);
        }
        $sum = 'SUM(o.bm + o.sm) as money';
        if ($onlyEle) {
            $sum = 'SUM(o.bm) as money';
        }
        $res = $res->andWhere(["FROM_UNIXTIME(o.created_at,'%Y-%m')" => $year . '-' . $month, 'o.status' => [2, 3]])
            ->groupBy("days")
            ->select(["FROM_UNIXTIME(o.created_at,'%d') days", $sum])
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
     * @param string $no
     * @return $this|array|\yii\db\ActiveRecord[]
     */
    public static function statisticsDateData($date = '', $no = '')
    {
        $date = $date ?: date('Y-m');
        $data = self::find()->alias('o')
            ->leftJoin(EnPile::tableName() . ' p', 'p.no=o.pile')
            ->leftJoin(EnUser::tableName() . ' u', 'u.id=o.uid');
        if ($no) {
            $data->where(['p.field' => $no]);
        }
        $data = $data->andWhere(["FROM_UNIXTIME(o.created_at,'%Y-%m-%d')" => $date, 'o.status' => [2, 3]])
            ->select(['o.*', 'u.tel'])
            ->orderBy('o.created_at desc')
            ->asArray()->all();
        foreach ($data as &$v) {
            $v['statusV'] = $v['status'];
            $v['status'] = Constant::orderStatus()[$v['status']];
            $v['created'] = $v['created_at'];
            $v['created_at'] = date('Y-m-d H:i:s', $v['created_at']);
            $v['info'] = '基础电费:' . $v['bm'] . '<br>服务电费:' . $v['sm'] . '<br>订单总额:' . round($v['bm'] + $v['sm'], 2);
        }
        return $data;
    }

    /**
     * 累计24小时报表统计
     * @param string $report ELE 统计电量 / TIMES 统计充电次数
     * @return array
     */
    public static function reportBy24($report = 'ELE')
    {
        $words = '';
        if ($report == 'ELE') {
            $words = 'SUM(e) as val';
        }
        if ($report == 'TIMES') {
            $words = 'count(no) as val';
        }
        $interval = [0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0];
        $data = self::find()->where(['status' => [2, 3]])
            ->select(["FROM_UNIXTIME(created_at,'%k') hours", $words])
            ->groupBy('hours')
            ->asArray()->all();
        foreach ($data as $v) {
            $interval[$v['hours']] = round($v['val'], 2);
        }
        return $interval;
    }

    /**
     * 订单扣款
     * @param string $no
     * @return bool
     */
    public static function deduct($no = '')
    {
        if ($order = self::findOne(['no' => $no, 'status' => 2])) {
            $transaction = \Yii::$app->db->beginTransaction();
            try {
                $order->status = 3;
                $user = EnUser::findOne($order->uid);
                $money = round($order['bm'] + $order['sm'], 2);
                if ($user->money < $money) {
                    throw new Exception('余额不足');
                }
                $user->money -= $money;
                if ($user->save()) {
                    if ($order->save()) {
                        $transaction->commit();
                        Msg::set('支付成功');
                        return true;
                    }
                }
                throw new Exception('系统错误,请稍后再试');
            } catch (Exception $e) {
                $transaction->rollBack();
                Msg::set($e->getMessage());
                return false;
            }
        }
        Msg::set('订单已支付');
        return false;
    }
}
