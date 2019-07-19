<?php

namespace vendor\project\base;

use Yii;

/**
 * This is the model class for table "en_company".
 *
 * @property string $id
 * @property string $name 公司名称
 * @property string $logo 公司logo
 * @property string $address 公司地址
 * @property string $powers 公司权限
 * @property string $intro 公司介绍
 */
class EnCompany extends \yii\db\ActiveRecord
{
    public $intro;

    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'en_company';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['name', 'address'], 'unique'],
            [['name', 'logo', 'address', 'intro', 'powers'], 'required'],
            [['name'], 'string', 'max' => 50],
            [['logo'], 'string', 'max' => 80],
            [['address'], 'string', 'max' => 255],
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
            'name' => '公司名称',
            'logo' => '公司logo',
            'powers' => '公司权限',
            'address' => '公司地址',
            'intro' => '公司介绍',
        ];
    }

    public function afterSave($insert, $changedAttributes)
    {
        Yii::$app->cache->set('CompanyIntro_' . $this->id, $this->intro);
        if (!$insert) {
            $powers = explode(',', $this->powers);
            $jobs = EnJob::find()->where(['company_id' => $this->id])->all();
            foreach ($jobs as $v) {
                $havePowers = explode(',', $v->powers);
                $newPowers = array_intersect($powers, $havePowers);
                if ($havePowers != $newPowers) {
                    $v->powers = implode(',', $newPowers);
                    $v->save(false);
                }
            }

        }
    }

    /**
     * 分页数据
     * @return mixed
     */
    public static function getPageData()
    {
        $data = self::find()
            ->page([
                'name' => ['like', 'name'],
            ]);
        foreach ($data['data'] as &$v) {
            $v['powers'] = EnPower::getPowerName($v['powers']);
        }
        return $data;
    }

    /**
     * 返回公司
     * @return array|\yii\db\ActiveRecord[]
     */
    public static function getCompany()
    {
        $data = self::find()->select(['id', 'name'])
            ->orderBy('id asc')
            ->asArray()->all();
        array_unshift($data, ['id' => 0, 'name' => '本部公司']);
        return $data;
    }
}
