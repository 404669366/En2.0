<?php
/**
 * Created by PhpStorm.
 * User: miku
 * Date: 2019/7/9
 * Time: 9:39
 */

namespace app\controllers\field;


use app\controllers\basis\AuthController;
use vendor\project\base\EnField;

class BuyController extends AuthController
{
    public function actionBuy($no = '')
    {
        if ($detail = EnField::findOne(['status' => 4, 'no' => $no])) {
            if(\Yii::$app->request->isPost){
                $post = \Yii::$app->request->post();
                var_dump($post);exit();
            }
            return $this->render('add', ['detail' => $detail]);
        }
        return $this->goBack('错误操作');
    }
}