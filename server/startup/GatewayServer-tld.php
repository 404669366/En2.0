<?php
$gateway = new \GatewayWorker\Gateway("Tld://0.0.0.0:20002");
$gateway->name = 'GatewayServer-tld';
$gateway->lanIp = '172.16.161.173';
$gateway->registerAddress = '172.16.161.173:20000';
$gateway->startPort = 20050;
$gateway->count = 1;
$gateway->pingInterval = 30;
$gateway->pingNotResponseLimit = 1;