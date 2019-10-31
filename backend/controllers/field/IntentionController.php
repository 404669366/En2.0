<?php
/**
 * Created by PhpStorm.
 * User: miku
 * Date: 2019/6/17
 * Time: 18:00
 */

namespace app\controllers\field;


use app\controllers\basis\CommonController;
use vendor\project\base\EnField;
use vendor\project\base\EnIntention;
use vendor\project\base\EnMember;
use vendor\project\helpers\Helper;
use vendor\project\helpers\Msg;

class IntentionController extends CommonController
{
    /**
     * 列表页
     * @return string
     */
    public function actionList()
    {
        return $this->render('list', ['count' => EnIntention::getRobCount()]);
    }

    /**
     * 列表数据
     * @return string
     */
    public function actionData()
    {
        return $this->rTableData(EnIntention::getPageData(\Yii::$app->user->id));
    }
}