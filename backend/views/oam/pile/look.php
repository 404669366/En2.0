<div class="wrapper wrapper-content animated">
    <div class="ibox-content">

    </div>
</div>
<script>
    var code = JSON.parse('<?=$code?>');
    var socket = new WebSocket('ws://47.99.36.149:20001');
    socket.onopen = function () {
        socket.send('<?=$info?>');
        socket.onmessage = function (event) {
            var data = JSON.parse(event.data);

        };
    };
</script>