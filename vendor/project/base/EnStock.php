<?php

namespace vendor\project\base;

use vendor\project\helpers\Constant;
use Yii;

/**
 * This is the model class for table "en_stock".
 *
 * @property string $no 股权编号
 * @property string $field 场站编号
 * @property int $type 类型 1平台2企业3场地4投资
 * @property string $key 关联键(type=? 1无效2企业ID3场地用户ID4投资用户ID)
 * @property int $num 股权数量
 * @property string $created_at 创建时间
 */
class EnStock extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'en_stock';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['no'], 'unique'],
            [['no', 'field', 'type', 'num'], 'required'],
            [['type'], 'validateType'],
            [['num'], 'validateNum'],
            [['type', 'created_at', 'num', 'key'], 'integer'],
            [['no', 'field'], 'string', 'max' => 32],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'no' => '股权编号',
            'field' => '场站编号',
            'type' => '类型',
            'key' => '绑定用户',
            'num' => '股权数量',
            'created_at' => '创建时间',
        ];
    }

    public function validateType()
    {
        if ($this->type == 1 || $this->type == 2) {
            if (self::findOne(['field' => $this->field, 'type' => $this->type])) {
                $this->addError('type', '已存在该类股权');
                return false;
            }
            $this->key = EnField::findOne(['no' => $this->field])->company_id;
        }

        if ($this->type == 3 || $this->type == 4) {
            $user = EnUser::findOne(['tel' => $this->key]);
            if (!$user) {
                $user = new EnUser();
                $user->tel = $this->key;
                $user->token = \Yii::$app->security->generatePasswordHash($this->key);
                $user->created_at = time();
                if (!$user->save()) {
                    $this->addError('key', '创建用户账号失败');
                    return false;
                }
            }
            if (self::findOne(['field' => $this->field, 'type' => $this->type, 'key' => $user->id])) {
                $this->addError('key', '该用户已存在该类股权');
                return false;
            }
            $this->key = $user->id;
        }
        return true;
    }

    public function validateNum()
    {
        if (!$this->num) {
            $this->addError('num', '股权数量必须大于0');
        }
        $num = self::find()->where(['field' => $this->field])->sum('num');
        if (($num + $this->num) > 100) {
            $this->addError('num', '股权数量超过100份');
        }
    }

    public function afterSave($insert, $changedAttributes)
    {
        if ($model = EnField::findOne(['no' => $this->field, 'status' => 3])) {
            if (self::find()->where(['field' => $this->field])->sum('num') == 100) {
                $model->status = 4;
                $model->save();
            }
        }
    }

    /**
     * 查询股权信息
     * @param string $no
     * @return string
     */
    public static function getStockByFieldToStr($no = '')
    {
        $data = self::find()->alias('s')
            ->leftJoin(EnUser::tableName() . ' u3', 'u3.id=s.key')
            ->leftJoin(EnUser::tableName() . ' u4', 'u4.id=s.key')
            ->where(['s.field' => $no])
            ->select(['s.type', 's.num', 'u3.tel as local', 'u4.tel as invest'])
            ->orderBy(['s.type' => 'asc'])
            ->asArray()->all();
        $stock = '';
        foreach ($data as $v) {
            switch ($v['type']) {
                case 1:
                    $stock .= '平台:' . $v['num'] . '<br>';
                    break;
                case 2:
                    $stock .= '企业:' . $v['num'];
                    break;
                case 3:
                    $stock .= '<br>' . '场地(' . $v['local'] . '):' . $v['num'];
                    break;
                case 4:
                    $stock .= '<br>' . '投资(' . $v['invest'] . '):' . $v['num'];
                    break;
            }
        }
        return $stock;
    }

    /**
     * 查询股权信息
     * @param string $no
     * @return array|\yii\db\ActiveRecord[]
     */
    public static function getStockByFieldToArr($no = '')
    {
        $data = self::find()->alias('s')
            ->leftJoin(EnUser::tableName() . ' u3', 'u3.id=s.key')
            ->leftJoin(EnUser::tableName() . ' u4', 'u4.id=s.key')
            ->where(['s.field' => $no])
            ->select(['s.*', 'u3.tel as local', 'u4.tel as invest'])
            ->orderBy(['s.type' => 'asc'])
            ->asArray()->all();
        foreach ($data as &$v) {
            if ($v['type'] == 1 || $v['type'] == 2) {
                $v['key'] = '----';
            }
            if ($v['type'] == 3) {
                $v['key'] = $v['local'];
            }
            if ($v['type'] == 4) {
                $v['key'] = $v['invest'];
            }
            $v['type'] = Constant::stockType()[$v['type']];
        }
        return $data;
    }

    /**
     * 我的股权数据
     * @return array|\yii\db\ActiveRecord[]
     */
    public static function listDataByCenter()
    {
        $data = self::find()->alias('s')
            ->leftJoin(EnField::tableName() . ' f', 'f.no=s.field')
            ->leftJoin(EnCompany::tableName() . ' c', 'c.id=f.company_id')
            ->where(['s.type' => [3, 4], 's.key' => Yii::$app->user->id])
            ->select(['s.*', 'f.univalence', 'f.status as fStatus', 'c.abridge'])
            ->orderBy(['s.created_at' => 'desc'])
            ->asArray()->all();
        foreach ($data as &$v) {
            $v['typeName'] = Constant::stockType()[$v['type']];
            $v['fStatusName'] = Constant::fieldStatus()[$v['fStatus']];
            $v['created'] = date('Y-m-d H:i:s', $v['created_at']);
            $v['abridge'] = $v['abridge'] ?: '平台场站';
        }
        return $data;
    }
}
