<?php
/**
 * Created by PhpStorm.
 * User: miku
 * Date: 2019/5/30
 * Time: 16:14
 */

namespace app\controllers\job;


use app\controllers\basis\CommonController;
use vendor\project\base\EnJob;
use vendor\project\base\EnPower;
use vendor\project\helpers\Msg;

class JobController extends CommonController
{
    /**
     * 职位管理主页面
     * @return string
     */
    public function actionInfo()
    {
        return $this->render('info', [
            'relation' => EnJob::getJobRelation(),
            'powers' => EnPower::getTreeData()
        ]);
    }

    /**
     * 获取职位信息
     * @param $id
     * @return string
     */
    public function actionGetJob($id)
    {
        if ($one = EnJob::getOne($id)) {
            return $this->rJson($one);
        }
        return $this->rJson('', false, '职位不存在');
    }

    /**
     * 保存职位关系
     * @param $data
     * @param $map
     * @return string
     */
    public function actionSave($data, $map)
    {
        if (EnJob::saveJobRelation($data, $map)) {
            return $this->rJson();
        }
        return $this->rJson('', false, '职位关系保存错误');
    }

    /**
     * 数据操作
     * @param int $id
     * @return \yii\web\Response
     */
    public function actionDo($id = 0)
    {
        if (\Yii::$app->request->isPost) {
            $model = new EnJob();
            if ($id) {
                $model = $model::findOne($id);
            }
            $post = \Yii::$app->request->post();
            if ($model->load(['EnJob' => $post]) && $model->validate() && $model->save()) {
                Msg::set('保存成功');
            } else {
                Msg::set($model->errors());
            }
        }
        return $this->redirect(['info']);
    }

    /**
     * 删除职位
     * @param $id
     * @return \yii\web\Response
     */
    public function actionDel($id)
    {
        Msg::set('存在下级不能直接删除');
        if (EnJob::delJob($id)) {
            Msg::set('删除成功');
        }
        return $this->redirect(['info']);
    }
}