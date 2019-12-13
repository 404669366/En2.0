<?php
$gateway = new \GatewayWorker\Gateway("websocket://0.0.0.0:20001");
$gateway->name = 'GatewayServer-web';
$gateway->registerAddress = '127.0.0.1:20000';
$gateway->startPort = 22000;
$gateway->count = 1;