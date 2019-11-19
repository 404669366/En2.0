<style>
    .btns {
        width: 100%;
        margin: 10px auto;
        text-align: right;
    }

    p {
        line-height: 12px;
        margin: 0;
    }

    .info {
        width: 100%;
        line-height: 18px;
        border-bottom: 1px solid silver;
        padding: 2px 0;
        margin: 0 auto 6px auto;
    }
</style>
<div class="wrapper wrapper-content animated">
    <div class="ibox-content">
        <div class="btns">
            <button type="button" class="btn btn-sm btn-info begin">开始</button>
            &emsp;
            <button type="button" class="btn btn-sm btn-warning stop">暂停</button>
        </div>
        <div class="text"></div>
    </div>
</div>
<script>
    var socket = new WebSocket('ws://47.99.36.149:20001');
    socket.onopen = function () {
        $('.begin').click(function () {
            socket.send(JSON.stringify({do: 'joinServer'}));
        });
        $('.stop').click(function () {
            socket.send(JSON.stringify({do: 'leaveServer'}));
        });
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