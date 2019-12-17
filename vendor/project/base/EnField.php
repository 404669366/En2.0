<?php

namespace vendor\project\base;

use vendor\project\helpers\Constant;
use vendor\project\helpers\Helper;
use vendor\project\helpers\Msg;
use Yii;

/**
 * This is the model class for table "en_field".
 *
 * @property string $no 场站编号
 * @property string $company_id 企业ID
 * @property string $commissioner_id 专员ID
 * @property string $user_id 前台用户ID
 * @property string $name 场站名称
 * @property string $title 场站标题
 * @property string $trait 场站特色
 * @property string $config 场站配置
 * @property string $intro 场站介绍
 * @property string $images 场站图片
 * @property string $address 场站位置
 * @property string $lng 经度
 * @property string $lat 纬度
 * @property string $univalence 股权单价
 * @property string $reply 电力答复
 * @property string $record 备案文件
 * @property string $remark 备注
 * @property int $status 场站状态 0等待处理1正在处理2平台审核3正在融资4融资完成
 * @property int $online 上线状态 0未上线1已上线
 * @property string $created_at 创建时间
 */
class EnField extends \yii\db\ActiveRecord
{
    /**
     * @var string $trait
     */
    public $trait;

    /**
     * @var string $config
     */
    public $config;

    /**
     * @var string $images
     */
    public $images;

    /**
     * @var string $intro
     */
    public $intro;

