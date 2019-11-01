<?php

namespace vendor\project\base;

use vendor\project\helpers\Constant;
use vendor\project\helpers\Helper;
use vendor\project\helpers\Wechat;
use Yii;

/**
 * This is the model class for table "en_intention".
 *
 * @property string $no 意向编号
 * @property string $field 场站编号
 * @property string $user_id 用户ID
 * @property string $num 股权数量
 * @property string $amount 金额
 * @property string $remark 备注
 * @property int $status 意向状态 0等待支付1支付成功2申请退款3退款通过
 * @property string $created_at
 */
class EnIntention extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'en_intention';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['no'], 'required'],
            [['no'], 'unique'],
            [['user_id', 'num', 'status', 'created_at'], 'integer'],
            [['status'], 'validateStatus'],
            [['no', 'field'], 'string', 'max' => 32],
            [['amount'], 'number'],
            [['remark'], 'string', 'max' => 255],
        ];
    }

    public function validateStatus()
    {
        if ($this->status == 1) {
            if ($this->local->canInvestByNum($this->num)) {
                $stock = EnStock::findOne(['type' => 4, 'field' => $this->field, 'key' => $this->user_id]);
                if ($stock) {
                    $stock->num += $this->num;
                } else {
                    $stock = new EnStock();
                    $stock->no = Helper::createNo('S');
                    $stock->field = $this->field;
                    $stock->type = 4;
                    $stock->key = $this->user_id;
                    $stock->created_at = time();
                    $stock->num = $this->num;
                }
                $stock->save(false);
            } else {
                $this->addError('status', '该场站已完成融资');
            }
        }
        if ($this->status == 3) {
            $stock = EnStock::findOne(['type' => 4, 'field' => $this->field, 'key' => $this->user_id]);
            $stock->num -= $this->num;
            if ($stock->num <= 0) {
                $stock->delete();
            } else {
                if (!$stock->save()) {
                    $this->addError('status', '股权同步失败');
                }
            }
        }
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'no' => '意向编号',
            'field' => '场站编号',
            'user_id' => '用户ID',
            'num' => '股权数量',
            'amount' => '金额',
            'remark' => '备注',
            'status' => '意向',
            'created_at' => 'Created At',
        ];
    }

    public function getLocal()
    {
        return $this->hasOne(EnField::class, ['no' => 'field']);
    }

    public function getUser()
    {
        return $this->hasOne(EnUser::class, ['id' => 'user_id']);
    }

    /**
     * 分页数据
     * @param bool $need
     * @return $this|mixed
     */
    public static function getPageData($need = true)
    {
        $data = self::find()->alias('i')
            ->leftJoin(EnField::tableName() . ' f', 'f.no=i.field')
            ->leftJoin(EnUser::tableName() . ' u', 'u.id=i.user_id')
            ->select(['i.*', 'u.tel', 'f.name', 'f.status as fStatus']);
        if ($company_id = Yii::$app->user->identity->company_id) {
            $data->andWhere(['f.company_id' => $company_id]);
        }
        if ($need && Yii::$app->user->identity->job_id) {
            $data->andWhere(['f.commissioner_id' => Yii::$app->user->id]);
        }
        $data = $data->page([
            'keywords' => ['like', 'i.no', 'i.field', 'f.name', 'u.tel'],
            'status' => ['=', 'i.status'],
            'fStatus' => ['=', 'f.status'],
        ]);
        foreach ($data['data'] as &$v) {
            $v['fieldInfo'] = '场站编号:' . $v['field'] . '<br>场站名称:' . $v['name'] . '<br>场站状态:' . Constant::fieldStatus()[$v['fStatus']];
            $v['stock'] = EnStock::getStockByFieldToStr($v['field']);
            $v['created_at'] = date('Y-m-d H:i:s', $v['created_at']);
            $v['statusName'] = Constant::intentionStatus()[$v['status']];
        }
        return $data;
    }

    /**
     * 获取购买页数据
     * @param string $no
     * @return array
     */
    public static function getBuyInfoByField($no = '')
    {
        $data = [];
        if ($field = EnField::findOne(['status' => 3, 'no' => $no])) {
            $data['no'] = $no;
            $data['name'] = $field->name;
            $data['title'] = $field->title;
            $data['address'] = $field->address;
            $data['univalence'] = $field->univalence;
            $now = EnStock::find()->where(['field' => $no])->sum('num');
            $data['max'] = 100 - $now;
            $data['all'] = $field->univalence * 100;
            $data['now'] = $field->univalence * $now;
            $data['images'] = explode(',', Yii::$app->cache->get('EnField-images-' . $field['no']));
            $data['status'] = Constant::fieldStatus()[3];
            $data['abridge'] = $field->company ? $field->company->abridge : '平台场站';
            $data['stock'] = $field->getStock();
        }
        return $data;
    }

    /**
     * 返回pc支付参数
     * @return mixed|string
     */
    public function getPayDataByPc()
    {
        if ($url = \Yii::$app->cache->get('EnIntention-url-' . $this->no)) {
            return $url;
        }
        if ($data = Wechat::nativePay('亿能建站-股权买入', $this->no, $this->amount, '/wx/pay/back.html')) {
            Yii::$app->cache->set('EnIntention-url-' . $this->no, $data['code_url'], 60 * 60 * 2);
            return $data['code_url'];
        }
        return '';
    }

    /**
     * 返回mp支付参数
     * @return array|bool|mixed
     */
    public function getPayDataByMp()
    {
        return Wechat::h5Pay('亿能建站-股权买入', $this->no, $this->amount, '/wx/pay/back.html', '/user/intention/list.html');
    }

    /**
     * 我的意向数据
     * @return array|\yii\db\ActiveRecord[]
     */
    public static function listDataByCenter()
    {
        $data = self::find()->alias('i')
            ->leftJoin(EnField::tableName() . ' f', 'f.no=i.field')
            ->leftJoin(EnCompany::tableName() . ' c', 'c.id=f.company_id')
            ->where(['i.user_id' => Yii::$app->user->id])
            ->select(['i.*', 'f.univalence', 'f.status as fStatus', 'c.abridge'])
            ->orderBy('i.created_at desc')
            ->asArray()->all();
        foreach ($data as &$v) {
            $v['statusName'] = Constant::intentionStatus()[$v['status']];
            $v['fStatusName'] = Constant::fieldStatus()[$v['fStatus']];
            $v['created'] = date('Y-m-d H:i:s', $v['created_at']);
            $v['abridge'] = $v['abridge'] ?: '平台场站';
        }
        return Helper::arraySort($data, 'created_at', SORT_DESC, 'status', SORT_ASC);
    }
}
