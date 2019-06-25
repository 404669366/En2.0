<?php
/**
 * Created by PhpStorm.
 * User: miku
 * Date: 2019/6/24
 * Time: 10:27
 */

namespace app\controllers\news;


use app\controllers\basis\BasisController;
use vendor\project\base\EnNews;

class NewsController extends BasisController
{
    /**
     * 新闻列表
     * @return string
     */
    public function actionList()
    {
        return $this->render('list', ['data' => EnNews::getData()]);
    }

    /**
     * 新闻详情
     * @param $id
     * @return string
     */
    public function actionDetail($id)
    {
        return $this->render('detail', [
            'model' => EnNews::findOne($id),
            'content' => \Yii::$app->cache->get('EnNewsContent_' . $id)
        ]);
    }
}