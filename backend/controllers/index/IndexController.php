<?php
/**
 * Created by PhpStorm.
 * User: miku
 * Date: 2019/5/29
 * Time: 14:34
 */

namespace app\controllers\index;


use app\controllers\basis\AuthController;
use vendor\project\base\EnField;
use vendor\project\base\EnJob;
use vendor\project\base\EnMember;
use vendor\project\base\EnPower;

class IndexController extends AuthController
{

    public function actionIndex()
    {
        $this->layout = false;
        $user = EnMember::findOne(\Yii::$app->user->id);
        $data['tel'] = $user->tel;
        $data['job'] = $user->job_id ? $user->job->name : '管理员';
        $data['company'] = $user->company_id ? $user->company->name : '系统账户';
        $data['logo'] = $user->company_id ? $user->company->logo : '/img/profile_small.jpg';
        $data['menus'] = EnPower::getUserMenus();
        return $this->render('index', ['data' => $data]);
    }

    public function actionFirst($key = '')
    {
        return $this->render('first', [
            'data' => json_encode(EnField::getMapData($key)),
            'key' => $key
        ]);
    }
}