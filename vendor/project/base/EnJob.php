<?php

namespace vendor\project\base;

use Yii;

/**
 * This is the model class for table "en_job".
 *
 * @property string $id
 * @property string $job 职位名
 * @property string $remark 备注
 * @property string $powers 拥有权限
 */
class EnJob extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'en_job';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['job'], 'unique'],
            [['job'], 'required'],
            [['remark'], 'string', 'max' => 255],
            [['powers'], 'string', 'max' => 1000],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'job' => '职位名',
            'remark' => '备注',
            'powers' => '拥有权限',
        ];
    }

    /**
     * 查询一条
     * @param int $id
     * @return array|null|\yii\db\ActiveRecord
     */
    public static function getOne($id = 0)
    {
        return self::find()->where(['id' => $id])->asArray()->one();
    }

    public function afterSave($insert, $changedAttributes)
    {
        if ($insert) {
            $data = \Yii::$app->cache->get('JobRelationData');
            $data = $data ? json_decode($data, true) : [];
            array_push($data, ['id' => $this->id, 'name' => $this->job, 'pid' => 0]);
            Yii::$app->cache->set('JobRelationData', json_encode($data));
            $map = \Yii::$app->cache->get('JobRelationMap');
            $map = $map ? json_decode($map, true) : [];
            $map[$this->id] = ['pid' => 0, 'children' => false];
            Yii::$app->cache->set('JobRelationMap', json_encode($map));
        } else {
            if (isset($changedAttributes['job'])) {
                $data = \Yii::$app->cache->get('JobRelationData');
                $data = str_replace('"name":"' . $changedAttributes['job'] . '"', '"name":"' . $this->job . '"', $data);
                Yii::$app->cache->set('JobRelationData', $data);
            }
        }
    }

    /**
     * 删除职位
     * @param $id
     * @return bool
     */
    public static function delJob($id)
    {
        $map = \Yii::$app->cache->get('JobRelationMap');
        $map = json_decode($map, true);
        if (!$map[$id]['children']) {
            $model = self::findOne($id);
            $data = \Yii::$app->cache->get('JobRelationData');
            $data = str_replace('{"id":' . $id . ',"name":"' . $model->job . '","pid":' . $map[$id]['pid'] . '}', '', $data);
            $data = str_replace('[,', '[', $data);
            $data = str_replace(',]', ']', $data);
            Yii::$app->cache->set('JobRelationData', $data);
            unset($map[$id]);
            Yii::$app->cache->set('JobRelationMap', json_encode($map));
            $model->delete();
            return true;
        }
        return false;
    }

    /**
     * 保存职位关系
     * @param string $data
     * @param string $map
     * @return bool
     */
    public static function saveJobRelation($data = '', $map = '')
    {
        if ($data && $map) {
            Yii::$app->cache->set('JobRelationData', $data);
            Yii::$app->cache->set('JobRelationMap', $map);
            return true;
        }
        return false;
    }

    /**
     * 获取职位关系
     * @return mixed|string
     */
    public static function getJobRelation()
    {
        if ($relation = \Yii::$app->cache->get('JobRelationData')) {
            return self::analysisRelation(json_decode($relation, true));
        }
        return '';
    }

    /**
     * 拼装job关系html
     * @param $relation
     * @return string
     */
    private static function analysisRelation($relation)
    {
        $str = '<ol class="dd-list">';
        foreach ($relation as $v) {
            $str .= '<li class="dd-item" data-id="' . $v['id'] . '">';
            $str .= '<div class="dd-handle">' . $v['name'] . '</div>';
            if (isset($v['children']) && $v['children']) {
                $str .= self::analysisRelation($v['children']);
            }
            $str .= '</li>';
        }
        return $str . '</ol>';
    }

    /**
     * 返回所有职位
     * @return array|\yii\db\ActiveRecord[]
     */
    public static function getJobs()
    {
        return self::find()->select(['id', 'job'])->asArray()->all();
    }

    /**
     * 返回所有职位树
     * @return mixed|string
     */
    public static function getJobTree()
    {
        if ($relation = \Yii::$app->cache->get('JobRelationData')) {
            $relation = str_replace('children', 'nodes', $relation);
            $relation = str_replace('name', 'text', $relation);
            return $relation;
        }
        return '';
    }
}
