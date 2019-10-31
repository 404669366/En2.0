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
                    <span>
                    <span class="tableSpan">
                        <button class="tableSearch">搜索</button>
                        <button class="tableReload">重置</button>
                    </span>
                </div>
            </div>
            <table class="table table-striped table-bordered table-hover dataTable" id="table">
                <thead>
                <tr role="row">
                    <th>NO/创建时间</th>
                    <th>归属企业</th>
                    <th>归属专员</th>
                    <th>归属用户</th>
                    <th>场站信息</th>
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
        url: '/examine/field/data',
        length: 10,
        columns: [
            {"data": "info"},
            {"data": "cName"},
            {"data": "cTel"},
            {"data": "uTel"},
            {"data": "data"},
            {"data": "stock"},
            {"data": "statusInfo"},
            {
                "data": "no", "orderable": false, "render": function (data, type, row) {
                var str = '<a class="btn btn-sm btn-info" href="/examine/field/info?no=' + data + '">详情</a>';
                if (row.status == 2) {
                    str = '<a class="btn btn-sm btn-warning" href="/examine/field/info?no=' + data + '">审核</a>';
                }
                return str;
            }
            }
        ],
        default_order: [7, 'asc']
    });
    myTable.search();
</script>