<?php

namespace vendor\project\base;

use Yii;
use yii\web\IdentityInterface;

/**
 * This is the model class for table "en_member".
 *
 * @property string $id
 * @property string $company_id 公司id
 * @property string $job_id 职位id
 * @property string $tel 手机号
 * @property string $password 密码
 */
class EnMember extends \yii\db\ActiveRecord implements IdentityInterface
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'en_member';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['tel'], 'unique'],
            [['tel', 'job_id', 'company_id', 'password'], 'required'],
            [
                'tel',
                'match',
                'pattern' => '/^13[0-9]{9}$|14[0-9]{9}$|15[0-9]{9}$|17[0-9]{9}$|18[0-9]{9}$/',
                'message' => '手机号格式不正确'
            ],
            [['password'], 'validatePassword'],
            [['job_id', 'company_id'], 'integer'],
            [['tel'], 'string', 'max' => 11],
            [['password'], 'string', 'max' => 80],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'company_id' => '公司id',
            'job_id' => '职位id',
            'tel' => '手机号',
            'password' => '密码',
        ];
    }

    public function validatePassword()
    {
        if ($this->isNewRecord) {
            if (!$this->password) {
                $this->addError('password', '请设置密码');
            }
        }
    }

    /**
     * 关联company表
     * @return \yii\db\ActiveQuery
     */
    public function getCompany()
    {
        return $this->hasOne(EnCompany::class, ['id' => 'company_id']);
    }

    /**
     * 关联job表
     * @return \yii\db\ActiveQuery
     */
    public function getJob()
    {
        return $this->hasOne(EnJob::class, ['id' => 'job_id']);
    }

    /**
     * 用户管理分页数据
     * @return mixed
     */
    public static function getPageData()
    {
        $data = self::find()->alias('m')
            ->leftJoin(EnJob::tableName() . ' j', 'm.job_id=j.id')
            ->leftJoin(EnCompany::tableName() . ' c', 'j.company_id=c.id')
            ->where(['<>', 'm.job_id', 0])
            ->select(['m.*', 'j.name as job', 'c.name as company'])
            ->page([
                'tel' => ['like', 'm.tel'],
                'company' => ['=', 'j.company_id'],
            ]);
        foreach ($data['data'] as &$v) {
            if (!$v['company']) {
                $v['company'] = '本部公司';
            }
        }
        return $data;
    }

    /**
     * 用户管理分页数据
     * @return mixed
     */
    public static function getMyPageData()
    {
        return self::find()->alias('m')
            ->leftJoin(EnJob::tableName() . ' j', 'm.job_id=j.id')
            ->where(['<>', 'm.job_id', 0])
            ->andWhere(['m.company_id' => \Yii::$app->user->identity->company_id])
            ->select(['m.*', 'j.name as job'])
            ->page([
                'tel' => ['like', 'm.tel'],
            ]);
    }

    /**
     * 手机号登录
     * @param string $tel
     * @param string $pwd
     * @return bool
     */
    public static function login($tel = '', $pwd = '')
    {
        if ($member = self::find()->Where(['tel' => $tel])->one()) {
            if (Yii::$app->security->validatePassword($pwd, $member->password)) {
                Yii::$app->user->login($member, 3 * 60 * 60);
                return true;
            }
        }
        return false;
    }

    //todo**********************  登录接口实现  ***************************

    public static function findIdentity($id)
    {
        return self::findOne($id);
    }

    public static function findIdentityByAccessToken($token, $type = null)
    {
        return self::findOne(['password' => $token]);
    }

    public function getId()
    {
        return $this->id;
    }

    public function getAuthKey()
    {
        return $this->password;
    }

    public function validateAuthKey($authKey)
    {
        return $this->password === $authKey;
    }
}
