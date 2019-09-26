<?php $this->registerJsFile('@web/js/modal.js', ['depends' => ['app\assets\ModelAsset']]) ?>
<div class="wrapper wrapper-content animated">
    <div class="ibox-content">
        <div class="dataTables_wrapper form-inline">
            <div class="row tableSearchBox">
                <div class="col-sm-10">
                    <span class="tableSpan">
                        综合搜索: <input class="searchField" type="text" value="" name="content"
                                     placeholder="编号/名称/标题/地址/用户" style="width: 18rem">
                    </span>
                    <span class="tableSpan">
                        场站来源: <select class="searchField" name="source">
                                <option value="">----</option>
                            <?php foreach (\vendor\project\helpers\Constant::fieldSource() as $k => $type): ?>
                                <option value="<?= $k ?>"><?= $type ?></option>
                            <?php endforeach; ?>
                        </select>
                    </span>
                    <span class="tableSpan">
                        业务类型: <select class="searchField" name="business_type">
                                <option value="">----</option>
                            <?php foreach (\vendor\project\helpers\Constant::businessType() as $k => $type): ?>
                                <option value="<?= $k ?>"><?= $type ?></option>
                            <?php endforeach; ?>
                        </select>
                    </span>
                    <span class="tableSpan">
                        投资类型: <select class="searchField" name="invest_type">
                                <option value="">----</option>
                            <?php foreach (\vendor\project\helpers\Constant::investType() as $k => $type): ?>
                                <option value="<?= $k ?>"><?= $type ?></option>
                            <?php endforeach; ?>
                        </select>
                    </span>
                    <span class="tableSpan">
                        状态: <select class="searchField" name="status">
                                <option value="">----</option>
                            <?php foreach (\vendor\project\helpers\Constant::fieldStatus() as $k => $type): ?>
                                <option value="<?= $k ?>"><?= $type ?></option>
                            <?php endforeach; ?>
                        </select>
                    </span>
                    <span>
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
                    <th>NO</th>
                    <th>场站来源</th>
                    <th>场站名称</th>
                    <th>场站标题</th>
                    <th>场站位置</th>
                    <th>业务类型</th>
                    <th>投资类型</th>
                    <th>场地用户</th>
                    <th>分成比例</th>
                    <th>融资情况</th>
                    <th>场站状态</th>
                    <th>创建时间</th>
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
            {"data": "no"},
            {"data": "source"},
            {
                "data": "name", "render": function (data, type, row) {
                return linFeed(data, 15);
            }
            },
            {
                "data": "title", "render": function (data, type, row) {
                return linFeed(data, 15);
            }
            },
            {
                "data": "address", "render": function (data, type, row) {
                return linFeed(data, 15);
            }
            },
            {"data": "business_type"},
            {"data": "invest_type"},
            {
                "data": "local", "render": function (data, type, row) {
                return data || '----';
            }
            },
            {"data": "ratio"},
            {"data": "info"},
            {"data": "status"},
            {
                "data": "created_at", "render": function (data, type, row) {
                return timeToDate(data);
            }
            },
            {
                "data": "id", "orderable": false, "render": function (data, type, row) {
                var str = '<a class="btn btn-sm btn-info" href="/field/field/info?id=' + data + '">详情</a>&emsp;';
                if (row.status === '待处理' || row.status === '挂起' || row.status === '审核不通过') {
                    str = '<a class="btn btn-sm btn-warning" href="/field/field/edit?id=' + data + '">编辑</a>&emsp;';
                }
                return str + '<button type="button" class="btn btn-sm btn-danger appoint" data-fid="' + data + '">指派</button>';
            }
            }
        ],
        default_order: [0, 'desc']
    });
    myTable.search();
    $('#table').on('click', '.appoint', function () {
        var content = '<input value="" placeholder="请输入要指派专员手机号" data-fid="' + $(this).data('fid') + '" type="text" style="width: 100%;height:6rem;line-height: 6rem;padding:1rem 0;border: none;background: none;text-align: center;font-size: 2rem"/>';
        window.modal({
            title: '指派专员',
            width: '40rem',
            height: '6rem',
            content: content,
            callback: function (event) {
                var input = event.find('input');
                if (input.data('fid') && input.val()) {
                    $.getJSON('/field/field/appoint', {id: input.data('fid'), tel: input.val()}, function (re) {
                        if (re.type) {
                            $('#table').find('[data-fid="' + input.data('fid') + '"]').parents('tr').remove();
                            showMsg('指派成功');
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