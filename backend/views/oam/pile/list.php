<div class="wrapper wrapper-content animated">
    <div class="ibox-content">
        <div class="dataTables_wrapper form-inline">
            <div class="row tableSearchBox">
                <div class="col-sm-11">
                    <span class="tableSpan">
                        综合搜索: <input class="searchField" type="text" value="" name="keywords"
                                     placeholder="编号/名称/地址/业主/类型">
                    </span>
                    <span class="tableSpan">
                        在线状态: <select class="searchField" name="online">
                                <option value="">----</option>
                            <?php foreach ($online as $k => $v): ?>
                                <option value="<?= $k ?>"><?= $v ?></option>
                            <?php endforeach; ?>
                            </select>
                    </span>
                    <span class="tableSpan">
                        绑定状态: <select class="searchField" name="bind">
                                <option value="">----</option>
                            <?php foreach ($bind as $k => $v): ?>
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
                    <th>NO</th>
                    <th>枪口数量</th>
                    <th>在线状态</th>
                    <th>绑定状态</th>
                    <th>电桩类型</th>
                    <th>场站信息</th>
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
        url: '/oam/pile/data',
        length: 10,
        columns: [
            {"data": "no"},
            {"data": "count"},
            {"data": "online"},
            {"data": "bind"},
            {"data": "model"},
            {"data": "fieldInfo"},
            {
                "data": "no", "orderable": false, "render": function (data, type, row) {
                return '<button class="btn btn-sm btn-info" data-url="/oam/pile/info?no=' + data + '">编辑</button>';
            }
            }
        ],
        default_order: [0, 'desc']
    });
    myTable.search();
</script>