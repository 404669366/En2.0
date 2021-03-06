<?php $this->registerJsFile('@web/js/modal.js', ['depends' => ['app\assets\ModelAsset']]) ?>
<div class="wrapper wrapper-content animated">
    <div class="ibox-content">
        <div class="dataTables_wrapper form-inline">
            <div class="row tableSearchBox">
                <div class="col-sm-10">
                    <span class="tableSpan">
                        综合搜索: <input class="searchField" type="text" value="" name="key"
                                     placeholder="编号/名称/标题/地址/企业/专员/用户" style="width: 18rem">
                    </span>
                    <span class="tableSpan">
                        场站状态: <select class="searchField" name="status">
                                <option value="">----</option>
                            <?php foreach ($status as $k => $v): ?>
                                <option value="<?= $k ?>"><?= $v ?></option>
                            <?php endforeach; ?>
                        </select>
                    </span>
                    <span class="tableSpan">
                        上线状态: <select class="searchField" name="online">
                                <option value="">----</option>
                            <?php foreach ($online as $k => $v): ?>
                                <option value="<?= $k ?>"><?= $v ?></option>
                            <?php endforeach; ?>
                        </select>
                    </span>
                    <span class="tableSpan">
                        <button class="tableSearch">搜索</button>
                        <button class="tableReload">重置</button>
                    </span>
                </div>
                <div class="col-sm-2">
                    <a class="btn btn-sm btn-warning" data-rid="21" href="/field/field/get">抢单(<?= $count ?>)</a>
                    &emsp;
                    <a class="btn btn-sm btn-info" href="/field/field/edit">添加场地</a>
                </div>
            </div>
            <table class="table table-striped table-bordered table-hover dataTable" id="table">
                <thead>
                <tr role="row">
                    <th>场站信息</th>
                    <th>归属信息</th>
                    <th>股权情况</th>
                    <th>场站状态</th>
                    <th>操作</th>
                </tr>
                </thead>
            </table>
        </div>
    </div>
</div>
<script>
    myTable.load({
        table: '#table',
        url: '/field/field/data',
        length: 10,
        columns: [
            {"data": "data"},
            {"data": "uInfo"},
            {"data": "stock"},
            {"data": "statusInfo"},
            {
                "data": "no", "orderable": false, "render": function (data, type, row) {
                var str = '<a class="btn btn-sm btn-info" href="/field/field/info?no=' + data + '">详情</a>&emsp;';
                if (row.status == 0 || row.status == 1) {
                    str = '<a class="btn btn-sm btn-warning" href="/field/field/edit?no=' + data + '">编辑</a>&emsp;';
                }
                return str + '<button type="button" class="btn btn-sm btn-danger appoint" data-no="' + data + '">指派</button>';
            }
            }
        ],
        default_order: [4, 'asc']
    });
    myTable.search();
    $('#table').on('click', '.appoint', function () {
        var content = '<input value="" placeholder="请输入要指派专员手机号" data-no="' + $(this).data('no') + '" type="text" style="width: 100%;height:6rem;line-height: 6rem;padding:1rem 0;border: none;background: none;text-align: center;font-size: 2rem"/>';
        window.modal({
            title: '指派专员',
            width: '40rem',
            height: '6rem',
            content: content,
            callback: function (event) {
                var input = event.find('input');
                if (input.data('no') && input.val()) {
                    $.getJSON('/field/field/appoint', {no: input.data('no'), tel: input.val()}, function (re) {
                        if (re.type) {
                            window.location.reload();
                            $.cookie('message-data', '指派成功', {path: '/'});
                            event.close();
                        } else {
                            showMsg(re.msg);
                        }
                    })
                } else {
                    showMsg('请输入要指派专员手机号');
                }
            }
        });
    })
</script>