<div class="wrapper wrapper-content animated">
    <div class="ibox-content">
        <div class="dataTables_wrapper form-inline">
            <div class="row tableSearchBox">
                <div class="col-sm-11">
                    <span class="tableSpan">
                        综合搜索: <input class="searchField" type="text" value="" name="name" placeholder="类型/品牌/功率">
                    </span>
                    <span class="tableSpan">
                        电桩类型: <select class="searchField" name="company">
                                <option value="">----</option>
                            <?php foreach (\vendor\project\helpers\Constant::pileType() as $k => $v): ?>
                                <option value="<?= $k ?>"><?= $v ?></option>
                            <?php endforeach; ?>
                            </select>
                    </span>
                    <span class="tableSpan">
                        电桩标准: <select class="searchField" name="company">
                                <option value="">----</option>
                            <?php foreach (\vendor\project\helpers\Constant::pileStandard() as $k => $v): ?>
                                <option value="<?= $k ?>"><?= $v ?></option>
                            <?php endforeach; ?>
                            </select>
                    </span>
                    <span class="tableSpan">
                        <button class="tableSearch">搜索</button>
                        <button class="tableReload">重置</button>
                    </span>
                </div>
                <div class="col-sm-1">
                    <a class="btn btn-sm btn-info" href="/pile/model/edit">添加</a>
                </div>
            </div>
            <table class="table table-striped table-bordered table-hover dataTable" id="table">
                <thead>
                <tr role="row">
                    <th>ID</th>
                    <th>电桩名称</th>
                    <th>电桩品牌</th>
                    <th>电桩功率</th>
                    <th>电桩电压</th>
                    <th>电桩电流</th>
                    <th>电桩类型</th>
                    <th>电桩标准</th>
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
        url: '/pile/model/data',
        length: 10,
        columns: [
            {"data": "id"},
            {
                "data": "name", "render": function (data, type, row) {
                return linFeed(data);
            }
            },
            {
                "data": "brand", "render": function (data, type, row) {
                return linFeed(data);
            }
            },
            {
                "data": "power", "render": function (data, type, row) {
                return linFeed(data);
            }
            },
            {
                "data": "voltage", "render": function (data, type, row) {
                return linFeed(data);
            }
            },
            {
                "data": "current", "render": function (data, type, row) {
                return linFeed(data);
            }
            },
            {"data": "type"},
            {"data": "standard"},
            {
                "data": "id", "orderable": false, "render": function (data, type, row) {
                return '<a class="btn btn-sm btn-warning" href="/pile/model/edit?id=' + data + '">修改</a>';
            }
            }
        ],
        default_order: [0, 'asc']
    });
    myTable.search();
</script>