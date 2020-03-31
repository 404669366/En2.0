<?php
/**
 * Created by PhpStorm.
 * User: 40466
 * Date: 2020/03/27
 * Time: 15:25
 */

namespace app\controllers\finance;


use app\controllers\basis\CommonController;
use vendor\project\base\EnInvest;
use vendor\project\base\EnOrder;
use vendor\project\base\EnUser;

class AllController extends CommonController
{
    public function actionAll()
    {
        return $this->render('all', [
            'consume' => [
                'all' => round(EnOrder::find()->where(['status' => [2, 3]])->sum('bm+sm'), 2),
                'today' => round(EnOrder::find()->where(["FROM_UNIXTIME(created_at,'%Y-%m-%d')" => date('Y-m-d'), 'status' => [2, 3]])->sum('bm+sm'), 2),
                'report' => json_encode(EnOrder::monthData())
            ],
            'invest' => [
                'all' => round(EnInvest::find()->where(['status' => 1])->sum('money'), 2),
                'today' => round(EnInvest::find()->where(["FROM_UNIXTIME(created_at,'%Y-%m-%d')" => date('Y-m-d'), 'status' => 1])->sum('money'), 2),
                'report' => json_encode(EnInvest::monthData())
            ],
            'ele' => [
                'all' => round(EnOrder::find()->where(['status' => [2, 3]])->sum('bm'), 2),
                'today' => round(EnOrder::find()->where(["FROM_UNIXTIME(created_at,'%Y-%m-%d')" => date('Y-m-d'), 'status' => [2, 3]])->sum('bm'), 2)
            ],
            'charge' => [
                'all' => round(EnOrder::find()->where(['status' => [2, 3]])->sum('e'), 2),
                'today' => round(EnOrder::find()->where(["FROM_UNIXTIME(created_at,'%Y-%m-%d')" => date('Y-m-d'), 'status' => [2, 3]])->sum('e'), 2),
                'report' => json_encode(EnOrder::reportBy24())
            ],
            'times' => [
                'all' => EnOrder::find()->count(),
                'today' => EnOrder::find()->where(["FROM_UNIXTIME(created_at,'%Y-%m-%d')" => date('Y-m-d')])->count(),
                'report' => json_encode(EnOrder::reportBy24('TIMES'))
            ],
            'user' => [
                'all' => EnUser::find()->count(),
                'today' => EnUser::find()->where(["FROM_UNIXTIME(created_at,'%Y-%m-%d')" => date('Y-m-d')])->count()
            ],
        ]);
    }
}