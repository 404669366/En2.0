<?php
require __DIR__ . '/../event/events.php';
$worker = new \GatewayWorker\BusinessWorker();
$worker->name = 'worker';
$worker->registerAddress = '127.0.0.1:20000';
$worker->count = 2;
$worker->eventHandler = '\event\events';

