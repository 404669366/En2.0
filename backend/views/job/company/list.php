<div class="wrapper wrapper-content animated">
    <div class="ibox-content">
        <div class="dataTables_wrapper form-inline">
            <div class="row tableSearchBox">
                <div class="col-sm-11">
                    <span class="tableSpan">
                        综合搜索: <input class="searchField" type="text" value="" name="key" placeholder="名称/简称/法人/管理员">
                    </span>
                    <span class="tableSpan">
                        <button class="tableSearch">搜索</button>
                        <button class="tableReload">重置</button>
                    </span>
                </div>
                <div class="col-sm-1">
                    <a class="btn btn-sm btn-info" href="/job/company/edit">添加</a>
                </div>
            </div>
            <table class="table table-striped table-bordered table-hover dataTable" id="table">
                <thead>
                <tr role="row">
                    <th>ID</th>
                    <th>公司名称</th>
                    <th>公司简称</th>
                    <th>公司法人</th>
                    <th>管理员</th>
                    <th>拥有权限</th>
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
        url: '/job/company/data',
        length: 10,
        columns: [
            {"data": "id"},
            {
                "data": "name", "render": function (data, type, row) {
                return linFeed(data);
            }
            },
            {"data": "abridge"},
            {"data": "legal"},
            {"data": "admin"},
            {
                "data": "powers", "render": function (data, type, row) {
                return linFeed(data.join(','), 40);
            }
            },
            {
                "data": "id", "orderable": false, "render": function (data, type, row) {
                return '<a class="btn btn-sm btn-warning" href="/job/company/edit?id=' + data + '">修改</a>';
            }
            }
        ],
        default_order: [0, 'asc']
    });
    myTable.search();
</script>