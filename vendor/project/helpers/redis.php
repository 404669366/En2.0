<?php
/**
 * Created by PhpStorm.
 * User: 40466
 * Date: 2018/6/29
 * Time: 15:49
 */

namespace vendor\project\helpers;

class redis
{
    private static $app;

    /**
     * @return \Redis
     */
    public static function app()
    {
        if (!self::$app) {
            $config = \Yii::$app->components['redis'];
            try {
                $redis = new \Redis();
                $redis->connect($config['hostname'], $config['port']);
                if(isset($config['password'])){
                    $redis->auth($config['password']);
                }
                self::$app = $redis;
            } catch (\Exception $e) {
                exit('Redis服务器链接错误或未开启');
            }
        }
        return self::$app;
    }
}