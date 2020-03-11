<?php
$jm = new \GatewayWorker\Gateway("Jm://0.0.0.0:20002");
$jm->name = 'GatewayServer-jm';
$jm->registerAddress = '127.0.0.1:20000';
$jm->startPort = 20050;
$jm->count = 1;