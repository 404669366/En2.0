<?php

return [
    'defaultRoute' => 'index/index/index',
    'loginRoute' => 'user/login/login',
    'logoutRoute' => 'user/login/logout',
    'User' => [
        'identityClass' => 'vendor\project\base\EnUser',
        'enableAutoLogin' => true,
    ],
    'DB' => [
        'class' => 'yii\db\Connection',
        'dsn' => 'mysql:host=127.0.0.1;dbname=en',
        'username' => 'root',
        'password' => 'root',
        'charset' => 'utf8',
    ],
    'Redis' => [
        'class' => 'yii\redis\Connection',
        'hostname' => '127.0.0.1',
        'password' => '',
        'port' => 6379,
        'database' => 0,
    ],
    'Cache' => [
        'class' => 'yii\caching\DbCache',
        'cacheTable' => 'en_cache',
        'gcProbability' => 1000000
    ],
    'AliOss' => [
        'url' => 'https://ascasc.oss-cn-hangzhou.aliyuncs.com/',
        'accessKeyId' => 'LTAI9s99tZC58pzG',
        'accessKeySecret' => 'usmBiqxU7jMYV9Gz7qSToq8J1Q8lWb',
        'endPoint' => 'oss-cn-hangzhou.aliyuncs.com',
        'bucket' => 'ascasc',
    ],
];
