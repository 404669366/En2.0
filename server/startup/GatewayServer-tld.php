<?php
$gateway = new \GatewayWorker\Gateway("Tld://0.0.0.0:20002");
$gateway->name = 'GatewayServer-tld';
$gateway->registerAddress = '127.0.0.1:20000';
$gateway->lanIp = '47.99.36.149';
$gateway->startPort = 3000;
$gateway->count = 1;
$gateway->pingInterval = 30;
$gateway->pingNotResponseLimit = 1;