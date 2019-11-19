<style>
    .btns {
        width: 100%;
        margin: 10px auto;
        border-bottom: 1.4px solid #000000;
        padding: 6px 0;
    }

    .btns > .btn {
        float: right;
        margin-left: 18px;
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
            <button type="button" class="btn btn-sm btn-danger clear">清空</button>
            <button type="button" class="btn btn-sm btn-warning stop">暂停</button>
            <button type="button" class="btn btn-sm btn-info begin">开始</button>
            <div style="clear: both"></div>
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
        $('.clear').click(function () {
            $('.text').html('');
        });
        socket.onmessage = function (event) {
            var data = JSON.parse(event.data);
            if (!isString(data.msg)) {
                var str = '<p>' + data.time + '</p><div class="info">';
                $.each(data.msg, function (k, v) {
                    str += k + ':' + decodeURI(v) + '; ';
                });
                str += '</div>';
                $('.text').prepend(str);
            }
        };
    };

    function isString(str) {
        return (typeof str == 'string') && str.constructor == String;
    }
</script>