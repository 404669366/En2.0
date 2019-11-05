<?php $this->registerJsFile('@web/js/modal.js', ['depends' => ['app\assets\ModelAsset']]) ?>
<style>
    .gunBox > div {
        margin-top: 2rem;
        text-align: center;
    }

    .gunBox > div:nth-child(-n+4) {
        margin-top: 0;
    }

    .gunBox > div > span {
        height: 2rem;
        line-height: 2rem;
        font-size: 1.5rem;
    }

    .gunBox > div > div > canvas {
        height: 7.8rem;
        width: 7.8rem;
    }

    .content {
        text-align: center;
    }

    .content > input {
        height: 3rem;
        line-height: 3rem;
        width: 12rem;
        font-size: 1.4rem;
        margin: 1rem 1rem 1rem auto;
    }

    table {
        width: 100%;
        line-height: 3rem;
        font-size: 1.4rem;
        text-align: center;
        border-color: silver;
        margin-bottom: 1rem;
    }

</style>
<div class="wrapper wrapper-content animated">
    <div class="ibox-content">
        <form method="post" class="form-horizontal">
            <input type="hidden" name="_csrf" value="<?= Yii::$app->request->csrfToken ?>">
            <div class="row">
                <div class="col-sm-12">
                    <div class="hr-line-dashed"></div>
                    <div class="form-group">
                        <label class="col-sm-1 control-label">电桩编号</label>
                        <div class="col-sm-10">
                            <input type="text" class="form-control no" value="<?= $model->no ?>" readonly>
                        </div>
                    </div>
                </div>
                <div class="col-sm-6">
                    <div class="hr-line-dashed"></div>
                    <div class="form-group">
                        <label class="col-sm-2 control-label">电桩状态</label>
                        <div class="col-sm-8">
                            <input type="text" class="form-control"
                                   placeholder="<?= \vendor\project\helpers\Constant::pileOnline()[$model->online] ?>"
                                   readonly>
                        </div>
                    </div>
                    <div class="hr-line-dashed"></div>
                    <div class="form-group">
                        <label class="col-sm-2 control-label">归属电站</label>
                        <div class="col-sm-8">
                            <input type="text" class="form-control" readonly placeholder="<?= $model->field ?>">
                        </div>
                    </div>
                </div>
                <div class="col-sm-6">
                    <div class="hr-line-dashed"></div>
                    <div class="form-group">
                        <label class="col-sm-2 control-label">枪口数量</label>
                        <div class="col-sm-8">
                            <input type="text" class="form-control" placeholder="<?= $model->count ?>" readonly>
                        </div>
                    </div>
                    <div class="hr-line-dashed"></div>
                    <div class="form-group">
                        <label class="col-sm-2 control-label">电桩类型</label>
                        <div class="col-sm-8">
                            <input type="text" class="form-control" placeholder="<?= $models[$model->model_id] ?>"
                                   readonly>
                        </div>
                    </div>
                </div>
                <div class="col-sm-12">
                    <div class="hr-line-dashed"></div>
                    <div class="form-group">
                        <input type="hidden" name="rules" value='<?= $model->rules ?>'>
                        <label class="col-sm-1 control-label">计费规则</label>
                        <div class="col-sm-10">
                            <table class="rulesTable" border="1">
                                <tr>
                                    <td>开始时间</td>
                                    <td>结束时间</td>
                                    <td>基础电价</td>
                                    <td>服务电价</td>
                                </tr>
                            </table>
                            <div style="text-align: right">
                                <button type="button" class="btn btn-danger delRule">删除规则</button>
                                &emsp;
                                <button type="button" class="btn btn-primary addRule">添加规则</button>
                                &emsp;
                                <button type="submit" class="btn btn-success save">保存</button>
                                &emsp;
                                <a class="btn btn-white" href="/oam/field/pile?no=<?= $model->field ?>">返回</a>
                                &emsp;
                                <button type="button" class="btn btn-info" onclick="location.reload()">刷新</button>
                            </div>
                        </div>
                    </div>
                    <div class="hr-line-dashed"></div>
                    <div class="form-group">
                        <label class="col-sm-1 control-label">枪口信息</label>
                        <div class="col-sm-10">
                            <table class="gunTable" border="1">
                                <tr>
                                    <td>枪口编码</td>
                                    <td>连接状态</td>
                                    <td>枪口状态</td>
                                    <td>枪口操作</td>
                                </tr>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </form>
    </div>
