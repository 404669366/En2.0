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
     * @return string
     */
    public function actionDetail($no = '')
    {
        if ($no) {
            if ($detail = EnField::findOne(['no' => $no])) {
                return $this->render('detail', [
                    'detail' => $detail,
                    'intro' => \Yii::$app->cache->get('FieldIntro-' . $detail->id),
                ]);
            }
        }
        return $this->redirect(['basis/basis/error']);
    }
}