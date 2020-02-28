<?php $this->registerJsFile('@web/js/modal.js', ['depends' => ['app\assets\ModelAsset']]) ?>
<div class="wrapper wrapper-content animated">
    <div class="ibox-content">
        <div class="dataTables_wrapper form-inline">
            <div class="row tableSearchBox">
                <div class="col-sm-12">
                    <span class="tableSpan">
                        综合搜索: <input class="searchField" type="text" value="" name="key"
                                     placeholder="编号/名称/标题/地址/企业" style="width: 18rem">
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
                    <th>NO/创建时间</th>
                    <th>场站信息</th>
                    <th>场站状态</th>
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
        url: '/finance/consume/field-data',
        length: 10,
        columns: [
            {"data": "created_at"},
            {"data": "data"},
            {"data": "statusInfo"},
            {
                "data": "no", "orderable": false, "render": function (data, type, row) {
               return '<a class="btn btn-sm btn-info" href="/finance/consume/field-report?no=' + data + '">详情</a>';
            }
            }
        ],
        default_order: [0, 'desc']
    });
    myTable.search();
</script>