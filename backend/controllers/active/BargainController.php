<?php
/**
 * Created by PhpStorm.
 * User: miku
 * Date: 2020/01/16
 * Time: 16:48
 */

namespace app\controllers\active;


use app\controllers\basis\CommonController;
use vendor\project\base\EnBargain;

class BargainController extends CommonController
{
    /**
     * 砍价
     * @return string
     */
    public function actionList()
    {
        return $this->render('list');
    }

    /**
     * 砍价数据
     * @return string
     */
    public function actionData()
    {
        return $this->rTableData(EnBargain::getPageData());
    }
}