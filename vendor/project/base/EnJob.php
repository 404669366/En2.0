<?php

namespace vendor\project\base;

use Yii;

/**
 * This is the model class for table "en_job".
 *
 * @property string $id
 * @property string $company_id 公司id 0本部公司
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
            [['name', 'powers', 'company_id'], 'required'],
            [['name'], 'validateName'],
            [['company_id'], 'integer'],
            [['name'], 'string', 'max' => 30],
            [['powers'], 'string', 'max' => 1000],
            [['remark'], 'string', 'max' => 255],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'company_id' => '公司id 0本部公司',
            'name' => '职位名',
            'powers' => '拥有权限',
            'remark' => '备注',
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

    public function afterSave($insert, $changedAttributes)
    {
        if (!$insert && isset($changedAttributes['company_id'])) {
            EnMember::updateAll(['company_id' => $this->company_id], ['job_id' => $this->id]);
        }
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
            if (!$v['company']) {
                $v['company'] = '本部公司';
            }
        }
        return $data;
    }

    /**
     * 分页数据
     * @return mixed
     */
    public static function getMyPageData()
    {
        $data = self::find()->where(['company_id' => Yii::$app->user->identity->company_id])
            ->page(['name' => ['like', 'j.name']]);
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
    public static function getJob($company_id = 0)
    {
        return self::find()->where(['company_id' => $company_id])
            ->select(['id', 'name', 'remark'])
            ->asArray()->all();
    }
}
