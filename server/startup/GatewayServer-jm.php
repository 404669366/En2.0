<?php
$gateway = new \GatewayWorker\Gateway("Jm://0.0.0.0:20002");
$gateway->name = 'GatewayServer-jm';
$gateway->registerAddress = '127.0.0.1:20000';
$gateway->startPort = 20050;
$gateway->count = 1;