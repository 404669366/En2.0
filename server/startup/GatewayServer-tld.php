<?php
$gateway = new \GatewayWorker\Gateway("Tld://0.0.0.0:20002");
$gateway->name = 'GatewayServer-tld';
$gateway->registerAddress = '127.0.0.1:20000';
$gateway->startPort = 20050;
$gateway->count = 1;
$gateway->pingInterval = 60;
$gateway->pingNotResponseLimit = 1;