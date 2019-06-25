<div class="wrapper wrapper-content animated">
    <div class="ibox-content">
        <div class="dataTables_wrapper form-inline">
            <div class="row tableSearchBox">
                <div class="col-sm-11">
                    <span class="tableSpan">
                        综合搜索: <input class="searchField" type="text" value="" name="content"
                                     placeholder="场站编号/用户/推荐用户">
                    </span>
                    <span class="tableSpan">
                        来源: <select class="searchField" name="source">
                                <option value="">----</option>
                            <?php foreach (\vendor\project\helpers\Constant::intentionSource() as $k => $type): ?>
                                <option value="<?= $k ?>"><?= $type ?></option>
                            <?php endforeach; ?>
                        </select>
                    </span>
                    <span class="tableSpan">
                        状态: <select class="searchField" name="status">
                                <option value="">----</option>
                            <?php foreach (\vendor\project\helpers\Constant::intentionStatus() as $k => $type): ?>
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
                <div class="col-sm-1">
                    <a class="btn btn-sm btn-primary" href="/field/intention/add">添加</a>
                </div>
            </div>
            <table class="table table-striped table-bordered table-hover dataTable" id="table">
                <thead>
                <tr role="row">
                    <th>ID</th>
                    <th>意向来源</th>
                    <th>场站编号</th>
                    <th>意向用户</th>
                    <th>推荐用户</th>
                    <th>认购金额</th>
                    <th>定金金额</th>
                    <th>分成比例</th>
                    <th>意向状态</th>
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
        url: '/field/intention/data',
        length: 10,
        columns: [
            {"data": "id"},
            {"data": "source"},
            {"data": "no"},
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
            {"data": "purchase_amount"},
            {"data": "order_amount"},
            {"data": "part_ratio"},
            {"data": "status"},
            {
                "data": "created_at", "render": function (data, type, row) {
                return timeToDate(data);
            }
            },
            {
                "data": "id", "orderable": false, "render": function (data, type, row) {
                var str = '';
                if (row.status === '待付定金' || row.status === '审核中' || row.status === '审核通过' || row.status === '用户违约') {
                    str += '<a class="btn btn-sm btn-info" href="/field/intention/info?id=' + data + '">详情</a>';
                }
                if (row.status === '已付定金' || row.status === '审核不通过') {
                    str += '&emsp;<a class="btn btn-sm btn-warning" href="/field/intention/edit?id=' + data + '">编辑</a>';
                    if (row.source === '用户') {
                        str += '&emsp;<a class="btn btn-sm btn-danger" href="/field/intention/break?id=' + data + '">违约</a>';
                    }
                }
                return str;
            }
            }
        ],
        default_order: [0, 'desc']
    });
    myTable.search();
</script>