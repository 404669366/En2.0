<?php

namespace vendor\project\base;

use Yii;
use yii\web\IdentityInterface;

/**
 * This is the model class for table "en_user".
 *
 * @property string $id
 * @property string $token token
 * @property string $open_id 微信open_id
 * @property string $tel 手机号
 * @property string $money 余额
 * @property string $points 积分
 * @property string $address 地址
 * @property string $created_at 创建时间
 */
class EnUser extends \yii\db\ActiveRecord implements IdentityInterface
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'en_user';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['tel', 'token'], 'required'],
            [['tel'], 'unique'],
            [
                'tel',
                'match',
                'pattern' => '/^13[0-9]{9}$|14[0-9]{9}$|15[0-9]{9}$|17[0-9]{9}$|18[0-9]{9}$/',
                'message' => '手机号格式不正确'
            ],
            [['created_at'], 'integer'],
            [['open_id', 'token'], 'string', 'max' => 80],
            [['tel'], 'string', 'max' => 11],
            [['money', 'points', 'address'], 'string', 'max' => 50],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'token' => 'token',
            'open_id' => '微信open_id',
            'tel' => '手机号',
            'money' => '余额',
            'points' => '积分',
            'address' => '地址',
            'created_at' => '创建时间',
        ];
    }

    /**
     * 返回分页数据
     * @return mixed
     */
    public static function getPageData()
    {
        return self::find()->page(['tel' => ['like', 'tel']]);
    }

    //todo**********************  登录接口实现  ***************************

    public static function findIdentity($id)
    {
        return self::findOne($id);
    }

    public static function findIdentityByAccessToken($token, $type = null)
    {
        return self::findOne(['token' => $token]);
    }

    public function getId()
    {
        return $this->id;
    }

    public function getAuthKey()
    {
        return $this->token;
    }

    public function validateAuthKey($authKey)
    {
        return $this->token === $authKey;
    }
}
