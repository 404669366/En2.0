<?php

namespace vendor\project\base;

use vendor\project\helpers\Constant;
use Yii;

/**
 * This is the model class for table "en_news".
 *
 * @property string $id
 * @property string $title 新闻标题
 * @property string $intro 新闻简介
 * @property string $image 封面图片
 * @property string $source 新闻来源 0原创...
 * @property string $url 来源路由
 * @property string $created_at
 */
class EnNews extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'en_news';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['image', 'title', 'intro'], 'required'],
            [['source', 'created_at'], 'integer'],
            [['title'], 'string', 'max' => 50],
            [['intro'], 'string', 'max' => 100],
            [['image', 'url'], 'string', 'max' => 255],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'title' => '新闻标题',
            'intro' => '新闻简介',
            'image' => '封面图片',
            'source' => '新闻来源 0原创...',
            'url' => '来源路由',
            'created_at' => 'Created At',
        ];
    }

    /**
     * 列表分页数据
     * @return mixed
     */
    public static function getPageData()
    {
        $data = self::find()->page([
            'title' => ['like', 'title'],
            'source' => ['=', 'source'],
        ]);
        foreach ($data['data'] as &$v) {
            $v['source'] = Constant::newsSource()[$v['source']]['name'];
        }
        return $data;
    }

    /**
     * 前台列表页数据
     * @return array|\yii\db\ActiveRecord[]
     */
    public static function getData()
    {
        $pageNum = Yii::$app->request->get('pageNum', 1);
        $data = self::find()->offset(($pageNum - 1) * 6)->limit(6)->asArray()->all();
        foreach ($data as &$v) {
            $source = Constant::newsSource()[$v['source']];
            $v['source'] = $source['name'];
            $v['sourceLogo'] = $source['logo'];
        }
        return $data;
    }

    /**
     * 首页数据
     * @param int $length
     * @return array|\yii\db\ActiveRecord[]
     */
    public static function indexData($length = 6)
    {
        $data = self::find()->orderBy('created_at desc')->limit($length)->asArray()->all();
        foreach ($data as &$v) {
            $source = Constant::newsSource()[$v['source']];
            $v['source'] = $source['name'];
            $v['sourceLogo'] = $source['logo'];
        }
        return $data;
    }
}
