<div class="wrapper wrapper-content animated">
    <div class="ibox-content">
        <div class="dataTables_wrapper form-inline">
            <div class="row tableSearchBox">
                <div class="col-sm-11">
                    <span class="tableSpan">
                        综合搜索: <input class="searchField" type="text" value="" name="keywords"
                                     placeholder="充值编号/充值用户">
                    </span>
                    <span class="tableSpan">
                        充值状态: <select class="searchField" name="status">
                                <option value="">----</option>
                            <?php foreach ($status as $k => $v): ?>
                                <option value="<?= $k ?>"><?= $v ?></option>
                            <?php endforeach; ?>
                            </select>
                    </span>
                    <span class="tableSpan">
                        充值来源: <select class="searchField" name="source">
                                <option value="">----</option>
                            <?php foreach ($source as $k => $v): ?>
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
                    <th>充值编号</th>
                    <th>充值用户</th>
                    <th>充值金额</th>
                    <th>当前余额</th>
                    <th>充值来源</th>
                    <th>创建时间</th>
                    <th>充值状态</th>
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
        url: '/finance/invest/data',
        length: 10,
        columns: [
            {"data": "no"},
            {"data": "tel"},
            {"data": "money"},
            {"data": "balance"},
            {"data": "source"},
            {"data": "created_at"},
            {"data": "status"},
            {
                "data": "no", "orderable": false, "render": function (data, type, row) {
                return '';
            }
            }
        ],
        default_order: [5, 'desc']
    });
    myTable.search();
</script>