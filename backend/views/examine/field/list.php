<?php $this->registerJsFile('@web/js/modal.js', ['depends' => ['app\assets\ModelAsset']]) ?>
<div class="wrapper wrapper-content animated">
    <div class="ibox-content">
        <div class="dataTables_wrapper form-inline">
            <div class="row tableSearchBox">
                <div class="col-sm-10">
                    <span class="tableSpan">
                        综合搜索: <input class="searchField" type="text" value="" name="content"
                                     placeholder="编号/名称/标题/地址/用户" style="width: 18rem">
                    </span>
                    <span class="tableSpan">
                        场站来源: <select class="searchField" name="source">
                                <option value="">----</option>
                            <?php foreach (\vendor\project\helpers\Constant::fieldSource() as $k => $type): ?>
                                <option value="<?= $k ?>"><?= $type ?></option>
                            <?php endforeach; ?>
                        </select>
                    </span>
                    <span class="tableSpan">
                        业务类型: <select class="searchField" name="business_type">
                                <option value="">----</option>
                            <?php foreach (\vendor\project\helpers\Constant::businessType() as $k => $type): ?>
                                <option value="<?= $k ?>"><?= $type ?></option>
                            <?php endforeach; ?>
                        </select>
                    </span>
                    <span class="tableSpan">
                        投资类型: <select class="searchField" name="invest_type">
                                <option value="">----</option>
                            <?php foreach (\vendor\project\helpers\Constant::investType() as $k => $type): ?>
                                <option value="<?= $k ?>"><?= $type ?></option>
                            <?php endforeach; ?>
                        </select>
                    </span>
                    <span class="tableSpan">
                        状态: <select class="searchField" name="status">
                                <option value="">----</option>
                            <?php foreach (\vendor\project\helpers\Constant::fieldStatus() as $k => $type): ?>
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
                    <th>场站来源</th>
                    <th>场站名称</th>
                    <th>场站标题</th>
                    <th>场站位置</th>
                    <th>业务类型</th>
                    <th>投资类型</th>
                    <th>场地用户</th>
                    <th>分成比例</th>
                    <th>融资情况</th>
                    <th>场站状态</th>
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
        url: '/examine/field/data',
        length: 10,
        columns: [
            {"data": "no"},
            {"data": "source"},
            {
                "data": "name", "render": function (data, type, row) {
                return linFeed(data, 15);
            }
            },
            {
                "data": "title", "render": function (data, type, row) {
                return linFeed(data, 15);
            }
            },
            {
                "data": "address", "render": function (data, type, row) {
                return linFeed(data, 15);
            }
            },
            {"data": "business_type"},
            {"data": "invest_type"},
            {
                "data": "local", "render": function (data, type, row) {
                return data || '----';
            }
            },
            {"data": "ratio"},
            {"data": "info"},
            {"data": "status"},
            {
                "data": "created_at", "render": function (data, type, row) {
                return timeToDate(data);
            }
            },
            {
                "data": "id", "orderable": false, "render": function (data, type, row) {
                return '<a class="btn btn-sm btn-info" href="/examine/field/info?id=' + data + '">详情</a>';
            }
            }
        ],
        default_order: [0, 'desc']
    });
    myTable.search();
</script>