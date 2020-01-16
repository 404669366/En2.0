<div class="wrapper wrapper-content animated">
    <div class="ibox-content">
        <div class="dataTables_wrapper form-inline">
            <div class="row tableSearchBox">
                <div class="col-sm-10">
                    <span class="tableSpan">
                        综合搜索: <input class="searchField" type="text" value="" name="keywords"
                                     placeholder="用户/订单编号">
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
                    <th>用户</th>
                    <th>砍价金额</th>
                    <th>最少刀数</th>
                    <th>当前砍价</th>
                    <th>当前刀数</th>
                    <th>创建时间</th>
                    <th>截止时间</th>
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
        url: '/active/bargain/data',
        length: 10,
        columns: [
            {"data": "no"},
            {"data": "tel"},
            {"data": "price"},
            {"data": "count"},
            {"data": "nowPrice"},
            {"data": "nowCount"},
            {"data": "created_at"},
            {"data": "end_at"},
            {
                "data": "id", "orderable": false, "render": function (data, type, row) {
                return '<a class="btn btn-sm btn-info" href="/active/bargain/record?id=' + data + '">砍价记录</a>';
            }
            },
        ],
        default_order: [6, 'desc']
    });
    myTable.search();
</script>