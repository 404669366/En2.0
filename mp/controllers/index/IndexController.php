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
     * @param int $type
     * @return string
     */
    public function actionIndex($type = 1)
    {
        return $this->render('index.html', [
            'banner' => Constant::bannerImg(),
            'fields' => EnField::recommendDataByMp($type, 4),
            'serviceTel' => Constant::serviceTel()
        ]);
    }
}