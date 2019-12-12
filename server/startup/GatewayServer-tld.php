<?php
$gateway = new \GatewayWorker\Gateway("Tld://0.0.0.0:20002");
$gateway->name = 'GatewayServer-tld';
$gateway->startPort = 3000;
$gateway->registerAddress = '127.0.0.1:20000';
$gateway->count = 2;
$gateway->pingInterval = 30;
$gateway->pingNotResponseLimit = 1;