<div class="wrapper wrapper-content animated">
    <div class="ibox-content">
        <div class="dataTables_wrapper form-inline">
            <div class="row tableSearchBox">
                <div class="col-sm-11">
                    <span class="tableSpan">
                        综合搜索: <input class="searchField" type="text" value="" name="content"
                                     placeholder="意向编号/场站编号/意向用户">
                    </span>
                    <span class="tableSpan">
                        来源: <select class="searchField" name="source">
                                <option value="">----</option>
                            <?php foreach (\vendor\project\helpers\Constant::intentionSource() as $k => $type): ?>
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
            </div>
            <table class="table table-striped table-bordered table-hover dataTable" id="table">
                <thead>
                <tr role="row">
                    <th>NO</th>
                    <th>意向来源</th>
                    <th>场站编号</th>
                    <th>意向用户</th>
                    <th>认购金额</th>
                    <th>分成比例</th>
                    <th>创建时间</th>
                    <th>用户删除</th>
                    <th>意向状态</th>
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
        url: '/examine/intention/contract-data',
        length: 10,
        columns: [
            {"data": "no"},
            {"data": "source"},
            {"data": "field"},
            {"data": "user"},
            {"data": "amount"},
            {"data": "ratio"},
            {
                "data": "created_at", "render": function (data, type, row) {
                return timeToDate(data);
            }
            },
            {"data": "delete"},
            {"data": "status"},
            {
                "data": "id", "orderable": false, "render": function (data, type, row) {
                return '<a class="btn btn-sm btn-info" href="/examine/intention/contract-info?id=' + data + '">详情</a>';
            }
            }
        ],
        default_order: [0, 'desc']
    });
    myTable.search();
</script>