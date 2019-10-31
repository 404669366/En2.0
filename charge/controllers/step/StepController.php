<?php
/**
 * Created by PhpStorm.
 * User: miku
 * Date: 2019/9/6
 * Time: 11:37
 */

namespace app\controllers\step;


use app\controllers\basis\AuthController;
use vendor\project\base\EnField;
use vendor\project\helpers\Wechat;

class StepController extends AuthController
{
    /**
     * 浏览记录
     * @return string
     */
    public function actionList()
    {
        return $this->render('list.html', [
            'nowCut0' => date('Ymd'),
            'nowCut1' => date('Ymd', strtotime("-1 day")),
            'nowCut2' => date('Ymd', strtotime("-2 day")),
            'jsApi' => Wechat::getJsApiParams(),
            '_csrf' => \Yii::$app->request->csrfToken
        ]);
    }

    /**
     * 获取枪口信息
     * @return string
     */
    public function actionGuns()
    {
        return $this->rJson(EnField::getStepInfo(\Yii::$app->request->post('nos', [])));
    }
}