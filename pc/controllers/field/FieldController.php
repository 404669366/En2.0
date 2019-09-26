<?php
/**
 * Created by PhpStorm.
 * User: miku
 * Date: 2019/6/24
 * Time: 14:30
 */

namespace app\controllers\field;


use app\controllers\basis\BasisController;
use vendor\project\base\EnField;
use vendor\project\helpers\Constant;
use vendor\project\helpers\Helper;

class FieldController extends BasisController
{
    /**
     * 项目列表
     * @return string
     */
    public function actionList()
    {
        return $this->render('list.html', [
            'data' => EnField::listDataByPc(),
            'businessType' => Helper::arrayKeyToV(Constant::businessType()),
            'investType' => Helper::arrayKeyToV(Constant::investType()),
        ]);
    }

    /**
     * 项目详情
     * @param string $no
     * @param string $r
     * @return string|\yii\web\Response
     */
    public function actionDetail($no = '', $r = '')
    {
        if ($detail = EnField::find()->where(['no' => $no, 'status' => [1, 2, 3, 4, 5]])->asArray()->one()) {
            if ($r) {
                setcookie('field-r-' . $detail['id'], $r, time() + 3600 * 24 * 30, '/');
            }
            return $this->render('detail.html', [
                'detail' => $detail,
                'images' => Helper::completionImg($detail['images']),
                'intro' => \Yii::$app->cache->get('FieldIntro-' . $detail['id']),
                'investInfo' => \Yii::$app->cache->get('InvestInfo-' . $detail['invest_type']) ?: '',
                'business_type' => Constant::businessType()[$detail['business_type']],
                'invest_type' => Constant::investType()[$detail['invest_type']],
            ]);
        }
        return $this->redirect(['basis/basis/error']);
    }
}