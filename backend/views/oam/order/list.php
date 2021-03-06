<div class="wrapper wrapper-content animated">
    <div class="ibox-content">
        <div class="dataTables_wrapper form-inline">
            <div class="row tableSearchBox">
                <div class="col-sm-11">
                    <span class="tableSpan">
                        综合搜索: <input class="searchField" type="text" value="" name="keywords"
                                     placeholder="订单编号/电站编号/充电用户">
                    </span>
                    <span class="tableSpan">
                        订单状态: <select class="searchField" name="status">
                                <option value="">----</option>
                            <?php foreach ($status as $k => $v): ?>
                                <option value="<?= $k ?>"><?= $v ?></option>
                            <?php endforeach; ?>
                            </select>
                    </span>
                    <span class="tableSpan">
                        <button class="tableSearch">搜索</button>
                        <button class="tableReload">重置</button>
                    </span>
                </div>
                <div class="col-sm-1">
                    <button class="btn btn-sm btn-info export" data-go="/oam/order/export">导出</button>
                </div>
            </div>
            <table class="table table-striped table-bordered table-hover dataTable" id="table">
                <thead>
                <tr role="row">
                    <th>订单编号</th>
                    <th>电桩编号</th>
                    <th>充电枪口</th>
                    <th>充电电量</th>
                    <th>费用信息</th>
                    <th>用户信息</th>
                    <th>创建时间</th>
                    <th>订单状态</th>
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
        url: '/oam/order/data',
        length: 10,
        columns: [
            {"data": "no"},
            {"data": "pile"},
            {"data": "gun"},
            {"data": "e"},
            {"data": "info"},
            {"data": "userInfo"},
            {"data": "created_at"},
            {"data": "status"},
            {
                "data": "no", "orderable": false, "render": function (data, type, row) {
                if (row.status === '充电结束') {
                    return '<a href="/oam/order/deduct?no=' + data + '" class="btn btn-sm btn-primary">扣款</a>'
                }
                return '';
            }
            }
        ],
        default_order: [6, 'desc']
    });
    myTable.search();
    myTable.export();
</script>
