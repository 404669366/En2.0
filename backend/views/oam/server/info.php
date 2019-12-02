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
        width: 100%;
    }

    .info > .data > p {
        width: 100%;
        line-height: 16px;
        margin: 0;
    }
</style>
<div class="wrapper wrapper-content animated">
    <div class="ibox-content">
        <div class="btns">
            <button type="button" class="btn btn-sm btn-warning stop">暂停</button>
            <button type="button" class="btn btn-sm btn-info begin">开始</button>
            <div style="clear: both"></div>
        </div>
        <div class="102 info">
            <h3>
                102报文
                <button type="button" class="btn btn-sm btn-danger clear">清空</button>
            </h3>
            <div class="data"></div>
        </div>
        <div class="104 info">
            <h3>
                104报文
                <button type="button" class="btn btn-sm btn-danger clear">清空</button>
            </h3>
            <div class="data"></div>
        </div>
        <div class="106 info">
            <h3>
                106报文
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
        <div class="202 info">
            <h3>
                202报文
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
            var cla = '';
            var last;
            var data = JSON.parse(event.data);
            if (data.msg.cmd) {
                var str = '';
                if (data.msg.cmd == 102) {
                    cla = data.msg.no;
                    last = $('.102>.data').find('.' + cla);
                    $.each(data.msg, function (k, v) {
                        str += k + ':' + decodeURI(v) + '; ';
                    });
                    if (last.length) {
                        last.text(str);
                    } else {
                        $('.102>.data').prepend('<p class="' + cla + '">' + str + '</p>');
                    }
                }
                if (data.msg.cmd == 104) {
                    cla = data.msg.no + data.msg.gun;
                    last = $('.104>.data').find('.' + cla);
                    $.each(data.msg, function (k, v) {
                        str += k + ':' + decodeURI(v) + '; ';
                    });
                    if (last.length) {
                        last.text(str);
                    } else {
                        $('.104>.data').prepend('<p class="' + cla + '">' + str + '</p>');
                    }
                }
                if (data.msg.cmd == 106) {
                    cla = data.msg.no;
                    last = $('.106>.data').find('.' + cla);
                    $.each(data.msg, function (k, v) {
                        str += k + ':' + decodeURI(v) + '; ';
                    });
                    if (last.length) {
                        last.text(str);
                    } else {
                        $('.106>.data').prepend('<p class="' + cla + '">' + str + '</p>');
                    }
                }
                if (data.msg.cmd == 108) {
                    cla = data.msg.no;
                    last = $('.108>.data').find('.' + cla);
                    $.each(data.msg, function (k, v) {
                        str += k + ':' + decodeURI(v) + '; ';
                    });
                    if (last.length) {
                        last.text(str);
                    } else {
                        $('.108>.data').prepend('<p class="' + cla + '">' + str + '</p>');
                    }
                }
                if (data.msg.cmd == 202) {
                    cla = data.msg.orderNo;
                    last = $('.202>.data').find('.' + cla);
                    $.each(data.msg, function (k, v) {
                        str += k + ':' + decodeURI(v) + '; ';
                    });
                    if (last.length) {
                        last.text(str);
                    } else {
                        $('.202>.data').prepend('<p class="' + cla + '">' + str + '</p>');
                    }
                }
            } else {
                $.each(data.msg, function (k, v) {
                    cla += v;
                });
                last = $('.do>.data').find('.' + cla);
                if (last.length) {
                    last.text(JSON.stringify(data));
                } else {
                    $('.do>.data').prepend('<p class="' + cla + '">' + JSON.stringify(data) + '</p>');
                }
            }
        };
    };
</script>