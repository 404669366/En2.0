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
use vendor\project\helpers\Constant;

class NewsController extends BasisController
{
    /**
     * 新闻列表
     * @return string
     */
    public function actionList()
    {
        return $this->render('list.html', ['data' => EnNews::getData()]);
    }

    /**
     * 新闻详情
     * @param $id
     * @return string
     */
    public function actionDetail($id)
    {
        $news = EnNews::find()->where(['id' => $id])->asArray()->one();
        return $this->render('detail.html', [
            'news' => $news,
            'content' => \Yii::$app->cache->get('EnNewsContent_' . $id),
            'source' => Constant::newsSource()[$news['source']],
        ]);
    }
}