<div class="wrapper wrapper-content animated">
    <div class="ibox-content">
        <div class="dataTables_wrapper form-inline">
            <div class="row tableSearchBox">
                <div class="col-sm-11">
                    <span class="tableSpan">
                        综合搜索: <input class="searchField" type="text" value="" name="keywords"
                                     placeholder="场站/电桩/订单">
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
                    <th>订单编号</th>
                    <th>场站信息</th>
                    <th>股权信息</th>
                    <th>消费信息</th>
                    <th>结算金额</th>
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
        url: '/finance/income/data',
        length: 10,
        columns: [
            {"data": "order"},
            {"data": "info2"},
            {"data": "info3"},
            {"data": "info1"},
            {"data": "money"},
            {"data": "created_at"},
        ],
        default_order: [5, 'desc']
    });
    myTable.search();
</script>