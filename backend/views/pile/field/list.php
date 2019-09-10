<div class="wrapper wrapper-content animated">
    <div class="ibox-content">
        <div class="dataTables_wrapper form-inline">
            <div class="row tableSearchBox">
                <div class="col-sm-11">
                    <span class="tableSpan">
                        综合搜索: <input class="searchField" type="text" value="" name="content" placeholder="编号/名称/地址/用户">
                    </span>
                    <span class="tableSpan">
                        上线状态: <select class="searchField" name="online">
                                <option value="">----</option>
                            <?php foreach (\vendor\project\helpers\Constant::fieldOnline() as $k => $v): ?>
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
                    <th>场站名称</th>
                    <th>场站地址</th>
                    <th>场站业主</th>
                    <th>上线状态</th>
                    <th>操作</th>
                </tr>
                </thead>
            </table>
        </div>
    </div>
</div>
<div class="wait" style="display: none">
    <div style="position: fixed;left: 0;top: 0;width: 100%;height: 100%;z-index: 999;background: rgba(0,0,0,0.06);display: table">
        <span style="display: table-cell;vertical-align: middle;text-align: center">
            <img src="/img/loading.gif" style="width: 3rem;height: 3rem">
        </span>
    </div>
</div>
<script>
    myTable.load({
        table: '#table',
        url: '/pile/field/data',
        length: 10,
        columns: [
            {"data": "no"},
            {
                "data": "name", "render": function (data, type, row) {
                return linFeed(data);
            }
            },
            {
                "data": "address", "render": function (data, type, row) {
                return linFeed(data);
            }
            },
            {
                "data": "local", "render": function (data, type, row) {
                return data || '----';
            }
            },
            {"data": "online"},
            {
                "data": "no", "orderable": false, "render": function (data, type, row) {
                var btn = '';
                if (row.online === '未上线') {
                    btn = '<button class="btn btn-sm btn-info do" data-url="/pile/field/up?no=' + data + '">上线</button>';
                } else {
                    btn = '<button class="btn btn-sm btn-warning do" data-url="/pile/field/down?no=' + data + '">下线</button>'
                }
                return btn;
            }
            }
        ],
        default_order: [0, 'desc']
    });
    myTable.search();

    $('#table').on('click', '.do', function () {
        $('.wait').show();
        window.location.href = $(this).data('url');
    });
</script>