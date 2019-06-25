<?php
/**
 * Created by PhpStorm.
 * User: zhangjiajing
 * Date: 2017/6/12
 * Time: 15:55
 */

namespace app\assets;

use yii\web\AssetBundle;

class FrameAsset extends AssetBundle
{
    public $basePath = '@webroot';
    public $baseUrl = '@web';
    public $css = [
        '/css/bootstrap.min.css',
        '/css/font-awesome.css',
        '/css/animate.css',
        '/css/style.css',

    ];
    public $js = [
        '/js/jquery.min.js',
        '/js/bootstrap.min.js',
        '/js/jquery.metisMenu.js',
        '/js/jquery.slimscroll.min.js',
        '/js/hplus.js',
        '/js/contabs.js',
        '/js/pace.min.js',
        '/js/imgPreview.js',
    ];
}
