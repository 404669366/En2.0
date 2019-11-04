<?php

namespace vendor\project\base;

use vendor\project\helpers\client;
use vendor\project\helpers\Constant;
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
            'status' => '订单状态',
            'created_at' => '创建时间',
        ];
    }

    public function validateStatus()
    {
        if ($this->status == 0) {
            if (self::findOne(['pile' => $this->pile, 'gun' => $this->gun, 'status' => [0, 1]])) {
                $this->addError('status', '枪口已占用,请稍后再试');
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
            ->select(['o.*', 'u.tel'])
            ->page([
                'keywords' => ['like', 'o.no', 'o.pile', 'u.tel'],
                'status' => ['=', 'o.status']
            ]);
        foreach ($data['data'] as &$v) {
            $v['status'] = Constant::orderStatus()[$v['status']];
            $v['created_at'] = date('Y-m-d H:i:s', $v['created_at']);
            $v['info'] = '基础电费:' . $v['bm'] . '<br>服务电费:' . $v['sm'] . '<br>订单总额:' . round($v['bm'] + $v['sm'], 2);
        }
        return $data;
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
        }
        return $orders;
    }

    /**
     * 报表数据
     * @return array
     */
    public static function reportInfo()
    {
        $minYear = self::find()->min("FROM_UNIXTIME(created_at,'%Y')") ?: date('Y');
        $data = [
            'allOrder' => round(self::find()->where(['status' => [2, 3]])->sum('bm + sm'), 2),
            'yearOrder' => round(self::find()->where(["FROM_UNIXTIME(created_at,'%Y')" => date('Y'), 'status' => [2, 3]])->sum('bm + sm'), 2),
            'monthOrder' => round(self::find()->where(["FROM_UNIXTIME(created_at,'%Y-%m')" => date('Y-m'), 'status' => [2, 3]])->sum('bm + sm'), 2),
            'dayOrder' => round(self::find()->where(["FROM_UNIXTIME(created_at,'%Y-%m-%d')" => date('Y-m-d'), 'status' => [2, 3]])->sum('bm + sm'), 2),
            'years' => array_reverse(range($minYear, date('Y'))),
        ];
        return $data;
    }

    /**
     * 报表数据
     * @param string $year
     * @return array
     */
    public static function reportData($year = '')
    {
        $year = $year ?: date('Y');
        $data = ['-01', '-02', '-03', '-04', '-05', '-06', '-07', '-08', '-09', '-10', '-11', '-12'];
        foreach ($data as &$v) {
            $v = round(self::find()->where(["FROM_UNIXTIME(created_at,'%Y-%m')" => $year . $v, 'status' => [2, 3]])->sum('bm + sm'), 2);
        }
        return $data;
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
