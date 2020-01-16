<div class="wrapper wrapper-content animated">
    <div class="ibox-content">
        <h3 style="text-align: center">砍价记录<a class="btn btn-white" href="/active/bargain/list" style="float: right">返回</a></h3>
        <div class="dataTables_wrapper form-inline">
            <table class="table table-striped table-bordered table-hover dataTable" id="table">
                <thead>
                <tr role="row">
                    <th>砍价用户</th>
                    <th>砍价金额</th>
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
        url: '/active/bargain/record-data?id=<?=$id?>',
        length: 10,
        columns: [
            {"data": "tel"},
            {"data": "price"},
            {"data": "created_at","render":function (data, type, row) {
                return timeToDate(data);
            }},
        ],
        default_order: [1, 'desc']
    });
</script>