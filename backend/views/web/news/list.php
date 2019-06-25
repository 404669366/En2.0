<div class="wrapper wrapper-content animated">
    <div class="ibox-content">
        <div class="dataTables_wrapper form-inline">
            <div class="row tableSearchBox">
                <div class="col-sm-11">
                    <span class="tableSpan">
                        标题:
                        <input class="searchField" type="text" value="" name="title">
                    </span>
                    <span class="tableSpan">
                        来源:
                        <select class="searchField" name="source">
                            <option value="">----</option>
                            <?php foreach (\vendor\project\helpers\Constant::newsSource() as $k => $v): ?>
                                <option value="<?= $k ?>"><?= $v['name'] ?></option>
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
                    <a class="btn btn-sm btn-primary" href="/web/news/do">发布新闻</a>
                </div>
            </div>
            <table class="table table-striped table-bordered table-hover dataTable" id="table">
                <thead>
                <tr role="row">
                    <th>ID</th>
                    <th>标题</th>
                    <th>来源</th>
                    <th>URL</th>
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
        url: '/web/news/data',
        length: 10,
        columns: [
            {"data": "id"},
            {
                "data": "title",
                "render": function (data, type, row) {
                    return linFeed(data);
                }
            },
            {"data": "source"},
            {
                "data": "url",
                "render": function (data, type, row) {
                    return data || '--------';
                }
            },
            {
                "data": "created_at",
                "render": function (data, type, row) {
                    return timeToDate(data);
                }
            },
            {
                "data": "id",
                "render": function (data, type, row) {
                    var str = '<a class="btn btn-sm btn-info" href="/web/news/do?id=' + data + '">编辑</a>';
                    str += '&emsp;<a class="btn btn-sm btn-danger" href="/web/news/del?id=' + data + '">删除</a>';
                    return str;
                }
            }
        ],
        default_order: [0, 'desc']
    });
    myTable.search();
</script>