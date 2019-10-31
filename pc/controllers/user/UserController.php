<?php
/**
 * Created by PhpStorm.
 * User: miku
 * Date: 2019/6/25
 * Time: 9:20
 */

namespace app\controllers\user;


use app\controllers\basis\AuthController;
use vendor\project\base\EnField;
use vendor\project\base\EnIncome;
use vendor\project\helpers\Constant;

class UserController extends AuthController
{
    public function render($view, $params = [])
    {
        $params['all'] = EnIncome::getAll([3, 4], \Yii::$app->user->id);
        $params['out'] = EnIncome::getOut(2, \Yii::$app->user->id);
        $params['surplus'] = EnIncome::getSurplus([3, 4], 2, \Yii::$app->user->id);
        return parent::render($view, $params);
    }

    /**
     * 个人中心
     * @return string
     */
    public function actionCenter()
    {
        return $this->render('center.html', ['data' => EnField::listDataByCenter()]);
    }
}