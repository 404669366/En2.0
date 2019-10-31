<?php
/**
 * Created by PhpStorm.
 * User: miku
 * Date: 2019/9/24
 * Time: 17:37
 */

namespace app\controllers\user;


use vendor\project\base\EnField;
use vendor\project\base\EnIntention;

class IntentionController extends UserController
{
    /**
     * 意向列表
     * @return string
     */
    public function actionList()
    {
        return $this->render('list.html', ['data' => EnIntention::listDataByCenter()]);
    }

    /**
     * 申请退款
     * @param $no
     * @return \yii\web\Response
     */
    public function actionReturn($no)
    {
        $model = EnIntention::find()->alias('i')
            ->leftJoin(EnField::tableName() . ' f', 'f.no=i.field')
            ->where([
                'i.no' => $no,
                'i.status' => 1,
                'i.user_id' => \Yii::$app->user->id,
                'f.status' => 3
            ])
            ->one();
        if ($model) {
            $model->status = 2;
            if ($model->save()) {
                return $this->goBack('申请成功');
            }
        }
        return $this->goBack('非法操作');
    }
}