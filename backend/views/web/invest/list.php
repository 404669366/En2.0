<div class="wrapper wrapper-content animated">
    <div class="ibox-content">
        <table class="table table-striped table-bordered table-hover dataTable">
            <thead>
            <tr role="row">
                <th>ID</th>
                <th>类型名称</th>
                <th>操作</th>
            </tr>
            </thead>
            <tbody>
            <?php foreach ($data as $k => $v): ?>
                <tr role="row">
                    <th><?= $k ?></th>
                    <th><?= $v ?></th>
                    <th><a class="btn btn-sm btn-info" href="/web/invest/edit?k=<?= $k ?>">编辑</a></th>
                </tr>
            <?php endforeach; ?>
            </tbody>
        </table>
    </div>
</div>
</div>