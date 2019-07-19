<div class="wrapper wrapper-content animated">
    <div class="ibox-content">
        <div class="dataTables_wrapper form-inline">
            <div class="row tableSearchBox">
                <div class="col-sm-10">
                    <span class="tableSpan">
                        手机号: <input class="searchField" type="text" value="" name="tel">
                    </span>
                    <span class="tableSpan">
                        归属公司: <select class="searchField" name="company">
                                <option value="">----</option>
                            <?php foreach ($company as $v): ?>
                                <option value="<?= $v['id'] ?>"><?= $v['name'] ?></option>
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
                    <a class="btn btn-sm btn-info" href="/job/member/edit">添加</a>
                </div>
            </div>
            <table class="table table-striped table-bordered table-hover dataTable" id="table">
                <thead>
                <tr role="row">
                    <th>ID</th>
                    <th>手机号</th>
                    <th>归属公司</th>
                    <th>职位</th>
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
        url: '/job/member/data',
        length: 10,
        columns: [
            {"data": "id"},
            {"data": "tel"},
            {"data": "company"},
            {"data": "job"},
            {
                "data": "id", "orderable": false, "render": function (data, type, row) {
                return '<a class="btn btn-sm btn-warning" href="/job/member/edit?id=' + data + '">修改</a>';
            }
            }
        ],
        default_order: [2, 'asc']
    });
    myTable.search();
</script>