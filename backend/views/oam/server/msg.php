<style>
    p {
        line-height: 12px;
        margin: 0;
    }

    .info {
        line-height: 16px;
        border-bottom: 1px solid silver;
    }
</style>
<div class="wrapper wrapper-content animated">
    <div class="ibox-content text"></div>
</div>
<script>
    var socket = new WebSocket('ws://47.99.36.149:20001');
    socket.onopen = function () {
        socket.send(JSON.stringify({do: 'seeServer'}));
        socket.onmessage = function (event) {
            var data = JSON.parse(event.data);
            console.log(data);
            var str = '<p>' + data.time + '</p><div class="info">';
            $.each(data.msg, function (k, v) {
                str += k + ':' + decodeURI(v) + '; ';
            });
            str += '</div>';
            $('.text').prepend(str);
        };
    };
</script>