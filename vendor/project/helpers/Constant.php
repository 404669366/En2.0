<?php
/**
 * Created by PhpStorm.
 * User: miku
 * Date: 2018/10/16
 * Time: 14:29
 */

namespace vendor\project\helpers;


use vendor\project\base\EnField;
use vendor\project\base\EnUser;

class Constant
{
    /**
     * 走过的日子
     * @return float
     */
    public static function goDay()
    {
        $begin = 1538323200;
        return ceil((time() - $begin) / 86400);
    }

    /**
     * 用户数量
     * @return int|string
     */
    public static function userCount()
    {
        return 100 + EnUser::find()->count();
    }

    /**
     * 场地数量
     * @return int|string
     */
    public static function fieldCount()
    {
        return 4 + EnField::find()->count();
    }

    /**
     * 成交金额
     * @return int|string
     */
    public static function amountCount()
    {
        $have = EnField::find()->select(['sum(present_amount) as all_amount'])->asArray()->one();
        return 3 + ceil($have['all_amount'] / 100000000);
    }

    /**
     * 定金收取比例
     * @return float
     */
    public static function orderRatio()
    {
        return 0.1;
    }

    /**
     * 权限类型
     * @return array
     */
    public static function powerType()
    {
        return [
            1 => '菜单',
            2 => '按钮',
            3 => '接口',
        ];
    }

    /**
     * 业务类型
     * @return array
     */
    public static function businessType()
    {
        return [
            1 => '新建场站',
            2 => '转手场站',
        ];
    }

    /**
     * 投资类型
     * @return array
     */
    public static function investType()
    {
        return [
            1 => '托管运营',
            2 => '保底运营',
            3 => '保底回购',
            4 => '第三方',
        ];
    }

    /**
     * 场站类型
     * @return array
     */
    public static function fieldStatus()
    {
        return [
            0 => '待处理',
            1 => '挂起',
            2 => '审核中',
            3 => '审核不通过',
            4 => '正在融资',
            5 => '融资完成',
        ];
    }

    /**
     * 场站来源
     * @return array
     */
    public static function fieldSource()
    {
        return [
            1 => '用户发布',
            2 => '平台专员',
            3 => '三方专员',
        ];
    }

    /**
     * 基础场站类型
     * @return array
     */
    public static function baseFieldStatus()
    {
        return [
            1 => '待转化',
            2 => '已转化'
        ];
    }

    /**
     * 场站意向状态
     * @return array
     */
    public static function intentionStatus()
    {
        return [
            1 => '待付定金',
            2 => '已付定金',
            3 => '审核中',
            4 => '审核通过',
            5 => '审核不通过',
            6 => '用户违约',
        ];
    }

    /**
     * 场站意向来源
     * @return array
     */
    public static function intentionSource()
    {
        return [
            1 => '用户',
            2 => '专员',
        ];
    }

    /**
     * 新闻来源
     * @return array
     */
    public static function newsSource()
    {
        return [
            0 => ['name' => '原创新闻', 'logo' => 'https://ascasc.oss-cn-hangzhou.aliyuncs.com/20190625091604sehen6sqg.png'],
        ];
    }

    /**
     * 友情链接
     * @return array
     */
    public static function friends()
    {
        return [
            0 => ['name' => '亿能充电<br/>合作伙伴', 'logo' => 'https://ascasc.oss-cn-hangzhou.aliyuncs.com/20190625091604sehen6sqg.png', 'url' => 'http://charge.en.ink'],
            1 => ['name' => '亿能建站<br/>合作伙伴', 'logo' => 'https://ascasc.oss-cn-hangzhou.aliyuncs.com/20190625091604sehen6sqg.png', 'url' => 'http://www.en.ink'],
        ];
    }

    /**
     * 返回银行类型
     * @return array
     */
    public static function bankType()
    {
        return [
            1 => '中国银行',
            2 => '中国农业银行',
            3 => '中国工商银行',
            4 => '中国交通银行',
            5 => '中国招商银行',
            6 => '中国建设银行',
            7 => '中国民生银行',
            8 => '中国兴业银行',
            9 => '中国光大银行',
            10 => '中国邮政储蓄银行',
        ];
    }
}