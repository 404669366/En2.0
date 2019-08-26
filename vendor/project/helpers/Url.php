<?php
/**
 * Created by PhpStorm.
 * User: miku
 * Date: 2019/1/24
 * Time: 16:28
 */

namespace vendor\project\helpers;


class Url
{
    /**
     * 记录当前路由
     */
    public static function remember()
    {
        \Yii::$app->session->set('LastUrl', \Yii::$app->request->url);
    }

    /**
     * 获取记录的路由
     * @param string $defUrl
     * @return mixed
     */
    public static function getUrl($defUrl = '/user/user/center.html')
    {
        $url = \Yii::$app->session->get('LastUrl', $defUrl);
        \Yii::$app->session->remove('LastUrl');
        return $url;
    }
}