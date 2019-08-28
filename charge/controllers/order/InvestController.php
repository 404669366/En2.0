<?php
/**
 * Created by PhpStorm.
 * User: miku
 * Date: 2019/8/21
 * Time: 12:45
 */

namespace app\controllers\order;


use app\controllers\basis\AuthController;
use vendor\project\base\EnInvest;
use vendor\project\base\EnUser;

class InvestController extends AuthController
{
    /**
     * 充值余额
     * @return string
     */
    public function actionInvest()
    {
        return $this->render('invest.html', [
            'money' => EnUser::getMoney() ?: '0.00',
        ]);
    }

    /**
     * 发起充值
     * @param int $money
     * @param int $way
     * @return string
     */
    public function actionCreate($money = 0, $way = 1)
    {
        if ($data = EnInvest::invest($money, $way)) {
            return $this->rJson($data);
        }
        return $this->rJson([], false);
    }

    /**
     * 充值记录
     * @return string
     */
    public function actionList()
    {
        return $this->render('list.html', [
            'data' => EnInvest::getUserInvest(),
            'now' => date('Y-m-d H:i:s')
        ]);
    }
}