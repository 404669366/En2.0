<?php

$params = require __DIR__ . '/params.php';
$config = [
    'id' => 'o7iT5DQyzc2IOZOxtJ9wi40yeCkYLp7',
    'basePath' => dirname(__DIR__),
    'timeZone' => 'Asia/Shanghai',
    'vendorPath' => __DIR__ . '/../../vendor',
    'bootstrap' => ['log'],
    'language' => 'zh-CN',
    'layout' => false,
    'defaultRoute' => $params['defaultRoute'],
    'aliases' => [
        '@bower' => '@vendor/bower-asset',
        '@npm' => '@vendor/npm-asset',
    ],
    'components' => [
        'db' => $params['DB'],
        'request' => [
            'cookieValidationKey' => '49NWqzW2Gu1_PZ9pzxSkiykoishzMAz9',
        ],
        'cache' => $params['Cache'],
        'mailer' => [
            'class' => 'yii\swiftmailer\Mailer',
            'useFileTransport' => true,
        ],
        'log' => [
            'traceLevel' => YII_DEBUG ? 3 : 0,
            'targets' => [
                [
                    'class' => 'yii\log\FileTarget',
                    'levels' => ['error', 'warning'],
                ],
            ],
        ],
        'urlManager' => [
            'enablePrettyUrl' => true,
            'showScriptName' => false,
            'suffix' => '.html',
            'rules' => [
            ],
        ],
        'errorHandler' => [
            'errorAction' => 'basis/basis/error',
        ],
        'redis' => $params['Redis'],
        'user' => $params['User'],
    ],
    'params' => $params,
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
