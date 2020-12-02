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
        'urlManager' => [
            'enablePrettyUrl' => true,
            'showScriptName' => false,
            'rules' => [],
        ],
    ],
];

if (YII_DEBUG) {
    $config['bootstrap'][] = 'debug';
    $config['modules']['debug'] = [
        'class' => 'yii\debug\Module',
    ];
}

return $config;
