<?php
/**
 * Created by PhpStorm.
 * User: miku
 * Date: 2018/10/19
 * Time: 17:12
 */

namespace vendor\project\helpers;

use OSS\Core\OssException;
use OSS\OssClient;

class Oss
{
    /**
     * 阿里云OSS上传文件
     * @param $file
     * @return bool
     */
    public static function aliUpload($file)
    {
        try {
            $name = Helper::randStr(3, 8, 1) . strrchr($file['name'], '.');
            $ossConfig = \Yii::$app->params['AliOss'];
            $content = file_get_contents($file['tmp_name']);
            $ossClient = new OssClient($ossConfig['accessKeyId'], $ossConfig['accessKeySecret'], $ossConfig['endPoint']);
            $ossClient->putObject($ossConfig['bucket'], $name, $content);
            return $ossConfig['url'] . $name;
        } catch (OssException $e) {
            return false;
        }
    }

    /**
     * 阿里云OSS删除文件
     * @param $name
     * @return bool
     */
    public static function aliDelete($name)
    {
        try {
            if ($name) {
                $ossConfig = \Yii::$app->params['AliOss'];
                $ossClient = new OssClient($ossConfig['accessKeyId'], $ossConfig['accessKeySecret'], $ossConfig['endPoint']);
                $ossClient->deleteObject($ossConfig['bucket'], str_replace(\Yii::$app->params['AliOss']['url'], '', $name));
            }
            return true;
        } catch (OssException $e) {
            return false;
        }
    }
}