</div>
<script>
    var code = JSON.parse(`<?=$code?>`);
    var work = JSON.parse(`<?=$work?>`);
    var link = JSON.parse(`<?=$link?>`);
    var socket = new WebSocket('ws://47.99.36.149:20001');
    socket.onopen = function () {
        var no = $('.no').val();
        socket.send(JSON.stringify({do: 'seePile', pile: no}));
        setInterval(function () {
            socket.send(JSON.stringify({do: 'seePile', pile: no}));
        }, 2000);
        socket.onmessage = function (event) {
            var data = JSON.parse(event.data);
            if (data.code === 600) {
                $('.gunTable').html('<tr><td>枪口编码</td><td>连接状态</td><td>枪口状态</td><td>枪口操作</td></tr>');
                $.each(data.data.status || [], function (k, v) {
                    var str = '<tr>';
                    str += '<td>' + no + '-' + k + '</td>';
                    str += '<td>' + link[v.linkStatus] + '</td>';
                    str += '<td>' + work[v.workStatus] + '</td>';
                    if (v.workStatus === 2 && v.linkStatus) {
                        str += '<td><button type="button" class="btn btn-sm btn-danger endCharge" data-no="' + no + '" data-gun="' + k + '">结束充电</button></td>';
                    } else {
                        str += '<td>----</td>';
                    }
                    str += '</tr>';
                    $('.gunTable').append(str);
                });
            } else {
                window.showMsg(code[data.code]);
            }
        };

        $('.gunTable').on('click', '.endCharge', function () {
            socket.send(JSON.stringify({do: 'endCharge', pile: $(this).data('no'), gun: $(this).data('gun')}));
        })

    };

    var rules = JSON.parse($('[name="rules"]').val());
    var str = '';
    $.each(rules, function (k1, v1) {
        str += '<tr>';
        $.each(v1, function (k2, v2) {
            if (k2 === 0 || k2 === 1) {
                str += '<td>' + timeToStr(v2) + '</td>';
            } else {
                str += '<td>' + v2 + '</td>';
            }
        });
        str += '</tr>';
    });
    $('.rulesTable').append(str);
    $('.addRule').click(function () {
        rules = JSON.parse($('[name="rules"]').val());
        var key = 0;
        var begin = '00:00';
        if (rules.length) {
            key = rules.length;
            begin = rules[key - 1][1];
            begin = timeToStr(begin);
            if (begin === '24:00') {
                window.showMsg('规则已完整');
                return;
            }
        }
        var content = '<label>开始时间:</label><input name="begin" type="text" value="' + begin + '" readonly/>';
        content += '<label>结束时间:</label><input name="end" type="text" value="24:00"/>';
        content += '<label>基础电价:</label><input class="basis" type="text" value="0.8"/>';
        content += '<label>服务电价:</label><input class="service" type="text" value="0.6"/>';
        window.modal({
            title: '添加规则',
            width: '40rem',
            height: '10rem',
            content: content,
            callback: function (event) {
                var thisBegin = strToTime(event.find('[name="begin"]').val());
                var thisEnd = strToTime(event.find('[name="end"]').val());
                if (thisEnd > thisBegin) {
                    if (thisEnd <= 86400) {
                        rules[key] = [thisBegin, thisEnd, event.find('.basis').val(), event.find('.service').val()];
                        str = '<tr>';
                        $.each(rules[key], function (k, v) {
                            if (k === 0 || k === 1) {
                                str += '<td>' + timeToStr(v) + '</td>';
                            } else {
                                str += '<td>' + v + '</td>';
                            }
                        });
                        $('.rulesTable').append(str + '</tr>');
                        $('[name="rules"]').val(JSON.stringify(rules));
                        event.close();
                        return;
                    }
                    event.find('.end').val('24:00');
                    window.showMsg('结束时间不大于24:00');
                    return;
                }
                event.find('.end').val('24:00');
                window.showMsg('结束时间必须大于开始时间');
                return;
            }
        });
    });
    $('.delRule').click(function () {
        rules = JSON.parse($('[name="rules"]').val());
        if (!rules.length) {
            window.showMsg('请先添加计费规则');
            return;
        }
        $('.rulesTable').find('tr').eq(rules.length).remove();
        rules.splice(rules.length - 1, 1);
        $('[name="rules"]').val(JSON.stringify(rules));
    });
    $('.save').click(function () {
        var rule = JSON.parse($('[name="rules"]').val());
        if (rule.length) {
            if (timeToStr(rule[rule.length - 1][1]) === '24:00') {
                return true;
            }
            window.showMsg('请添加全时间段计费规则(00:00-24:00)');
            return false;
        }
        window.showMsg('请添加计费规则');
        return false;
    });

    function timeToStr(time) {
        var hour = Math.floor(time / 3600);
        return prefixZero(hour, 2) + ':' + prefixZero(Math.floor((time - hour * 3600) / 60), 2);
    }

    function strToTime(time) {
        var arr = time.split(':');
        if (arr.length === 2) {
            return parseInt(arr[0], 10) * 3600 + parseInt(arr[1], 10) * 60;
        }
        return 86400;
    }

    function prefixZero(num, length) {
        return (Array(length).join('0') + num).slice(-length);
    }

</script>