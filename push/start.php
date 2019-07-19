<?php

use Workerman\Worker;
use PHPSocketIO\SocketIO;

include __DIR__ . '/../vendor/autoload.php';

$sender_io = new SocketIO(2120);

$sender_io->on('connection', function ($socket) {
    $socket->on('bind', function ($group) use ($socket) {
        $socket->join($group);
    });
});

$sender_io->on('workerStart', function () {

    $http = new Worker('http://0.0.0.0:2121');

    $http->onMessage = function ($connection, $data) {
        // 推送数据的url格式 token=BC-9fdad4748325434b84e113ef10ad8b2e&do=publish&group=group&content=xxxx
        if (isset($_POST['token']) && $_POST['token'] == 'BC-9fdad4748325434b84e113ef10ad8b2e') {
            switch (@$_POST['do']) {
                case 'publish':
                    global $sender_io;
                    if (@$_POST['group']) {
                        $sender_io->to($_POST['group'])->emit('msg', @$_POST['content']);
                        return $connection->send('ok');
                    }
                    return $connection->send('错误分组');
                    break;
            }
            return $connection->send('错误操作');
        }
        return $connection->send('错误鉴权');
    };

    $http->listen();

});

Worker::runAll();
