<?php
define('YII_DEBUG', true);
define('YII_GII', true);

require __DIR__ . '/../../vendor/autoload.php';
require __DIR__ . '/../../vendor/yiisoft/yii2/Yii.php';
$config = require __DIR__ . '/../config/params/params.php';

(new yii\web\Application($config))->run();
