<div class="wrapper wrapper-content animated">
    <div class="ibox-content">
        <div class="dataTables_wrapper form-inline">
            <div class="row tableSearchBox">
                <div class="col-sm-10">
                    <span class="tableSpan">
                        综合搜索: <input class="searchField" type="text" value="" name="keywords"
                                     placeholder="提现编号">
                    </span>
                    <span class="tableSpan">
                        提现状态: <select class="searchField" name="status">
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
                <div class="col-sm-1" style="line-height: 1.5;padding: 5px 0;text-align: center">
                    可提收益: <span style="color: #fa5741">&yen;<?= $surplus ?></span>
                </div>
                <div class="col-sm-1">
                    <a class="btn btn-sm btn-primary" href="/finance/cash/edit">申请提现</a>
                </div>
            </div>
            <table class="table table-striped table-bordered table-hover dataTable" id="table">
                <thead>
                <tr role="row">
                    <th>提现编号</th>
                    <th>提现金额</th>
                    <th>审核状态</th>
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
        url: '/finance/cash/data',
        length: 10,
        columns: [
            {"data": "no"},
            {"data": "money"},
            {"data": "statusName"},
            {"data": "created_at"},
            {
                "data": "no", "orderable": false, "render": function (data, type, row) {
                return '<a class="btn btn-sm btn-info" href="/finance/cash/edit?no=' + data + '">详情</a>';
            }
            },
        ],
        default_order: [3, 'desc']
    });
    myTable.search();
</script>