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
            var str = '<p>' + data.time + '</p><p>';
            $.each(data.msg, function (k, v) {
                str += k + ':' + decodeURI(v) + '; ';
            });
            str += '</p>';
            $('.text').prepend(str);
        };
    };
</script>