<div class="wrapper wrapper-content animated">
    <div class="ibox-content">
        <div class="dataTables_wrapper form-inline">
            <div class="row tableSearchBox">
                <div class="col-sm-10">
                    <span class="tableSpan">
                        综合搜索: <input class="searchField" type="text" value="" name="content"
                                     placeholder="地址/用户/推荐用户/转化场站">
                    </span>
                    <span class="tableSpan">
                        状态: <select class="searchField" name="status">
                                <option value="">----</option>
                            <?php foreach (\vendor\project\helpers\Constant::basefieldStatus() as $k => $type): ?>
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
                <div class="col-sm-2">
                    <a class="btn btn-sm btn-primary" href="/field/base/rob">抢单 ( <?= $count ?> )</a>
                </div>
            </div>
            <table class="table table-striped table-bordered table-hover dataTable" id="table">
                <thead>
                <tr role="row">
                    <th>ID</th>
                    <th>用户</th>
                    <th>推荐用户</th>
                    <th>场站地址</th>
                    <th>转化场站</th>
                    <th>场站状态</th>
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
        url: '/field/base/data',
        length: 10,
        columns: [
            {"data": "id"},
            {
                "data": "uTel", "render": function (data, type, row) {
                return data || '----';
            }
            },
            {
                "data": "cTel", "render": function (data, type, row) {
                return data || '----';
            }
            },
            {
                "data": "address", "render": function (data, type, row) {
                return linFeed(data, 30);
            }
            },
            {
                "data": "no", "render": function (data, type, row) {
                return data || '----';
            }
            },
            {"data": "status"},
            {
                "data": "created_at", "render": function (data, type, row) {
                return timeToDate(data);
            }
            },
            {
                "data": "id", "orderable": false, "render": function (data, type, row) {
                var str = '<a class="btn btn-sm btn-info" href="/field/base/info?id=' + data + '">详情</a>';
                if (row.status === '待转化') {
                    str += '&emsp;<a class="btn btn-sm btn-danger" href="/field/base/renounce?id=' + data + '">放弃</a>';
                }
                return str;
            }
            }
        ],
        default_order: [0, 'desc']
    });
    myTable.search();
</script>