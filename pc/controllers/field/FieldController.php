<?php
/**
 * Created by PhpStorm.
 * User: miku
 * Date: 2019/6/24
 * Time: 14:30
 */

namespace app\controllers\field;


use app\controllers\basis\BasisController;
use vendor\project\base\EnField;

class FieldController extends BasisController
{
    /**
     * 项目列表
     * @return string
     */
    public function actionList()
    {
        return $this->render('list', ['data' => EnField::listData()]);
    }

    /**
     * 项目详情
     * @return string
     */
    public function actionDetail()
    {
        return $this->render('detail');
    }
}