<?php
$config = [
    'id' => 'o7iT5DQyzc2IOZOxtJ9wi40yeCkYLp9',
    'basePath' => __DIR__ . '/../../',
    'vendorPath' => __DIR__ . '/../../../vendor',
    'timeZone' => 'Asia/Shanghai',
    'language' => 'zh-CN',
    'layout' => 'base',
    'layoutPath' => '@app/config/layouts',
    'defaultRoute' => 'index/index/index',
    'components' => [
        'request' => [
            'cookieValidationKey' => '49NWqzW2Gu1_PZ9pzxSkiykoishzMAz9',
        ],
        'cache' => [
            'class' => 'yii\caching\FileCache',
        ],
        'db' => [
            'class' => 'yii\db\Connection',
            'dsn' => 'mysql:host=47.99.36.149;dbname=en',
            'username' => 'root',
            'password' => 'fi9^BRLHschX%V96',
            'charset' => 'utf8',
        ],
        'user' => [
            'identityClass' => 'app\models\User',
            'enableAutoLogin' => true,
        ],
        'urlManager' => [
            'enablePrettyUrl' => true,
            'showScriptName' => false,
            'rules' => [],
        ],
    ],
    'aliases' => [
        '@bower' => '@vendor/bower-asset',
    ],
];

if (YII_DEBUG) {
    $config['bootstrap'][] = 'debug';
    $config['modules']['debug'] = [
        'class' => 'yii\debug\Module',
    ];
}
if (YII_GII) {
    $config['bootstrap'][] = 'gii';
    $config['modules']['gii'] = [
        'class' => 'yii\gii\Module',
    ];
}

return $config;
