<?php $this->registerJsFile('@web/js/modal.js', ['depends' => ['app\assets\ModelAsset']]) ?>
<div class="wrapper wrapper-content animated">
    <div class="ibox-content">
        <div class="dataTables_wrapper form-inline">
            <div class="row tableSearchBox">
                <div class="col-sm-10">
                    <span class="tableSpan">
                        综合搜索: <input class="searchField" type="text" value="" name="content"
                                     placeholder="意向编号/场站编号/意向用户">
                    </span>
                    <span class="tableSpan">
                        来源: <select class="searchField" name="source">
                                <option value="">----</option>
                            <?php foreach (\vendor\project\helpers\Constant::intentionSource() as $k => $type): ?>
                                <option value="<?= $k ?>"><?= $type ?></option>
                            <?php endforeach; ?>
                        </select>
                    </span>
                    <span class="tableSpan">
                        状态: <select class="searchField" name="status">
                                <option value="">----</option>
                            <?php foreach (\vendor\project\helpers\Constant::intentionStatus() as $k => $type): ?>
                                <option value="<?= $k ?>"><?= $type ?></option>
                            <?php endforeach; ?>
                        </select>
                    </span>
                    <span class="tableSpan">
                        用户删除状态: <select class="searchField" name="delete">
                                <option value="">----</option>
                            <?php foreach (\vendor\project\helpers\Constant::intentionDelete() as $k => $type): ?>
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
                    <a class="btn btn-sm btn-warning" href="/field/intention/get">抢单(<?= $count ?>)</a>
                    &emsp;
                    <a class="btn btn-sm btn-primary" href="/field/intention/edit">添加</a>
                </div>
            </div>
            <table class="table table-striped table-bordered table-hover dataTable" id="table">
                <thead>
                <tr role="row">
                    <th>NO</th>
                    <th>意向来源</th>
                    <th>场站编号</th>
                    <th>意向用户</th>
                    <th>认购金额</th>
                    <th>分成比例</th>
                    <th>创建时间</th>
                    <th>用户删除</th>
                    <th>意向状态</th>
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
        url: '/field/intention/data',
        length: 10,
        columns: [
            {"data": "no"},
            {"data": "source"},
            {"data": "field"},
            {"data": "user"},
            {"data": "amount"},
            {"data": "ratio"},
            {
                "data": "created_at", "render": function (data, type, row) {
                return timeToDate(data);
            }
            },
            {"data": "delete"},
            {"data": "status"},
            {
                "data": "id", "orderable": false, "render": function (data, type, row) {
                var str = '<button type="button" class="btn btn-sm btn-primary appoint" data-fid="' + data + '">指派</button>';
                if (row.status === '等待沟通' || row.status === '合同签署') {
                    str += '&emsp;<a class="btn btn-sm btn-warning" href="/field/intention/edit?id=' + data + '">编辑</a>';
                }
                if (row.status === '合同审核' || row.status === '等待打款' || row.status === '打款审核' || row.status === '审核通过' || row.status === '用户违约') {
                    str += '&emsp;<a class="btn btn-sm btn-info" href="/field/intention/info?id=' + data + '">详情</a>';
                }
                if (row.status === '合同签署' || row.status === '合同审核' || row.status === '等待打款') {
                    str += '&emsp;<a class="btn btn-sm btn-danger" href="/field/intention/break?id=' + data + '">违约</a>';
                }
                return str;
            }
            }
        ],
        default_order: [6, 'desc']
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
                    $.getJSON('/field/intention/appoint', {id: input.data('fid'), tel: input.val()}, function (re) {
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