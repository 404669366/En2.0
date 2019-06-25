<?php

namespace vendor\project\base;

use Yii;
use yii\web\IdentityInterface;

/**
 * This is the model class for table "en_member".
 *
 * @property string $id
 * @property string $username 用户名
 * @property string $tel 手机号
 * @property string $password 密码
 * @property string $job_id 职位id
 * @property string $created_at
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
            [['username', 'tel'], 'unique'],
            [['username', 'tel', 'job_id', 'password'], 'required'],
            [
                'tel',
                'match',
                'pattern' => '/^13[0-9]{9}$|14[0-9]{9}$|15[0-9]{9}$|17[0-9]{9}$|18[0-9]{9}$/',
                'message' => '手机号格式不正确'
            ],
            [['job_id', 'created_at'], 'integer'],
            [['username'], 'string', 'max' => 30],
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
            'username' => '用户名',
            'tel' => '手机号',
            'password' => '密码',
            'job_id' => '职位id',
            'created_at' => 'Created At',
        ];
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
     * 用户名或手机号登录
     * @param string $account
     * @param string $pwd
     * @return bool
     */
    public static function accountLogin($account = '', $pwd = '')
    {
        if ($member = self::find()->Where(['or', ['username' => $account], ['tel' => $account]])->one()) {
            if (Yii::$app->security->validatePassword($pwd, $member->password)) {
                Yii::$app->user->login($member, 3 * 60 * 60);
                return true;
            }
        }
        return false;
    }

    /**
     * 用户管理分页数据
     * @return mixed
     */
    public static function getPageData()
    {
        return self::find()->alias('m')
            ->leftJoin(EnJob::tableName() . ' j', 'm.job_id=j.id')
            ->where(['<>', 'm.job_id', 0])
            ->select(['m.*', 'j.job'])
            ->page([
                'username' => ['like', 'm.username'],
                'tel' => ['like', 'm.tel'],
                'job' => ['=', 'j.id'],
            ]);
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
