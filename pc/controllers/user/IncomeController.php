<?php
/**
 * Created by PhpStorm.
 * User: miku
 * Date: 2019/9/24
 * Time: 18:15
 */

namespace app\controllers\user;


use vendor\project\base\EnIncome;

class IncomeController extends UserController
{
    /**
     * 我的收益
     * @return string
     */
    public function actionList()
    {
        return $this->render('list.html', ['data' => EnIncome::listDataByCenter()]);
    }

    /**
     * 总共收益
     * @return string
     */
    public function actionAll()
    {
        return $this->render('all.html', EnIncome::incomeByCenter());
    }

    /**
     * 投资收益
     * @return string
     */
    public function actionInvest()
    {
        return $this->render('invest.html', EnIncome::incomeByCenter());
    }

    /**
     * 场地收益
     * @return string
     */
    public function actionField()
    {
        return $this->render('field.html', EnIncome::incomeByCenter());
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