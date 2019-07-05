<?php
/**
 * Created by PhpStorm.
 * User: miku
 * Date: 2019/6/24
 * Time: 14:32
 */

namespace app\controllers\user;


use app\controllers\basis\AuthController;
use vendor\project\helpers\Msg;

class FieldController extends AuthController
{
    /**
     * 发起项目
     * @return string
     */
    public function actionCreate()
    {
        return $this->render('create');
    }

    public function actionBuy($no = '')
    {
        Msg::set('认购成功');
        return $this->redirect('/field/field/detail.html?no=' . $no);
    }
}