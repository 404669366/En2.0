<?php

/**
 * Created by PhpStorm.
 * User: miku
 * Date: 2019/5/30
 * Time: 15:31
 */

namespace app\controllers\basis;

use vendor\project\base\EnPower;
use vendor\project\helpers\Msg;

class AuthController extends BasisController
{
    public function beforeAction($action)
    {
        $re = parent::beforeAction($action);
        if (\Yii::$app->user->isGuest) {
            Msg::set('请先登录');
            return $this->redirect([\Yii::$app->params['loginRoute']])->send();
        }
        return $re;
    }

    public function renderContent($content)
    {
        $layoutFile = $this->findLayoutFile($this->getView());
        if ($layoutFile !== false) {
            return $this->getView()->renderFile($layoutFile, ['content' => $content, 'btns' => EnPower::getPowersByType(\Yii::$app->user->id, 2)], $this);
        }

        return $content;
    }
}
