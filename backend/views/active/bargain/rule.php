<?php $this->registerJsFile('@web/js/modal.js', ['depends' => ['app\assets\ModelAsset']]) ?>
<style>
    td {
        text-align: center;
        -webkit-user-select: none;
        -moz-user-select: none;
        -o-user-select: none;
    }

    input {
        width: 50px;
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
</style>
<div class="wrapper wrapper-content animated">
    <div class="ibox-content">
        <h4 style="color: red;height: 30px;line-height: 30px;">双击编辑
            <button type="button" class="btn btn-sm btn-info save" style="float: right;">保存</button>
            <button type="button" class="btn btn-sm btn-primary add" style="float: right;margin-right: 15px">添加</button>
        </h4>
        <table class="table table-striped table-bordered table-hover dataTable">
            <thead>
            <tr>
                <td>起始金额</td>
                <td>最少刀数</td>
                <td>操&emsp;&emsp;作</td>
            </tr>
            </thead>
            <tbody>
            <?php foreach ($rule as $k => $v): ?>
                <tr>
                    <td><?= $k ?></td>
                    <td class="c"><?= $v ?></td>
                    <td class="do">
                        <button type="button" class="btn btn-sm btn-danger del">删除</button>
                    </td>
                </tr>
            <?php endforeach; ?>
            </tbody>
        </table>
    </div>
</div>

<script>
    $('tbody')
        .on('dblclick', 'td:not(.do)', function () {
            var v = $(this).text();
            if (v > 0) {
                $(this).html('<input data-old="' + v + '"/>');
                $('input').focus().val(v);
            } else {
                window.showMsg('不能编辑起始金额');
            }

        })
        .on('blur', 'input', function () {
            var v = $(this).val();
            if ($(this).parent('td').hasClass('c')) {
                v = Math.floor(v);
            }
            if (v > 0) {
                $(this).parent('td').html($(this).val());
            } else {
                window.showMsg('起始金额必须大于0且最少刀数必须是大于1的正整数');
                $(this).parent('td').html($(this).data('old'));
            }
        })
        .on('click', '.del', function () {
            if ($(this).parents('tr').children(':first').text() > 0) {
                $(this).parents('tr').remove();
            } else {
                window.showMsg('起始规则不能删除');
            }
        });
    $('.save').on('click', function () {
        var rule = [];
        rule['_csrf'] = '<?=Yii::$app->request->csrfToken ?>';
        $('tbody').find('tr').each(function (k, v) {
            rule[$(v).children(':first').text()] = $(v).children(':nth-child(2)').text();
        });
        postCall('/active/bargain/rule', rule);
    });
    $('.add').on('click', function () {
        var content = '<label>起始金额:</label><input class="begin"/>';
        content += '<label>最少刀数:</label><input class="count"/>';
        window.modal({
            title: '添加配置',
            width: '40rem',
            height: '6rem',
            content: content,
            callback: function (event) {
                var begin = event.find('.begin').val();
                var count = Math.floor(event.find('.count').val());
                if (begin > 0 && count > 0) {
                    $('tbody').append('  <tr>\n' +
                        '                    <td>' + begin + '</td>\n' +
                        '                    <td>' + count + '</td>\n' +
                        '                    <td class="do">\n' +
                        '                        <button type="button" class="btn btn-sm btn-danger del">删除</button>\n' +
                        '                    </td>\n' +
                        '                </tr>');
                    event.close();
                    var scrollHeight = $('body').prop("scrollHeight");
                    $('body').animate({scrollTop: scrollHeight}, 100);
                } else {
                    window.showMsg('起始金额必须大于0且最少刀数必须是大于1的正整数');
                }
            }
        });
    })
</script>