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
            $ossClient = new OssClient($ossConfig['accessKeyId'], $ossConfig['accessKeySecret'], $ossConfig['endPoint']);
            $ossClient->putObject($ossConfig['bucket'], $name, file_get_contents($file['tmp_name']), null, $file['name']);
            return $ossConfig['url'] . $name;
        } catch (OssException $e) {
            return false;
        }
    }

    /**
     * 阿里云OSS删除文件
     * @param $name
     * @return bool|null
     */
    public static function aliDelete($name)
    {
        try {
            $ossConfig = \Yii::$app->params['AliOss'];
            $ossClient = new OssClient($ossConfig['accessKeyId'], $ossConfig['accessKeySecret'], $ossConfig['endPoint']);
            return $ossClient->deleteObject($ossConfig['bucket'], $name);
        } catch (OssException $e) {
            return false;
        }
    }
}