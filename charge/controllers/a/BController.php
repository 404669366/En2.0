<?php
/**
 * Created by PhpStorm.
 * User: miku
 * Date: 2020/01/13
 * Time: 11:33
 */

namespace app\controllers\a;


use app\controllers\basis\AuthController;
use vendor\project\base\EnBargain;
use vendor\project\base\EnBargainRecord;
use vendor\project\base\EnOrder;
use vendor\project\helpers\Constant;
use vendor\project\helpers\Msg;

class BController extends AuthController
{
    /**
     * 创建砍价
     * @param string $no
     * @return \yii\web\Response
     */
    public function actionCreate($no = '')
    {
        $model = EnBargain::find()
            ->where(['type' => 1, 'key' => $no, 'user_id' => \Yii::$app->user->id])
            ->one();
        if (!$model) {
            if ($price = (string)round(EnOrder::find()->where(['no' => $no])->sum('bm + sm'), 2)) {
                $model = new EnBargain();
                $model->type = 1;
                $model->key = $no;
                $model->user_id = \Yii::$app->user->id;
                $model->price = (string)round(EnOrder::find()->where(['no' => $no])->sum('bm + sm'), 2);
                $model->count = Constant::bargainRule($model->price);
                $model->created_at = time();
                $model->save();
                return $this->redirect(['i', 'id' => $model->id]);
            }
            Msg::set('参与免单订单价格必须大于0元');
            return $this->redirect(['order/charge/list']);
        }
        return $this->redirect(['i', 'id' => $model->id]);
    }

    /**
     * 砍价页
     * @param int $id
     * @return string
     */
    public function actionI($id = 0)
    {
        return $this->render('info.html', EnBargain::getOrderBargain($id));
    }

    /**
     * 砍价
     * @param int $id
     * @return string
     */
    public function actionDo($id = 0)
    {
        $re = EnBargainRecord::bargain($id);
        return $this->rJson($re['data'], $re['type'], $re['msg']);
    }
}