<?php
$web = new \GatewayWorker\Gateway("websocket://0.0.0.0:20001");
$web->name = 'GatewayServer-web';
$web->registerAddress = '127.0.0.1:20000';
$web->startPort = 20075;
$web->count = 1;