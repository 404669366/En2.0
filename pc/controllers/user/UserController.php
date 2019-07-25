<?php
/**
 * Created by PhpStorm.
 * User: miku
 * Date: 2019/6/25
 * Time: 9:20
 */

namespace app\controllers\user;


use app\controllers\basis\AuthController;

class UserController extends AuthController
{
    /**
     * 个人中心
     * @return string
     */
    public function actionCenter()
    {
        if (isset($_COOKIE['user-show']) && $_COOKIE['user-show']) {
            return $this->redirect($_COOKIE['user-show']);
        }
        return $this->redirect(['user/intention/list']);
    }
}