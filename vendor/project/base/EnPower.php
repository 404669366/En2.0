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
     * 根据用户返回权限信息
     * @param int $uid
     * @return array|\yii\db\ActiveRecord[]
     */
    public static function getPowersDataByUser($uid)
    {
        $data = self::find()->select(['id', 'name as title', 'last_id as pid']);
        $user = EnMember::findOne($uid);
        if ($user->company_id && $user->job_id) {
            $data->where(['id' => explode(',', $user->job->powers)]);
        }
        if ($user->company_id && !$user->job_id) {
            $data->where(['id' => explode(',', $user->company->powers)]);
        }
        return $data->orderBy('sort asc')->asArray()->all();
    }

    /**
     * 根据公司返回权限信息
     * @param int $company_id
     * @return array|\yii\db\ActiveRecord[]
     */
    public static function getPowersDataByCompany($company_id)
    {
        $company = EnCompany::findOne($company_id);
        return self::find()->select(['id', 'name as title', 'last_id as pid'])
            ->where(['id' => explode(',', $company ? $company->powers : '')])
            ->orderBy('sort asc')
            ->asArray()->all();

    }

    /**
     * 根据职位返回权限信息
     * @param int $job_id
     * @return array|\yii\db\ActiveRecord[]
     */
    public static function getPowersDataByJob($job_id)
    {
        $job = EnCompany::findOne($job_id);
        return self::find()->select(['id', 'name as title', 'last_id as pid'])
            ->where(['id' => explode(',', $job ? $job->powers : '')])
            ->orderBy('sort asc')
            ->asArray()->all();
    }

    /**
     * 权限验证
     * @return bool
     */
    public static function pass()
    {
        if ($rule = self::findOne(['url' => '/' . Yii::$app->controller->getRoute()])) {
            $have = array_column(self::getPowersDataByUser(Yii::$app->user->id), 'id');
            return in_array($rule->id, $have);
        }
        return true;
    }

    /**
     * 获取用户菜单
     * @return mixed|string
     */
    public static function getUserMenus()
    {
        $str = '';
        if ($rule = self::getPowersDataByUser(Yii::$app->user->id)) {
            $rule = array_column($rule, 'id');
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

    /**
     * 根据id查询权限名称
     * @param string $ids
     * @return array
     */
    public static function getPowerName($ids = '')
    {
        $names = self::find()->where(['id' => explode(',', $ids)])
            ->select(['name'])->orderBy('url asc')
            ->asArray()->all();
        return array_column($names, 'name');
    }
}
