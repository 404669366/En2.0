<?php
/**
 * Created by PhpStorm.
 * User: miku
 * Date: 2019/9/24
 * Time: 17:37
 */

namespace app\controllers\user;


use app\controllers\basis\AuthController;
use vendor\project\base\EnField;
use vendor\project\base\EnIntention;
use vendor\project\helpers\Helper;
use vendor\project\helpers\Msg;

class IntentionController extends AuthController
{
    /**
     * 添加意向
     * @param string $no
     * @return \yii\web\Response
     */
    public function actionAdd($no = '')
    {
        if ($detail = EnField::findOne(['status' => 4, 'no' => $no])) {
            $model = new EnIntention();
            $model->no = Helper::createNo('I');
            $model->user = \Yii::$app->user->identity->tel;
            $model->created_at = time();
            $model->source = 1;
            $model->status = 1;
            $model->commissioner_id = 0;
            $model->field = $no;
            if ($model->save()) {
                Msg::set('意向保存成功,专员将尽快与您联系');
                return $this->redirect(['user/intention/list']);
            }
            return $this->goBack($model->errors());
        }
        return $this->goBack('该场站已完成融资');
    }

    /**
     * 意向列表
     * @return string
     */
    public function actionList()
    {
        return $this->render('list.html', []);
    }
}