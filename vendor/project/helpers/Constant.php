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
        return 3 + ceil(EnField::find()->sum('univalence') * 100 / 100000000);
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
     * 场站状态
     * @return array
     */
    public static function fieldStatus()
    {
        return [
            0 => '等待处理',
            1 => '正在处理',
            2 => '平台审核',
            3 => '正在融资',
            4 => '融资完成',
        ];
    }

    /**
     * 场站上线状态
     * @return array
     */
    public static function fieldOnline()
    {
        return [
            0 => '未上线',
            1 => '已上线'
        ];
    }

    /**
     * 股权类型
     * @return array
     */
    public static function stockType()
    {
        return [
            1 => '平台',
            2 => '企业',
            3 => '场地',
            4 => '投资'
        ];
    }

    /**
     * 收益类型
     * @return array
     */
    public static function incomeType()
    {
        return [
            1 => '平台收益',
            2 => '企业收益',
            3 => '场地收益',
            4 => '投资收益'
        ];
    }

    /**
     * 提现类型
     * @return array
     */
    public static function cashType()
    {
        return [
            1 => '企业提现',
            2 => '用户提现',
            3 => '余额提现',
        ];
    }

    /**
     * 提现状态
     * @return array
     */
    public static function cashStatus()
    {
        return [
            0 => '等待审核',
            1 => '审核通过',
            2 => '确认打款',
            3 => '驳回审核',
        ];
    }

    /**
     * 场站意向状态
     * @return array
     */
    public static function intentionStatus()
    {
        return [
            0 => '等待支付',
            1 => '支付成功',
            2 => '申请退款',
            3 => '退款通过',
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
     * 银行类型
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

    /**
     * 服务器信息码
     * @return array
     */
    public static function serverCode()
    {
        return [
            //基础信息码
            '100' => '错误操作',
            '101' => '电桩离线,请稍后再试',
            //充电信息码
            '200' => '充电启动失败,请稍后再试',
            '201' => '余额不足,请充值',
            '202' => '充电枪口未连接',
            '203' => '充电枪口故障,请稍后再试',
            '204' => '充电启动中,请稍候',
            '205' => '正在充电',
            '206' => '充电结束中',
            '207' => '余额不足,充电结束中',
            '208' => '充电已结束',
            '209' => '未查询到该充电信息',
            //结束充电
            '300' => '命令发送成功',
            '301' => '命令发送失败,请稍后再试',
            //设置编号
            '400' => '设置编号成功',
            '401' => '设置编号失败,请稍后再试',
            //查询电桩列表
            '500' => '查询电桩列表成功',
            //查询电桩信息
            '600' => '查询电桩信息成功',
            '601' => '查询电桩信息失败,请稍后再试',

        ];
    }

    /**
     * 枪口连接状态
     * @return array
     */
    public static function linkStatus()
    {
        return [
            0 => '枪口断开',
            1 => '枪口半连',
            2 => '枪口连接',
        ];
    }

    /**
     * 枪口工作状态
     * @return array
     */
    public static function workStatus()
    {
        return [
            0 => '枪口空闲',
            1 => '准备充电',
            2 => '正在充电',
            3 => '充电结束',
            4 => '启动失败',
            5 => '枪口预约',
            6 => '系统故障',
        ];
    }

    /**
     * 在线状态
     * @return array
     */
    public static function pileOnline()
    {
        return [
            0 => '离线',
            1 => '在线',
        ];
    }

    /**
     * 电桩类型
     * @return array
     */
    public static function pileType()
    {
        return [
            1 => '快充',
            2 => '慢充',
            3 => '均充',
            4 => '轮充',
        ];
    }

    /**
     * 电桩标准
     * @return array
     */
    public static function pileStandard()
    {
        return [
            1 => '国标2011',
            2 => '国标2015',
        ];
    }

    /**
     * 充电订单状态
     * @return array
     */
    public static function orderStatus()
    {
        return [
            0 => '启动中',
            1 => '充电中',
            2 => '充电结束',
            3 => '完成支付',
            4 => '启动失败',
        ];
    }

    /**
     * 充值来源
     * @return array
     */
    public static function investSource()
    {
        return [
            1 => '微信支付',
            2 => '支付宝支付',
            3 => '银联支付',
        ];
    }

    /**
     * 充值状态
     * @return array
     */
    public static function investStatus()
    {
        return [
            0 => '等待支付',
            1 => '支付成功',
            2 => '支付失败',
        ];
    }

    /**
     * 返回客服电话
     * @return string
     */
    public static function serviceTel()
    {
        return '15680898996';//代
    }

    /**
     * mp首页轮播图
     * @return array
     */
    public static function bannerImg()
    {
        return [
            '/img/index1.jpg',
        ];
    }
}