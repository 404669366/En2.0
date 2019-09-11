<?php

namespace vendor\project\base;

use Yii;

/**
 * This is the model class for table "en_company".
 *
 * @property string $id
 * @property string $name 公司名称
 * @property string $abridge 公司简称
 * @property string $address 公司地址
 * @property string $account 对公账户
 * @property string $bank 开户银行
 * @property string $logo 公司logo
 * @property string $license 营业执照
 * @property string $legal 法人电话
 * @property string $legal_card 法人身份证正反面
 * @property string $admin 管理员电话
 * @property string $admin_card 管理员身份证正反面
 * @property string $powers 公司权限
 * @property string $created_at
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
            [['abridge', 'name', 'address', 'admin'], 'unique'],
            [['abridge', 'name', 'address', 'account', 'bank', 'logo', 'license', 'legal', 'legal_card', 'admin', 'admin_card', 'intro'], 'required'],
            [['legal', 'admin'], 'match', 'pattern' => '/^13[0-9]{9}$|14[0-9]{9}$|15[0-9]{9}$|17[0-9]{9}$|18[0-9]{9}$/', 'message' => '手机号格式不正确'],
            [['created_at'], 'integer'],
            [['name'], 'string', 'max' => 50],
            [['abridge'], 'string', 'max' => 10],
            [['address'], 'string', 'max' => 255],
            [['account'], 'string', 'max' => 40],
            [['bank'], 'string', 'max' => 100],
            [['logo', 'license'], 'string', 'max' => 80],
            [['legal', 'admin'], 'string', 'max' => 20],
            [['legal_card', 'admin_card'], 'string', 'max' => 160],
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
            'abridge' => '公司简称',
            'address' => '公司地址',
            'account' => '对公账户',
            'bank' => '开户银行',
            'logo' => '公司logo',
            'license' => '营业执照',
            'legal' => '法人电话',
            'legal_card' => '法人身份证正反面',
            'admin' => '管理员电话',
            'admin_card' => '管理员身份证正反面',
            'powers' => '公司权限',
            'created_at' => 'Created At',
            'intro' => '公司介绍',
        ];
    }

    public function afterSave($insert, $changedAttributes)
    {
        Yii::$app->cache->set('CompanyIntro_' . $this->id, $this->intro);
        if (isset($changedAttributes['admin'])) {
            if ($old = EnMember::findOne(['tel' => $changedAttributes['admin']])) {
                $old->delete();
            }
            $user = EnMember::findOne(['tel' => $this->admin]);
            if (!$user) {
                $user = new EnMember();
                $user->tel = $this->admin;
                $user->password = Yii::$app->security->generatePasswordHash(substr($this->admin, -6, 6));
            }
            $user->company_id = $this->id;
            $user->job_id = 0;
            $user->save();
        }
        if (!$insert && isset($changedAttributes['powers'])) {
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
     * 获取公司介绍
     * @return mixed
     */
    public function getIntro()
    {
        return \Yii::$app->cache->get('CompanyIntro_' . $this->id);
    }

    /**
     * 分页数据
     * @return mixed
     */
    public static function getPageData()
    {
        $data = self::find()
            ->page([
                'key' => ['like', 'abridge', 'name', 'legal', 'admin'],
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
        return self::find()->select(['id', 'name'])
            ->orderBy('id asc')
            ->asArray()->all();
    }
}
