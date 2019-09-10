<?php
/**
 * Created by PhpStorm.
 * User: miku
 * Date: 2019/5/29
 * Time: 14:34
 */

namespace app\controllers\index;


use app\controllers\basis\AuthController;
use vendor\project\base\EnJob;
use vendor\project\base\EnPower;

class IndexController extends AuthController
{

    public function actionIndex()
    {
        $this->layout = false;
        $data['tel'] = \Yii::$app->user->getIdentity()->tel;
        $job = EnJob::findOne(\Yii::$app->user->getIdentity()->job_id);
        $data['job'] = $job ? $job->name : 'root';
        $data['company'] = $job ? $job->company->name : '----';
        $data['logo'] = $job ? $job->company->logo : '/img/profile_small.jpg';
        $data['menus'] = EnPower::getUserMenus();
        return $this->render('index', ['data' => $data]);
    }

    public function actionFirst()
    {
        return $this->render('first');
    }
}