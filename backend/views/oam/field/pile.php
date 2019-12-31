<div class="wrapper wrapper-content animated">
    <div class="ibox-content">
        <h3 style="text-align: center">电桩列表页<a class="btn btn-white" href="/oam/field/map" style="float: right">返回</a>
        </h3>
        <div class="dataTables_wrapper form-inline">
            <table class="table table-striped table-bordered table-hover dataTable" id="table">
                <thead>
                <tr role="row">
                    <th>NO</th>
                    <th>枪口数量</th>
                    <th>在线状态</th>
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
        url: '/oam/field/pile-data?no=<?=$no?>',
        length: 10,
        columns: [
            {"data": "no"},
            {"data": "count"},
            {"data": "online"},
            {"data": "model"},
            {"data": "fieldInfo"},
            {
                "data": "no", "orderable": false, "render": function (data, type, row) {
                return '<a class="btn btn-sm btn-info" href="/oam/field/pile-info?no=' + data + '">详情</a>';
            }
            }
        ],
        default_order: [0, 'desc']
    });
</script>