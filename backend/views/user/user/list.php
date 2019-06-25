<div class="wrapper wrapper-content animated">
    <div class="ibox-content">
        <div class="dataTables_wrapper form-inline">
            <div class="row tableSearchBox">
                <div class="col-sm-10">
                    <span class="tableSpan">
                        手机号: <input class="searchField" type="text" value="" name="tel">
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
                    <th>ID</th>
                    <th>手机号</th>
                    <th>账号余额</th>
                    <th>账号积分</th>
                    <th>创建时间</th>
                </tr>
                </thead>
            </table>
        </div>
    </div>
</div>
<script>
    myTable.load({
        table: '#table',
        url: '/user/user/data',
        length: 10,
        columns: [
            {"data": "id"},
            {"data": "tel"},
            {
                "data": "money", "render": function (data, type, row) {
                return data ? data: 0;
            }
            },
            {
                "data": "points", "render": function (data, type, row) {
                return data ? data: 0;
            }
            },
            {
                "data": "created_at", "render": function (data, type, row) {
                return timeToDate(data);
            }
            }
        ],
        default_order: [0, 'desc']
    });
    myTable.search();
</script>