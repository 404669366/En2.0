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

class FieldController extends BasisController
{
    /**
     * 项目列表
     * @return string
     */
    public function actionList()
    {
        return $this->render('list', ['data' => EnField::listData()]);
    }

    /**
     * 项目详情
     * @param string $no
     * @param string $r
     * @return string|\yii\web\Response
     */
    public function actionDetail($no = '', $r = '')
    {
        if ($detail = EnField::findOne(['no' => $no, 'status' => [1, 2, 3, 4, 5]])) {
            if ($r) {
                setcookie('field-r-' . $detail->id, $r, time() + 3600 * 24 * 30, '/');
            }
            return $this->render('detail', [
                'detail' => $detail,
                'intro' => \Yii::$app->cache->get('FieldIntro-' . $detail->id),
            ]);
        }
        return $this->redirect(['basis/basis/error']);
    }
}