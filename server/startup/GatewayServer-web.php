<?php
$gateway = new \GatewayWorker\Gateway("websocket://0.0.0.0:20001");
$gateway->name = 'GatewayServer-web';
$gateway->lanIp = '172.16.161.173';
$gateway->registerAddress = '172.16.161.173:20000';
$gateway->startPort = 20075;
$gateway->count = 1;