<?php
/**
 * Created by PhpStorm.
 * User: miku
 * Date: 2019/5/29
 * Time: 14:34
 */

namespace app\controllers\index;


use app\controllers\basis\BasisController;
use vendor\project\base\EnField;
use vendor\project\base\EnNews;
use vendor\project\helpers\Constant;

class IndexController extends BasisController
{
    /**
     * 渲染首页
     * @return string
     */
    public function actionIndex()
    {
        return $this->render('index.html', [
            'field' => EnField::recommendDataByPC(6),
            'news' => EnNews::indexData(),
            'goDay' => Constant::goDay(),
            'userCount' => Constant::userCount(),
            'fieldCount' => Constant::fieldCount(),
            'amountCount' => Constant::amountCount(),
        ]);
    }
}