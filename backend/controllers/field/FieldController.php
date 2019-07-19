<?php
/**
 * Created by PhpStorm.
 * User: miku
 * Date: 2019/6/12
 * Time: 13:58
 */

namespace app\controllers\field;


use app\controllers\basis\CommonController;
use vendor\project\base\EnField;
use vendor\project\base\EnMember;
use vendor\project\helpers\Helper;
use vendor\project\helpers\Msg;

class FieldController extends CommonController
{
    /**
     * 场地列表页
     * @return string
     */
    public function actionList()
    {
        return $this->render('list', ['count' => EnField::getNeedFieldCount()]);
    }

    /**
     * 场地列表数据页
     * @return string
     */
    public function actionData()
    {
        return $this->rTableData(EnField::getPageData());
    }

    /**
     * 编辑场地
     * @param int $id
     * @return string|\yii\web\Response
     */
    public function actionEdit($id = 0)
    {
        $model = EnField::findOne($id);
        if (!$model) {
            $model = new EnField();
            $model->no = Helper::createNo('F');
            $model->commissioner_id = \Yii::$app->user->id;
            $model->source = 2;
            $model->created = \Yii::$app->user->id;
            $model->created_at = time();
        } else {
            $model->intro = \Yii::$app->cache->get('FieldIntro-' . $model->id);
        }
        if (\Yii::$app->request->isPost) {
            if ($model->load(['EnField' => \Yii::$app->request->post()]) && $model->validate() && $model->save()) {
                Msg::set('操作成功');
                return $this->redirect(['list']);
            }
            Msg::set($model->errors());
        }
        return $this->render('edit', [
            'model' => $model,
        ]);
    }

    /**
     * 查看详情
     * @param $id
     * @return string
     */
    public function actionInfo($id)
    {
        return $this->render('info', ['model' => EnField::findOne($id)]);
    }

    /**
     * 用户绑定搜索
     * @param $tel
     * @return string
     */
    public function actionUserSearch($tel)
    {
        if ($data = EnField::userSearch($tel)) {
            return $this->rJson($data);
        }
        return $this->rJson([], false);
    }

    /**
     * 指派专员
     * @param $id
     * @param $tel
     * @return string
     */
    public function actionAppoint($id, $tel)
    {
        if ($model = EnField::findOne(['id' => $id, 'commissioner_id' => \Yii::$app->user->id])) {
            if ($commissioner = EnMember::findOne(['tel' => $tel])) {
                if ($commissioner->id != \Yii::$app->user->id) {
                    $model->commissioner_id = $commissioner->id;
                    $model->save(false);
                    return $this->rJson();
                }
                return $this->rJson('', false, '错误操作');
            }
            return $this->rJson('', false, '专员不存在,请核对手机号');
        }
        return $this->rJson('', false, '错误操作');
    }

    /**
     * 场站抢单
     * @return \yii\web\Response
     */
    public function actionGet()
    {
        EnField::getNeedField();
        return $this->redirect(['list']);
    }
}