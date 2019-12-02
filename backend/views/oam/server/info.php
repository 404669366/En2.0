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

    h3 {
        margin: 0;
    }

    .clear {
        display: inline-block;
        margin-left: 2rem;
    }

    .info > .data {
        margin: 1rem 0;
    }
</style>
<div class="wrapper wrapper-content animated">
    <div class="ibox-content">
        <div class="btns">
            <button type="button" class="btn btn-sm btn-warning stop">暂停</button>
            <button type="button" class="btn btn-sm btn-info begin">开始</button>
            <div style="clear: both"></div>
        </div>
        <div class="104 info">
            <h3>
                104报文
                <button type="button" class="btn btn-sm btn-danger clear">清空</button>
            </h3>
            <div class="data"></div>
        </div>
        <div class="202 info">
            <h3>
                202报文
                <button type="button" class="btn btn-sm btn-danger clear">清空</button>
            </h3>
            <div class="data"></div>
        </div>
        <div class="108 info">
            <h3>
                108报文
                <button type="button" class="btn btn-sm btn-danger clear">清空</button>
            </h3>
            <div class="data"></div>
        </div>
        <div class="do info">
            <h3>
                操作报文
                <button type="button" class="btn btn-sm btn-danger clear">清空</button>
            </h3>
            <div class="data"></div>
        </div>
    </div>
</div>
<script>
    var ws = JSON.parse(`<?=$ws?>`);
    var ls = JSON.parse(`<?=$ls?>`);
    var socket = new WebSocket('ws://47.99.36.149:20001');
    socket.onopen = function () {
        $('.begin').click(function () {
            socket.send(JSON.stringify({do: 'joinServer'}));
        });
        $('.stop').click(function () {
            socket.send(JSON.stringify({do: 'leaveServer'}));
        });
        $('.clear').click(function () {
            $(this).parents('.info').find('.data').html('');
        });
        socket.onmessage = function (event) {
            var data = JSON.parse(event.data);
            if (data.msg.cmd) {

            } else {
                var cla = '';
                $.each(data.msg, function (k, v) {
                    cla += v;
                });
                var last = $('.do>.data').find('.' + cla);
                if (last.length) {
                    last.text(JSON.stringify(data));
                } else {
                    $('.do>.data').prepend('<p class="' + cla + '">' + JSON.stringify(data) + '</p>');
                }
            }
        };
    };
</script>