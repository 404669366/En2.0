<?php
/**
 * Created by PhpStorm.
 * User: miku
 * Date: 2019/6/3
 * Time: 17:24
 */

namespace app\controllers\user;


use app\controllers\basis\CommonController;
use vendor\project\base\EnUser;

class UserController extends CommonController
{
    /**
     * 用户列表页
     * @return string
     */
    public function actionList()
    {
        return $this->render('list');
    }

    /**
     * 返回分页数据
     * @return string
     */
    public function actionData()
    {
        return $this->rTableData(EnUser::getPageData());
    }
}