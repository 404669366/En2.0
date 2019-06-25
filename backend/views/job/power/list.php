<div class="wrapper wrapper-content animated">
    <div class="ibox-content">
        <div class="dataTables_wrapper form-inline">
            <div class="row tableSearchBox">
                <div class="col-sm-10">
                    <span class="tableSpan">
                        权限名称: <input class="searchField" type="text" value="" name="name">
                    </span>
                    <span class="tableSpan">
                        上级权限: <input class="searchField" type="text" value="" name="last">
                    </span>
                    <span class="tableSpan">
                        权限类型: <select class="searchField" name="type">
                                <option value="">----</option>
                            <?php foreach ($types as $k => $type): ?>
                                <option value="<?= $k ?>"><?= $type ?></option>
                            <?php endforeach; ?>
                            </select>
                    </span>
                    <span class="tableSpan">
                        <button class="tableSearch">搜索</button>
                        <button class="tableReload">重置</button>
                    </span>
                </div>
                <div class="col-sm-2">
                    <a class="btn btn-sm btn-info" href="/job/power/add">添加</a>
                </div>
            </div>
            <table class="table table-striped table-bordered table-hover dataTable" id="table">
                <thead>
                <tr role="row">
                    <th>ID</th>
                    <th>权限名</th>
                    <th>权限类型</th>
                    <th>权限路由</th>
                    <th>上级权限</th>
                    <th>排序</th>
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
        url: '/job/power/data',
        length: 10,
        columns: [
            {"data": "id"},
            {"data": "name"},
            {"data": "type"},
            {"data": "url"},
            {
                "data": "lastName", "render": function (data, type, row) {
                return data ? data : '顶级权限';
            }
            },
            {"data": "sort"},
            {
                "data": "id", "orderable": false, "render": function (data, type, row) {
                var str = '<a class="btn btn-sm btn-warning" href="/job/power/edit?id=' + data + '">修改</a>&emsp;';
                str += '<a class="btn btn-sm btn-danger" href="/job/power/del?id=' + data + '">删除</a>';
                return str;
            }
            }
        ],
        default_order: [3, 'asc']
    });
    myTable.search();
</script>