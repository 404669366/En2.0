<?php
/**
 * Created by PhpStorm.
 * User: miku
 * Date: 2019/6/24
 * Time: 9:12
 */

namespace app\controllers\web;


use app\controllers\basis\CommonController;
use vendor\project\base\EnNews;
use vendor\project\helpers\Msg;
use vendor\project\helpers\redis;

class NewsController extends CommonController
{
    /**
     * 列表页
     * @return string
     */
    public function actionList()
    {
        return $this->render('list');
    }

    /**
     * 列表数据
     * @return string
     */
    public function actionData()
    {
        return $this->rTableData(EnNews::getPageData());
    }

    /**
     * 新增和编辑
     * @param int $id
     * @return string|\yii\web\Response
     */
    public function actionDo($id = 0)
    {
        if ($id) {
            $model = EnNews::findOne($id);
            $content = \Yii::$app->cache->get('EnNewsContent_' . $id);
        } else {
            $model = new EnNews();
            $model->created_at = time();
            $content = '';
        }
        if (\Yii::$app->request->isPost) {
            $post = \Yii::$app->request->post();
            Msg::set('新闻详情不能为空');
            if (isset($post['content']) && $post['content']) {
                $content = $post['content'];
                if ($model->load(['EnNews' => $post]) && $model->validate() && $model->save()) {
                    \Yii::$app->cache->set('EnNewsContent_' . $id, $content);
                    Msg::set('操作成功');
                    return $this->redirect(['list']);
                }
                Msg::set($model->errors());
            }
        }
        return $this->render('do', ['model' => $model, 'content' => $content]);
    }

    /**
     * 删除
     * @param $id
     * @return \yii\web\Response
     */
    public function actionDel($id)
    {
        Msg::set('错误操作');
        if ($model = EnNews::findOne($id)) {
            $model->delete();
            \Yii::$app->cache->delete('EnNewsContent_' . $id);
            Msg::set('删除成功');
        }
        return $this->redirect(['list']);
    }
}