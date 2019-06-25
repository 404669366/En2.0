<div class="wrapper wrapper-content animated">
    <div class="ibox-content">
        <div class="dataTables_wrapper form-inline">
            <div class="row tableSearchBox">
                <div class="col-sm-10">
                    <span class="tableSpan">
                        用户名: <input class="searchField" type="text" value="" name="username">
                    </span>
                    <span class="tableSpan">
                        手机号: <input class="searchField" type="text" value="" name="tel">
                    </span>
                    <span class="tableSpan">
                        职位: <select class="searchField" name="job">
                                <option value="">----</option>
                            <?php foreach ($jobs as $job): ?>
                                <option value="<?= $job['id'] ?>"><?= $job['job'] ?></option>
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
                    <a class="btn btn-sm btn-info" href="/member/member/add">添加</a>
                </div>
            </div>
            <table class="table table-striped table-bordered table-hover dataTable" id="table">
                <thead>
                <tr role="row">
                    <th>ID</th>
                    <th>职位</th>
                    <th>用户名</th>
                    <th>手机号</th>
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
        url: '/member/member/data',
        length: 10,
        columns: [
            {"data": "id"},
            {"data": "job"},
            {"data": "username"},
            {"data": "tel"},
            {
                "data": "created_at", "render": function (data, type, row) {
                return timeToDate(data);
            }
            },
            {
                "data": "id", "orderable": false, "render": function (data, type, row) {
                return '<a class="btn btn-sm btn-warning" href="/member/member/edit?id=' + data + '">修改</a>';
            }
            }
        ],
        default_order: [0, 'asc']
    });
    myTable.search();
</script>