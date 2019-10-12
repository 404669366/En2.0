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
            </div>
            <table class="table table-striped table-bordered table-hover dataTable" id="table">
                <thead>
                <tr role="row">
                    <th>订单编号</th>
                    <th>电桩编号</th>
                    <th>充电枪口</th>
                    <th>充电用户</th>
                    <th>充电电量</th>
                    <th>基础电费</th>
                    <th>服务电费</th>
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
        url: '/finance/order/data',
        length: 10,
        columns: [
            {"data": "no"},
            {"data": "pile"},
            {"data": "gun"},
            {"data": "tel"},
            {"data": "e"},
            {"data": "bm"},
            {"data": "sm"},
            {"data": "created_at"},
            {"data": "status"},
            {
                "data": "no", "orderable": false, "render": function (data, type, row) {
                if (row.status === '充电结束') {
                    return '<a href="/finance/order/deduct?no=' + data + '" class="btn btn-sm btn-primary">扣款</a>'
                }
                return '';
            }
            }
        ],
        default_order: [7, 'desc']
    });
    myTable.search();
</script>