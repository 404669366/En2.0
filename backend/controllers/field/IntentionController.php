<?php
/**
 * Created by PhpStorm.
 * User: miku
 * Date: 2019/6/17
 * Time: 18:00
 */

namespace app\controllers\field;


use app\controllers\basis\CommonController;
use vendor\project\base\EnIntention;
use vendor\project\helpers\Constant;

class IntentionController extends CommonController
{
    /**
     * 意向列表页
     * @return string
     */
    public function actionList()
    {
        return $this->render('list', [
            'status' => Constant::intentionStatus(),
            'fStatus' => Constant::fieldStatus(),
        ]);
    }

    /**
     * 意向列表数据
     * @return string
     */
    public function actionData()
    {
        return $this->rTableData(EnIntention::getPageData());
    }
}