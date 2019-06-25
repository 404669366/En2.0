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

class IndexController extends BasisController
{
    /**
     * 渲染首页
     * @return string
     */
    public function actionIndex()
    {
        return $this->render('index', [
            'field' => EnField::indexData(),
            'news' => EnNews::indexData()
        ]);
    }
}