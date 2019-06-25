<?php

namespace vendor\project\base;

use vendor\project\helpers\Constant;
use Yii;

/**
 * This is the model class for table "en_power".
 *
 * @property string $id
 * @property string $last_id 上级id
 * @property int $type 权限类型 1菜单2按钮3接口
 * @property string $name 权限名
 * @property string $url 权限路由
 * @property string $sort 排序
 */
class EnPower extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'en_power';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['last_id', 'type', 'name'], 'required'],
            [['name', 'url'], 'unique'],
            [['url'], 'validateUrl'],
            [['last_id', 'type', 'sort'], 'integer'],
            [['name'], 'string', 'max' => 30],
            [['url'], 'string', 'max' => 100],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'last_id' => '上级id',
            'type' => '权限类型 1菜单2按钮3接口',
            'name' => '权限名',
            'url' => '权限路由',
            'sort' => '排序',
        ];
    }

    /**
     * 验证路由
     * @return bool
     */
    public function validateUrl()
    {
        if ($this->last_id != 0) {
            if (!$this->url) {
                $this->addError('url', '路由不能为空');
                return false;
            }
        }
        return true;
    }

    /**
     * 分页数据
     * @return mixed
     */
    public static function getPageData()
    {
        $data = self::find()->alias('p1')
            ->leftJoin(self::tableName() . ' p2', 'p1.last_id=p2.id')
            ->select(['p1.*', 'p2.name as lastName'])
            ->page([
                'type' => ['like', 'p1.type'],
                'name' => ['like', 'p1.name'],
                'last' => ['like', 'p2.name'],
            ]);
        foreach ($data['data'] as &$v) {
            $v['type'] = Constant::powerType()[$v['type']];
        }
        return $data;
    }

    /**
     * 返回顶级权限
     * @param bool $notSelf
     * @return array|\yii\db\ActiveRecord[]
     */
    public static function getTopPowers($notSelf = false)
    {
        $data = self::find()->where(['last_id' => 0]);
        if ($notSelf) {
            $data->andWhere(['<>', 'id', $notSelf]);
        }
        return $data->select(['name', 'id'])->asArray()->all();
    }

    /**
     * 返回权限树类型数据
     * @return array|\yii\db\ActiveRecord[]
     */
    public static function getTreeData()
    {
        return self::find()->select(['id', 'name as title', 'last_id as pid'])->orderBy('type asc')->asArray()->all();
    }

    /**
     * 权限验证
     * @return array|bool|null|\yii\db\ActiveRecord
     */
    public static function pass()
    {
        $job_id = Yii::$app->user->identity->job_id;
        if ($job_id != 0) {
            if ($rule = self::findOne(['url' => '/' . Yii::$app->controller->getRoute()])) {
                return EnJob::find()->where(['id' => $job_id])
                    ->andWhere('find_in_set(' . $rule->id . ',powers)')
                    ->one();
            }
        }
        return true;
    }

    /**
     * 返回当前用户权限
     * @return array|\yii\db\ActiveRecord[]
     */
    public static function getUserPowers()
    {
        $rule = [];
        $job_id = Yii::$app->user->identity->job_id;
        if ($job_id == 0) {
            $rule = self::find()->select(['id'])->asArray()->all();
            $rule = array_column($rule, 'id');
        } else {
            if ($job = EnJob::findOne($job_id)) {
                $rule = explode(',', $job->powers);
            }
        }
        return $rule;
    }

    /**
     * 获取用户菜单
     * @return mixed|string
     */
    public static function getUserMenus()
    {
        $str = '';
        if ($rule = self::getUserPowers()) {
            $firstMenu = self::find()->where(['id' => $rule, 'last_id' => 0, 'type' => 1])->select(['id', 'name'])->orderBy('sort asc')->asArray()->all();
            foreach ($firstMenu as $v1) {
                $str .= '<li><a href="#"><span class="nav-label">' . $v1['name'] . '</span><span class="fa arrow"></span></a><ul class="nav nav-second-level collapse">';
                $nextMenu = self::find()->where(['id' => $rule, 'last_id' => $v1['id'], 'type' => 1])->select(['id', 'name', 'url'])->orderBy('sort asc')->asArray()->all();
                foreach ($nextMenu as $v2) {
                    $str .= '<li><a class="J_menuItem" href="' . $v2['url'] . '" data-index="' . $v2['id'] . '">' . $v2['name'] . '</a></li>';
                }
                $str .= '</ul></li>';
            }
        }
        return $str;
    }
}