    /**
     * @var string $remark
     */
    public $remark;

    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'en_field';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['no'], 'required'],
            [['company_id', 'commissioner_id', 'user_id', 'status', 'online', 'created_at'], 'integer'],
            [['univalence', 'lng', 'lat'], 'number'],
            [['online'], 'validateOnline'],
            [['status'], 'validateStatus'],
            [['no'], 'string', 'max' => 32],
            [['name', 'title'], 'string', 'max' => 30],
            [['trait'], 'string', 'max' => 255],
            [['config'], 'string', 'max' => 500],
            [['images'], 'string', 'max' => 400],
            [['address'], 'string', 'max' => 60],
            [['reply'], 'string', 'max' => 240],
            [['record'], 'string', 'max' => 80],
            [['intro', 'remark'], 'string'],
            [['no'], 'unique'],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'no' => '场站编号',
            'company_id' => '企业ID',
            'commissioner_id' => '专员ID',
            'user_id' => '前台用户ID',
            'name' => '场站名称',
            'title' => '场站标题',
            'trait' => '场站特色',
            'config' => '场站配置',
            'intro' => '场站介绍',
            'images' => '场站图片',
            'address' => '场站位置',
            'lng' => '经纬度',
            'lat' => '经纬度',
            'univalence' => '股权单价',
            'reply' => '电力答复',
            'record' => '备案文件',
            'remark' => '备注',
            'status' => '场站状态',
            'online' => '上线状态',
            'created_at' => '创建时间',
        ];
    }

    public function validateOnline()
    {
        if ($this->online == 0) {
            $data = [
                'key' => 'NZ7BZ-VWQHX-2XV4F-75J2W-UDF42-Q2BM2',
                'table_id' => '5d490255d31eea5b7b36b922',
                'filter' => 'ud_id in("' . $this->no . '")'
            ];
            $re = Helper::curlPost('https://apis.map.qq.com/place_cloud/data/delete', $data, true);
            $re = json_decode($re, true);
            if ($re['status']) {
                $this->addError('online', '数据同步失败');
            }
        }
        if ($this->online == 1) {
            $data = [
                'key' => 'NZ7BZ-VWQHX-2XV4F-75J2W-UDF42-Q2BM2',
                'table_id' => '5d490255d31eea5b7b36b922',
                'data' => [
                    [
                        'ud_id' => $this->no,
                        'title' => $this->name,
                        'address' => $this->address,
                        'location' => [
                            'lat' => (float)$this->lat,
                            'lng' => (float)$this->lng,
                        ]
                    ]
                ]
            ];
            $re = Helper::curlPost('https://apis.map.qq.com/place_cloud/data/create', $data, true);
            $re = json_decode($re, true);
            if ($re['status']) {
                $this->addError('online', '数据同步失败');
            }
        }
    }

    public function validateStatus()
    {
        if (in_array($this->status, [0, 1, 2])) {
            if (!$this->lng || !$this->lat) {
                $this->addError('lng', '请标注场地位置');
            }
            if (!$this->address) {
                $this->addError('address', '请填写场地地址');
            }
            if (!$this->images) {
                $this->addError('images', '请上传场地图片');
            }
        }
        if (in_array($this->status, [1, 2])) {
            if (!$this->name) {
                $this->addError('name', '请填写场站名称');
            }
            if (!$this->title) {
                $this->addError('title', '请填写场站标题');
            }
            if (!$this->trait) {
                $this->addError('trait', '请填写场站特色');
            }
            if (!$this->univalence) {
                $this->addError('univalence', '请填写股权单价');
            }
            if (!$this->config) {
                $this->addError('config', '请编写场站配置');
            }
            if (!$this->intro) {
                $this->addError('intro', '请编写场站介绍');
            }
        }
        if (in_array($this->status, [2])) {
            if (!$this->record) {
                $this->addError('config', '请上传备案文件');
            }
            if (!$this->reply) {
                $this->addError('intro', '请上传电力答复');
            }
        }
    }

    public function afterSave($insert, $changedAttributes)
    {
        if ($this->trait) {
            Yii::$app->cache->set('EnField-trait-' . $this->no, $this->trait);
        }
        if ($this->config) {
            Yii::$app->cache->set('EnField-config-' . $this->no, $this->config);
        }
        if ($this->images) {
            Yii::$app->cache->set('EnField-images-' . $this->no, $this->images);
        }
        if ($this->intro) {
            Yii::$app->cache->set('EnField-intro-' . $this->no, $this->intro);
        }
        if ($this->remark) {
            Yii::$app->cache->set('EnField-remark-' . $this->no, $this->remark);
        }
    }

    public function getTrait()
    {
        return Yii::$app->cache->get('EnField-trait-' . $this->no);
    }

    public function getConfig()
    {
        return Yii::$app->cache->get('EnField-config-' . $this->no);
    }

    public function getImages()
    {
        return Yii::$app->cache->get('EnField-images-' . $this->no);
    }

    public function getIntro()
    {
        return Yii::$app->cache->get('EnField-intro-' . $this->no);
    }

    public function getRemark()
    {
        return Yii::$app->cache->get('EnField-remark-' . $this->no);
    }

    public function getUser()
    {
        return self::hasOne(EnUser::class, ['id' => 'user_id']);
    }

    public function getCompany()
    {
        return self::hasOne(EnCompany::class, ['id' => 'company_id']);
    }

    public function getCommissioner()
    {
        return self::hasOne(EnMember::class, ['id' => 'commissioner_id']);
    }

    public function getStock()
    {
        return EnStock::find()->where(['field' => $this->no])->sum('num');
    }

    //todo*****************backend**********************

    /**
     * 分页数据
     * @param array $status
     * @param bool $need
     * @return $this|mixed
     */
    public static function getPageData($status = [], $need = true)
    {
        $data = self::find()->alias('f')
            ->leftJoin(EnCompany::tableName() . ' c', 'c.id=f.company_id')
            ->leftJoin(EnMember::tableName() . ' m', 'm.id=f.commissioner_id')
            ->leftJoin(EnUser::tableName() . ' u', 'u.id=f.user_id');
        if ($status) {
            $data->andWhere(['f.status' => $status]);
        }
        if ($company_id = Yii::$app->user->identity->company_id) {
            $data->andWhere(['f.company_id' => $company_id]);
        }
        if ($need && Yii::$app->user->identity->job_id) {
            $data->andWhere(['f.commissioner_id' => Yii::$app->user->id]);
        }
        $data = $data->select(['f.*', 'c.name as cName', 'm.tel as cTel', 'u.tel as uTel'])->page([
            'key' => ['like', 'f.no', 'f.name', 'f.title', 'f.address', 'c.name', 'm.tel', 'u.tel'],
            'status' => ['=', 'f.status'],
            'online' => ['=', 'f.online'],
        ]);
        foreach ($data['data'] as &$v) {
            $v['info'] = '场站编号:' . $v['no'] . '<br>创建时间:' . date('Y-m-d H:i:s', $v['created_at']);
            $v['data'] = '场站名称:' . $v['name'] . '<br>场站地址:' . $v['address'];
            $v['statusInfo'] = '场站状态:' . Constant::fieldStatus()[$v['status']] . '<br>上线状态:' . Constant::fieldOnline()[$v['online']];
            $v['stock'] = EnStock::getStockByFieldToStr($v['no']);
        }
        $data['data'] = Helper::arraySort($data['data'], 'created_at', SORT_DESC, 'status', SORT_ASC);
        return $data;
    }

    /**
     * 场站统计列表数据
     * @return $this|mixed
     */
    public static function getStatisticsData()
    {
        $data = self::find()->alias('f')
            ->leftJoin(EnCompany::tableName() . ' c', 'c.id=f.company_id')
            ->Where(['f.status' => 4]);
        if ($company_id = Yii::$app->user->identity->company_id) {
            $data->andWhere(['f.company_id' => $company_id]);
        }
        $data = $data->select(['f.*', 'c.name as cName'])->page([
            'key' => ['like', 'f.no', 'f.name', 'f.title', 'f.address', 'c.name'],
            'status' => ['=', 'f.status'],
            'online' => ['=', 'f.online'],
        ]);
        foreach ($data['data'] as &$v) {
            $pileCount = EnPile::find()->where(['field' => $v['no']])->count();
            $gunCount = EnPile::find()->where(['field' => $v['no']])->sum('count');
            $v['created_at'] = '场站编号:' . $v['no'] . '<br>创建时间:' . date('Y-m-d H:i:s', $v['created_at']);
            $v['data'] = '场站名称:' . $v['name'] . '<br>场站地址:' . $v['address'] . '<br>电桩数量:' . $pileCount . '<br>枪口数量:' . $gunCount;
            $v['statusInfo'] = '场站状态:' . Constant::fieldStatus()[$v['status']] . '<br>上线状态:' . Constant::fieldOnline()[$v['online']];
            $v['stock'] = EnStock::getStockByFieldToStr($v['no']);
        }
        return $data;
    }

    /**
     * 统计报表数据
     * @param string $no
     * @return array
     */
    public static function statisticsReportInfo($no = '')
    {
        $minYear = EnOrder::find()->alias('o')
            ->leftJoin(EnPile::tableName() . ' p', 'p.no=o.pile')
            ->where(['p.field' => $no])
            ->min("FROM_UNIXTIME(o.created_at,'%Y')") ?: date('Y');
        $data = [
            'all' => round(EnOrder::find()->alias('o')
                ->leftJoin(EnPile::tableName() . ' p', 'p.no=o.pile')
                ->where(['p.field' => $no, 'o.status' => [2, 3]])
                ->sum('o.bm+o.sm'), 2),
            'year' => round(EnOrder::find()->alias('o')
                ->leftJoin(EnPile::tableName() . ' p', 'p.no=o.pile')
                ->where(['p.field' => $no, "FROM_UNIXTIME(o.created_at,'%Y')" => date('Y'), 'o.status' => [2, 3]])
                ->sum('o.bm+o.sm'), 2),
            'month' => round(EnOrder::find()->alias('o')
                ->leftJoin(EnPile::tableName() . ' p', 'p.no=o.pile')
                ->where(['p.field' => $no, "FROM_UNIXTIME(o.created_at,'%Y-%m')" => date('Y-m'), 'o.status' => [2, 3]])
                ->sum('o.bm+o.sm'), 2),
            'day' => round(EnOrder::find()->alias('o')
                ->leftJoin(EnPile::tableName() . ' p', 'p.no=o.pile')
                ->where(['p.field' => $no, "FROM_UNIXTIME(o.created_at,'%Y-%m-%d')" => date('Y-m-d'), 'o.status' => [2, 3]])
                ->sum('o.bm+o.sm'), 2),
            'years' => array_reverse(range($minYear, date('Y'))),
        ];
        return $data;
    }

    /**
     * 统计报表各月数据
     * @param string $no
     * @param string $year
     * @return array
     */
    public static function statisticsReportData($no = '', $year = '')
    {
        $year = $year ?: date('Y');
        $data = ['-01', '-02', '-03', '-04', '-05', '-06', '-07', '-08', '-09', '-10', '-11', '-12'];
        foreach ($data as &$v) {
            $v = round(EnOrder::find()->alias('o')
                ->leftJoin(EnPile::tableName() . ' p', 'p.no=o.pile')
                ->where(['p.field' => $no, "FROM_UNIXTIME(o.created_at,'%Y-%m')" => $year . $v, 'o.status' => [2, 3]])
                ->sum('o.bm+o.sm'), 2);
        }
        return $data;
    }

    /**
     * 统计报表单月数据
     * @param string $no
     * @param string $month
     * @return array|\yii\db\ActiveRecord[]
     */
    public static function statisticsMonthData($no = '', $month = '')
    {
        $month = $month ?: date('Y-m');
        $data = EnOrder::find()->alias('o')
            ->leftJoin(EnPile::tableName() . ' p', 'p.no=o.pile')
            ->leftJoin(EnUser::tableName() . ' u', 'u.id=o.uid')
            ->where(['p.field' => $no, "FROM_UNIXTIME(o.created_at,'%Y-%m')" => $month, 'o.status' => [2, 3]])
            ->select(['o.*', 'u.tel'])
            ->orderBy('o.created_at desc')
            ->asArray()->all();
        foreach ($data as &$v) {
            $v['statusV'] = $v['status'];
            $v['status'] = Constant::orderStatus()[$v['status']];
            $v['created'] = $v['created_at'];
            $v['created_at'] = date('Y-m-d H:i:s', $v['created_at']);
            $v['info'] = '基础电费:' . $v['bm'] . '<br>服务电费:' . $v['sm'] . '<br>订单总额:' . round($v['bm'] + $v['sm'], 2);
        }
        return $data;
    }

    /**
     * 抢单数
     * @return int|string
     */
    public static function getRobCount()
    {
        return self::find()->where(['status' => 0, 'commissioner_id' => 0])->count();
    }

    /**
     * 场地抢单
     * @return bool|string
     */
    public static function robField()
    {
        if ($fields = self::find()->where(['status' => 0, 'commissioner_id' => 0])->select(['no'])->asArray()->all()) {
            $fields = array_column($fields, 'no');
            if ($field = self::findOne(['no' => $fields[array_rand($fields)], 'status' => 0, 'commissioner_id' => 0])) {
                $field->company_id = Yii::$app->user->identity->company_id;
                $field->commissioner_id = Yii::$app->user->id;
                $field->images = $field->getImages();
                if ($field->save()) {
                    Msg::set('抢单成功');
                    return $field->no;
                }
                Msg::set($field->errors());
                return false;
            }
        }
        Msg::set('手慢了啦!!!');
        return false;
    }

    /**
     * 地图数据
     * @return array|\yii\db\ActiveRecord[]
     */
    public static function getMapData()
    {
        $data = self::find()->where(['online' => 1]);
        if ($company_id = Yii::$app->user->identity->company_id) {
            $data = $data->andWhere(['company_id' => $company_id]);
        }
        $data = $data->select(['no', 'lat', 'lng', 'name', 'address'])->asArray()->all();
        foreach ($data as &$v) {
            $v['styleId'] = 'circle';
            $v['lat'] = (float)$v['lat'];
            $v['lng'] = (float)$v['lng'];
        }
        return $data;
    }

    /**
     * 地图数据
     * @param string $no
     * @return array
     */
    public static function getMapInfo($no = '')
    {
        $model = EnOrder::find()->alias('o')
            ->leftJoin(EnPile::tableName() . ' p', 'p.`no`=o.pile')
            ->where(['p.field' => $no]);
        $month = ['-01', '-02', '-03', '-04', '-05', '-06', '-07', '-08', '-09', '-10', '-11', '-12'];
        foreach ($month as &$v) {
            $model0 = clone $model;
            $v = $model0->andWhere(["FROM_UNIXTIME(o.created_at,'%Y-%m')" => date('Y') . $v])->count();
        }
        $model1 = clone $model;
        $model2 = clone $model;
        $model3 = clone $model;
        $model4 = clone $model;
        $data = [
            'allCharge' => round($model1->andWhere(['o.status' => [2, 3]])->sum('o.e'), 2),
            'allUse' => round($model2->andWhere(['o.status' => [2, 3]])->sum('o.bm + o.sm'), 2),
            'useCount' => $model3->andWhere(['o.status' => [2, 3]])->count(),
            'allCount' => $model4->andWhere(['o.status' => [2, 3, 4]])->count(),
            'chart' => implode(',', $month)
        ];
        return $data;
    }

    //todo*****************charge**********************

    /**
     * 返回电站信息
     * @param string $no
     * @return array|null|\yii\db\ActiveRecord
     */
    public static function getFieldInfo($no = '')
    {
        $data = self::find()->where(['no' => $no])
            ->select(['no', 'name', 'address', 'lng', 'lat'])
            ->asArray()->one();
        if ($data) {
            $data['images'] = explode(',', Yii::$app->cache->get('EnField-images-' . $no));
            $data['guns'] = EnPile::getGunsInfoByField($no);
        }
        return $data;
    }

    /**
     * 返回浏览记录信息
     * @param array $nos
     * @return array|\yii\db\ActiveRecord[]
     */
    public static function getStepInfo($nos = [])
    {
        $data = self::find()->where(['no' => $nos])
            ->select(['no', 'name', 'address', 'lng', 'lat',])
            ->asArray()->all();
        foreach ($data as &$v) {
            $v['guns'] = EnPile::getGunsInfoByField($v['no']);
        }
        return $data;
    }

    //todo*****************pc/mp**********************

    /**
     * 当前数量股权能否投资
     * @param int $num
     * @return bool
     */
    public function canInvestByNum($num = 0)
    {
        $max = 100 - EnStock::find()->where(['field' => $this->no])->sum('num');
        return $num <= $max;
    }

    /**
     * 首页数据
     * @param int $limit
     * @return array|\yii\db\ActiveRecord[]
     */
    public static function recommendDataByPC($limit = 0)
    {
        $data = self::find()->alias('f')
            ->leftJoin(EnCompany::tableName() . ' c', 'c.id=f.company_id')
            ->where(['f.status' => [1, 2, 3, 4]])->limit($limit)
            ->select(['f.*', 'c.abridge'])
            ->orderBy('RAND()')
            ->asArray()->all();
        foreach ($data as &$v) {
            $images = explode(',', Yii::$app->cache->get('EnField-images-' . $v['no']));
            $v['images'] = $images[array_rand($images)];
            $v['abridge'] = $v['abridge'] ?: '平台场站';
            $v['trait'] = Yii::$app->cache->get('EnField-trait-' . $v['no']) ?: '';
            if (in_array($v['status'], [1, 2, 4])) {
                $v['status'] = 4;
                $v['stock'] = 100;
            } else {
                $v['stock'] = EnStock::find()->where(['field' => $v['no']])->sum('num') ?: 0;
            }
            $v['statusName'] = Constant::fieldStatus()[$v['status']];
            $v['all'] = $v['univalence'] * 100;
        }
        return Helper::arraySort($data, 'status', SORT_ASC, 'stock', SORT_DESC, 'created_at', SORT_DESC);
    }

    /**
     * 列表页数据
     * @return array|\yii\db\ActiveQuery|\yii\db\ActiveRecord[]
     */
    public static function listDataByPc()
    {
        $page = Yii::$app->request->get('page', 1);
        $keywords = Yii::$app->request->get('keywords', '');
        $data = self::find()->alias('f')
            ->leftJoin(EnCompany::tableName() . ' c', 'c.id=f.company_id')
            ->where(['f.status' => [1, 2, 3, 4]]);
        if ($keywords) {
            $data->andWhere([
                'or',
                ['like', 'f.no', $keywords],
                ['like', 'f.name', $keywords],
                ['like', 'f.title', $keywords],
                ['like', 'f.address', $keywords],
                ['like', 'c.abridge', $keywords],
            ]);
        }
        $data = $data->select(['f.*', 'c.abridge'])
            ->offset(($page - 1) * 6)->limit(6)
            ->orderBy('f.status desc')
            ->asArray()->all();
        foreach ($data as &$v) {
            $images = explode(',', Yii::$app->cache->get('EnField-images-' . $v['no']));
            $v['images'] = $images[array_rand($images)];
            $v['abridge'] = $v['abridge'] ?: '平台场站';
            $v['trait'] = Yii::$app->cache->get('EnField-trait-' . $v['no']) ?: '';
            if (in_array($v['status'], [1, 2, 4])) {
                $v['status'] = 4;
                $v['stock'] = 100;
            } else {
                $v['stock'] = EnStock::find()->where(['field' => $v['no']])->sum('num') ?: 0;
            }
            $v['statusName'] = Constant::fieldStatus()[$v['status']];
            $v['all'] = $v['univalence'] * 100;
            $v['now'] = $v['univalence'] * $v['stock'];
        }
        return Helper::arraySort($data, 'status', SORT_ASC, 'stock', SORT_DESC, 'created_at', SORT_DESC);
    }

    /**
     * 场站推荐数据
     * @param int $type
     * @param int $limit
     * @return array|mixed|\yii\db\ActiveRecord[]
     */
    public static function recommendDataByMp($type = 0, $limit = 0)
    {
        $data = self::find()->alias('f')
            ->leftJoin(EnCompany::tableName() . ' c', 'c.id=f.company_id')
            ->where(['f.status' => 3])
            ->select(['f.*', 'c.abridge'])
            ->limit($limit)
            ->asArray()->all();
        foreach ($data as &$v) {
            $images = explode(',', Yii::$app->cache->get('EnField-images-' . $v['no']));
            $v['images'] = $images[array_rand($images)];
            $v['abridge'] = $v['abridge'] ?: '平台场站';
            $v['trait'] = Yii::$app->cache->get('EnField-trait-' . $v['no']) ?: '';
            $v['stock'] = EnStock::find()->where(['field' => $v['no']])->sum('num') ?: 0;
            $v['statusName'] = Constant::fieldStatus()[$v['status']];
            $v['all'] = $v['univalence'] * 100;
        }
        if ($type == 1) {
            $data = Helper::arraySort($data, 'created_at', SORT_DESC, 'stock', SORT_ASC);
        }
        if ($type == 2) {
            $data = Helper::arraySort($data, 'stock', SORT_DESC, 'created_at', SORT_DESC);
        }
        if ($type == 3) {
            $data = Helper::arraySort($data, 'all', SORT_DESC, 'created_at', SORT_DESC);
        }
        return $data;
    }

    /**
     * 列表页数据
     * @param int $type
     * @param string $keywords
     * @return $this|array|\yii\db\ActiveRecord[]
     */
    public static function listDataByMp($type = 0, $keywords = '')
    {
        $data = self::find()->alias('f')
            ->leftJoin(EnCompany::tableName() . ' c', 'c.id=f.company_id')
            ->where(['f.status' => [1, 2, 3, 4]]);
        if ($keywords) {
            $data->andWhere([
                'or',
                ['like', 'f.no', $keywords],
                ['like', 'f.name', $keywords],
                ['like', 'f.title', $keywords],
                ['like', 'f.address', $keywords],
                ['like', 'c.abridge', $keywords],
            ]);
        }
        $data = $data->select(['f.*', 'c.abridge'])->asArray()->orderBy('f.status desc')->all();
        foreach ($data as &$v) {
            $images = explode(',', Yii::$app->cache->get('EnField-images-' . $v['no']));
            $v['images'] = $images[array_rand($images)];
            $v['abridge'] = $v['abridge'] ?: '平台场站';
            $v['trait'] = Yii::$app->cache->get('EnField-trait-' . $v['no']) ?: '';
            if (in_array($v['status'], [1, 2, 4])) {
                $v['status'] = 4;
                $v['stock'] = 100;
            } else {
                $v['stock'] = EnStock::find()->where(['field' => $v['no']])->sum('num') ?: 0;
            }
            $v['statusName'] = Constant::fieldStatus()[$v['status']];
            $v['all'] = $v['univalence'] * 100;
        }
        if ($type == 1) {
            $data = Helper::arraySort($data, 'created_at', SORT_DESC, 'status', SORT_ASC, 'stock', SORT_DESC);
        }
        if ($type == 2) {
            $data = Helper::arraySort($data, 'stock', SORT_DESC, 'status', SORT_ASC, 'created_at', SORT_DESC);
        }
        if ($type == 3) {
            $data = Helper::arraySort($data, 'all', SORT_DESC, 'created_at', SORT_DESC, 'status', SORT_ASC);
        }
        if ($type == 4) {
            $data = Helper::arraySort($data, 'univalence', SORT_DESC, 'status', SORT_ASC, 'created_at', SORT_DESC);
        }
        if ($type == 5) {
            $data = Helper::arraySort($data, 'status', SORT_ASC, 'stock', SORT_DESC, 'created_at', SORT_DESC);
        }
        return $data;
    }

    /**
     * 场站详情数据
     * @param string $no
     * @return array|null|\yii\db\ActiveRecord
     */
    public static function detailData($no = '')
    {
        $detail = self::find()->alias('f')
            ->leftJoin(EnCompany::tableName() . ' c', 'c.id=f.company_id')
            ->where(['no' => $no, 'status' => [1, 2, 3, 4]])
            ->select(['f.*', 'c.abridge'])
            ->asArray()->one();
        if ($detail) {
            $detail['abridge'] = $detail['abridge'] ?: '平台场站';
            $detail['images'] = Helper::completionImg(\Yii::$app->cache->get('EnField-images-' . $detail['no']));
            $detail['trait'] = Helper::handleStr(\Yii::$app->cache->get('EnField-trait-' . $detail['no']));
            $detail['config'] = Helper::handleStr(\Yii::$app->cache->get('EnField-config-' . $detail['no']));
            $detail['intro'] = Helper::handleStr(\Yii::$app->cache->get('EnField-intro-' . $detail['no']));
            $detail['all'] = $detail['univalence'] * 100;
            if (in_array($detail['status'], [1, 2, 4])) {
                $detail['status'] = 4;
                $detail['stock'] = 100;
                $detail['now'] = $detail['all'];
            } else {
                $detail['stock'] = EnStock::find()->where(['field' => $detail['no']])->sum('num') ?: 0;
                $detail['now'] = $detail['stock'] * $detail['univalence'];
            }
            $detail['statusName'] = Constant::fieldStatus()[$detail['status']];
            $detail['commissioner'] = EnMember::findOne($detail['commissioner_id'])->tel;
        }
        return $detail;
    }

    /**
     * 我的场站数据
     * @return array|\yii\db\ActiveRecord[]
     */
    public static function listDataByCenter()
    {
        $data = self::find()->alias('f')
            ->leftJoin(EnCompany::tableName() . ' c', 'c.id=f.company_id')
            ->where(['f.user_id' => Yii::$app->user->id])
            ->select(['f.*', 'c.abridge'])
            ->asArray()->all();
        foreach ($data as &$v) {
            $images = explode(',', Yii::$app->cache->get('EnField-images-' . $v['no']));
            $v['images'] = $images[array_rand($images)];
            $v['abridge'] = $v['abridge'] ?: '平台场站';
            $v['statusName'] = Constant::fieldStatus()[$v['status']];
            $v['all'] = $v['univalence'] * 100;
        }
        return Helper::arraySort($data, 'created_at', SORT_DESC, 'status', SORT_ASC);
    }
}
