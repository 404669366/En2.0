<?php
$gateway = new \GatewayWorker\Gateway("Tld://0.0.0.0:20002");
$gateway->name = 'GatewayServer-tld';
$gateway->lanIp = '47.99.36.149';
$gateway->registerAddress = '127.0.0.1:20000';
$gateway->startPort = 20050;
$gateway->count = 1;
$gateway->pingInterval = 30;
$gateway->pingNotResponseLimit = 1;