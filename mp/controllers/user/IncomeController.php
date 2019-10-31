<?php
/**
 * Created by PhpStorm.
 * User: miku
 * Date: 2019/9/24
 * Time: 18:15
 */

namespace app\controllers\user;


use app\controllers\basis\AuthController;
use vendor\project\base\EnIncome;

class IncomeController extends AuthController
{
    /**
     * 我的收益
     * @return string
     */
    public function actionList()
    {
        return $this->render('list.html', [
            'data' => EnIncome::listDataByCenter(),
            'all' => EnIncome::getAll([3, 4], \Yii::$app->user->id)
        ]);
    }

    /**
     * 收益分析
     * @return string
     */
    public function actionIncome()
    {
        return $this->render('income.html', EnIncome::incomeByCenter());
    }

    /**
     * 收益数据
     * @param string $type
     * @param int $year
     * @return string
     */
    public function actionData($type = '', $year = 0)
    {
        return $this->rJson(EnIncome::incomeDataByCenter(explode(',', $type), $year));
    }
}