<?php
/**
 * Created by PhpStorm.
 * User: miku
 * Date: 2019/9/19
 * Time: 17:10
 */

namespace app\controllers\field;


use app\controllers\basis\BasisController;
use vendor\project\base\EnField;
use vendor\project\base\EnMember;

class FieldController extends BasisController
{
    /**
     * 场地列表页
     * @return string
     */
    public function actionList()
    {
        return $this->render('list.html', [
            'fields' => EnField::listDataByMp(\Yii::$app->request->get('type', 1), \Yii::$app->request->get('search', ''))
        ]);
    }

    /**
     * 场地详情
     * @param string $no
     * @return string
     */
    public function actionDetail($no = '')
    {
        $field = EnField::find()->alias('f')
            ->leftJoin(EnMember::tableName() . ' m', 'm.id=f.commissioner_id')
            ->select(['f.*', 'm.tel'])
            ->where(['f.no' => $no])
            ->asArray()->one();
        return $this->render('detail.html', [
            'field' => $field,
            'images' => explode(',', $field['images']),
            'intro' => \Yii::$app->cache->get('FieldIntro-' . $field['id']),
            'investInfo' => \Yii::$app->cache->get('InvestInfo-' . $field['invest_type']) ?: ''
        ]);
    }
}