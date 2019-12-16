<?php
$worker = new \GatewayWorker\BusinessWorker();
$worker->name = 'BusinessServer';
$worker->registerAddress = '127.0.0.1:20000';
$worker->count = 2;

