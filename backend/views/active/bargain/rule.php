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
                    <td><?= $v ?></td>
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
            if ($(this).val() > 0) {
                $(this).parent('td').html($(this).val());
            } else {
                window.showMsg('配置必须大于0');
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
    })
</script>