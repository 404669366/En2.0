<?php
/**
 * Created by PhpStorm.
 * User: zhangjiajing
 * Date: 2017/6/12
 * Time: 15:55
 */

namespace app\assets;

use yii\web\AssetBundle;

class LoginAsset extends AssetBundle
{
    public $basePath = '@webroot';
    public $baseUrl = '@web';
    public $css = [
        '/css/bootstrap.min.css',
        '/css/font-awesome.css',
        '/css/animate.css',
        '/css/style.css',
        '/css/login.css',
    ];
    public $js = [
        '/js/jquery.min.js',
        '/js/jquery.cookie.js',
        '/js/layer/layer.min.js',
        '/js/msg.js',
    ];
}
