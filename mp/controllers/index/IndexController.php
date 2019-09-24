<?php
/**
 * Created by PhpStorm.
 * User: miku
 * Date: 2019/9/19
 * Time: 17:08
 */

namespace app\controllers\index;


use app\controllers\basis\BasisController;
use vendor\project\base\EnField;
use vendor\project\helpers\Constant;

class IndexController extends BasisController
{
    /**
     * 首页
     * @return string
     */
    public function actionIndex()
    {
        return $this->render('index.html', [
            'banner' => Constant::bannerImg(),
            'fields' => EnField::listDataByMp(\Yii::$app->request->get('type', 1), 4)
        ]);
    }
}