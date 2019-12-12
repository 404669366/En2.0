<?php
ini_set('date.timezone', 'Asia/Shanghai');
if (strpos(strtolower(PHP_OS), 'win') === 0) {
    exit("start.php not support windows, Sorry\n");
}

if (!extension_loaded('pcntl')) {
    exit("Please install pcntl extension. See http://doc3.workerman.net/appendices/install-extension.html\n");
}

if (!extension_loaded('posix')) {
    exit("Please install posix extension. See http://doc3.workerman.net/appendices/install-extension.html\n");
}

require __DIR__ . '/../vendor/autoload.php';
require __DIR__ . '/event/events.php';

foreach (glob(__DIR__ . '/startup/*.php') as $file) {
    require_once $file;
}

\Workerman\Worker::runAll();
