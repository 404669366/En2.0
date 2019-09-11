<?php

namespace vendor\project\base;

use Yii;

/**
 * This is the model class for table "en_job".
 *
 * @property string $id
 * @property string $company_id 公司id
 * @property string $name 职位名
 * @property string $powers 拥有权限
 * @property string $remark 备注
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
            [['name', 'company_id'], 'required'],
            [['name'], 'validateName'],
            [['company_id'], 'integer'],
            [['name'], 'string', 'max' => 30],
            [['powers'], 'string', 'max' => 1000],
            [['remark'], 'string', 'max' => 255],
        ];
    }

    public function validateName()
    {
        if ($this->id) {
            $one = self::find()->where(['company_id' => $this->company_id, 'name' => $this->name])->andWhere(['<>', 'id', $this->id])->one();
        } else {
            $one = self::find()->where(['company_id' => $this->company_id, 'name' => $this->name])->one();
        }
        if ($one) {
            $this->addError('name', '公司已存在该职位');
        }
    }


    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'company_id' => '公司',
            'name' => '职位',
            'powers' => '权限',
            'remark' => '备注',
        ];
    }

    /**
     * 管理公司表
     * @return \yii\db\ActiveQuery
     */
    public function getCompany()
    {
        return $this->hasOne(EnCompany::class, ['id' => 'company_id']);
    }

    /**
     * 分页数据
     * @return mixed
     */
    public static function getPageData()
    {
        $data = self::find()->alias('j')
            ->leftJoin(EnCompany::tableName() . ' c', 'j.company_id=c.id')
            ->select(['j.*', 'c.name as company'])
            ->page([
                'name' => ['like', 'j.name'],
                'company' => ['=', 'j.company_id'],
            ]);
        foreach ($data['data'] as &$v) {
            $v['powers'] = EnPower::getPowerName($v['powers']);
        }
        return $data;
    }

    /**
     * 分页数据
     * @return mixed
     */
    public static function getMyPageData()
    {
        $data = self::find()->where(['company_id' => \Yii::$app->user->identity->company_id])
            ->page(['name' => ['like', 'name']]);
        foreach ($data['data'] as &$v) {
            $v['powers'] = EnPower::getPowerName($v['powers']);
        }
        return $data;
    }

    /**
     * 根据公司获取职位
     * @param int $company_id
     * @return array|\yii\db\ActiveRecord[]
     */
    public static function getJobByCompany($company_id)
    {
        return self::find()->where(['company_id' => $company_id])
            ->select(['id', 'name', 'remark'])
            ->asArray()->all();
    }
}
