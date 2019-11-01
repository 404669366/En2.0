<?php $this->registerJsFile('@web/js/modal.js', ['depends' => ['app\assets\ModelAsset']]) ?>
<div class="wrapper wrapper-content animated">
    <div class="ibox-content">
        <div class="dataTables_wrapper form-inline">
            <div class="row tableSearchBox">
                <div class="col-sm-10">
                    <span class="tableSpan">
                        综合搜索: <input class="searchField" type="text" value="" name="content"
                                     placeholder="意向编号/场站编号/场站名称/意向用户">
                    </span>
                    <span class="tableSpan">
                        意向状态: <select class="searchField" name="status">
                                <option value="">----</option>
                            <?php foreach ($status as $k => $type): ?>
                                <option value="<?= $k ?>"><?= $type ?></option>
                            <?php endforeach; ?>
                        </select>
                    </span>
                    <span class="tableSpan">
                        场站状态: <select class="searchField" name="fStatus">
                                <option value="">----</option>
                            <?php foreach ($fStatus as $k => $type): ?>
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
                    <th>场站信息</th>
                    <th>融资信息</th>
                    <th>意向用户</th>
                    <th>股权数量</th>
                    <th>意向金额</th>
                    <th>意向状态</th>
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
        url: '/field/intention/data',
        length: 10,
        columns: [
            {"data": "no"},
            {"data": "fieldInfo"},
            {"data": "stock"},
            {"data": "tel"},
            {"data": "num"},
            {"data": "amount"},
            {"data": "statusName"},
            {"data": "created_at"},
        ],
        default_order: [7, 'desc']
    });
    myTable.search();
</script>