<?php
$worker = new \GatewayWorker\BusinessWorker();
$worker->name = 'BusinessServer';
$worker->registerAddress = '172.16.161.173:20000';
$worker->count = 2;

