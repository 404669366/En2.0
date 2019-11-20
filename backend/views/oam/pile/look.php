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
            <button type="button" class="btn btn-sm btn-white back">返回</button>
            <button type="button" class="btn btn-sm btn-danger clear">清空</button>
            <div style="clear: both"></div>
        </div>
        <div class="text"></div>
    </div>
</div>
<script>
    var socket = new WebSocket('ws://47.99.36.149:20001');
    socket.onopen = function () {
        $('.clear').click(function () {
            $('.text').html('');
        });
        socket.send('<?=$info?>');
        socket.onmessage = function (event) {
            var data = JSON.parse(event.data);
            var str = '<p>' + data.code + '</p><div class="info">';
            $.each(data.data, function (k, v) {
                str += k + ':' + decodeURI(v) + '; ';
            });
            str += '</div>';
            $('.text').prepend(str);
        };
    };
</script>