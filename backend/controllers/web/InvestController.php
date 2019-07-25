<?php
/**
 * Created by PhpStorm.
 * User: miku
 * Date: 2019/7/25
 * Time: 14:39
 */

namespace app\controllers\web;


use app\controllers\basis\CommonController;
use vendor\project\helpers\Constant;
use vendor\project\helpers\Msg;

class InvestController extends CommonController
{
    /**
     * 投资类型列表
     * @return string
     */
    public function actionList()
    {
        return $this->render('list', ['data' => Constant::investType()]);
    }

    /**
     * 投资类型编辑
     * @param $k
     * @return string|\yii\web\Response
     */
    public function actionEdit($k)
    {
        if (\Yii::$app->request->isPost) {
            $post = \Yii::$app->request->post();
            Msg::set('请编辑投资类型描述');
            if ($post['info']) {
                \Yii::$app->cache->set('InvestInfo-' . $k, $post['info']);
                Msg::set('保存成功');
                return $this->redirect(['list']);
            }
        }
        return $this->render('edit', [
            'name' => Constant::investType()[$k],
            'info' => \Yii::$app->cache->get('InvestInfo-' . $k) ?: '',
        ]);
    }
}