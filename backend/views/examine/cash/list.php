<div class="wrapper wrapper-content animated">
    <div class="ibox-content">
        <div class="dataTables_wrapper form-inline">
            <div class="row tableSearchBox">
                <div class="col-sm-11">
                    <span class="tableSpan">
                        综合搜索: <input class="searchField" type="text" value="" name="content"
                                     placeholder="编号/企业/用户">
                    </span>
                    <span class="tableSpan">
                        提现类型: <select class="searchField" name="type">
                                <option value="">----</option>
                            <?php foreach ($types as $k => $type): ?>
                                <option value="<?= $k ?>"><?= $type ?></option>
                            <?php endforeach; ?>
                        </select>
                    </span>
                    <span class="tableSpan">
                        提现状态: <select class="searchField" name="status">
                                <option value="">----</option>
                            <?php foreach ($status as $k => $type): ?>
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
            </div>
            <table class="table table-striped table-bordered table-hover dataTable" id="table">
                <thead>
                <tr role="row">
                    <th>NO</th>
                    <th>提现类型</th>
                    <th>提现账户</th>
                    <th>提现金额</th>
                    <th>创建时间</th>
                    <th>提现状态</th>
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
        url: '/examine/cash/data',
        length: 10,
        columns: [
            {"data": "no"},
            {"data": "typeName"},
            {"data": "user"},
            {"data": "money"},
            {"data": "created_at"},
            {"data": "statusName"},
            {
                "data": "no", "orderable": false, "render": function (data, type, row) {
                return '<a class="btn btn-sm btn-info" href="/examine/cash/info?no=' + data + '">详情</a>';
            }
            }
        ],
        default_order: [4, 'desc']
    });
    myTable.search();
</script>