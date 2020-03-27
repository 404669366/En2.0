<?php $this->registerJsFile('@web/js/tree.js', ['depends' => ['app\assets\ModelAsset']]) ?>
<style>
    body {
        display: flex;
        justify-content: center;
        align-items: center;
        width: 100%;
        height: 100%;
    }
</style>
<canvas id="tree"></canvas>
<script>
    var socket = new WebSocket('ws://47.99.36.149:20001');
    socket.onopen = function () {
        var tree = window.tree('tree', 2019093001, 10).onClick(function (cnt) {
            console.log(cnt);
        });
        socket.send(JSON.stringify({do: 'joinGuns', pile: 2019093001}));
        socket.onmessage = function (event) {
            var data = JSON.parse(event.data);
            tree.draw(data);
        };
    };
</script>