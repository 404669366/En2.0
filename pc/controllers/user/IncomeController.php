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
        $beginYear = EnIncome::find()->where(['type' => [3, 4], 'key' => \Yii::$app->user->id])->min('created_at') ?: time();
        $endYear = EnIncome::find()->where(['type' => [3, 4], 'key' => \Yii::$app->user->id])->max('created_at') ?: time();
        return $this->render('all.html', [
            'years' => range(date('Y', $endYear), date('Y', $beginYear), 1),
            'data' => EnIncome::incomeDataByCenter()
        ]);
    }

    /**
     * 投资收益
     * @return string
     */
    public function actionInvest()
    {
        $beginYear = EnIncome::find()->where(['type' => [3, 4], 'key' => \Yii::$app->user->id])->min('created_at') ?: time();
        $endYear = EnIncome::find()->where(['type' => [3, 4], 'key' => \Yii::$app->user->id])->max('created_at') ?: time();
        return $this->render('invest.html', [
            'years' => range(date('Y', $endYear), date('Y', $beginYear), 1),
            'data' => EnIncome::incomeDataByCenter(4)
        ]);
    }

    /**
     * 场地收益
     * @return string
     */
    public function actionField()
    {
        $beginYear = EnIncome::find()->where(['type' => [3, 4], 'key' => \Yii::$app->user->id])->min('created_at') ?: time();
        $endYear = EnIncome::find()->where(['type' => [3, 4], 'key' => \Yii::$app->user->id])->max('created_at') ?: time();
        return $this->render('field.html', [
            'years' => range(date('Y', $endYear), date('Y', $beginYear), 1),
            'data' => EnIncome::incomeDataByCenter(3)
        ]);
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