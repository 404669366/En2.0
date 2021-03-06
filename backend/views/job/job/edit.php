<?php $this->registerCssFile('@web/css/selectTree.css', ['depends' => ['app\assets\ModelAsset']]) ?>
<?php $this->registerJsFile('@web/js/selectTree.js', ['depends' => ['app\assets\ModelAsset']]) ?>
<div class="wrapper wrapper-content animated">
    <div class="ibox-content">
        <form method="post" class="form-horizontal">
            <input type="hidden" name="_csrf" value="<?= Yii::$app->request->csrfToken ?>">
            <div class="hr-line-dashed"></div>
            <div class="form-group">
                <label class="col-sm-2 control-label">职位名称</label>
                <div class="col-sm-8">
                    <input type="text" class="form-control" name="name" value="<?= $model->name ?>">
                </div>
            </div>
            <div class="hr-line-dashed"></div>
            <div class="form-group">
                <label class="col-sm-2 control-label">归属公司</label>
                <div class="col-sm-8">
                    <select class="form-control" name="company_id">
                        <option value="">--请选择--</option>
                        <?php foreach ($company as $v): ?>
                            <option <?= $model->company_id == $v['id'] ? 'selected' : '' ?>
                                    value="<?= $v['id'] ?>"><?= $v['name'] ?></option>
                        <?php endforeach; ?>
                    </select>
                    <small style="color:red;">*修改职位归属公司,拥有该职位的员工也会修改成相应的公司</small>
                </div>
            </div>
            <div class="hr-line-dashed"></div>
            <div class="form-group">
                <label class="col-sm-2 control-label">拥有权限</label>
                <div class="col-sm-8">
                    <input type="hidden" name="powers" value="<?= $model->powers ?>">
                    <div class="powersTree"></div>
                </div>
                <script>
                    var powersData = JSON.parse('<?=$powers?>');
                    selectTree.tree({
                        type: 1,
                        wrapper: '.powersTree',
                        data: powersData,
                        check: $('[name="powers"]').val().split(',')
                    });
                    $('[name="company_id"]').on('change', function () {
                        $.getJSON('/job/job/get-powers', {company_id: $(this).val()}, function (re) {
                            if (re.type) {
                                $('.powersTree').html('');
                                selectTree.tree({
                                    type: 1,
                                    wrapper: '.powersTree',
                                    data: re.data,
                                    check: $('[name="powers"]').val().split(',')
                                });
                            }
                        });
                    })
                </script>
            </div>
            <div class="hr-line-dashed"></div>
            <div class="form-group">
                <label class="col-sm-2 control-label">备注</label>
                <div class="col-sm-8">
                    <textarea class="form-control" name="remark" rows="10"><?= $model->remark ?></textarea>
                </div>
            </div>
            <div class="hr-line-dashed"></div>
            <div class="form-group">
                <div class="col-sm-4 col-sm-offset-2">
                    <button class="btn btn-primary sub" type="submit">保存内容</button>
                    <script>
                        $('.sub').click(function () {
                            $('[name="powers"]').val(selectTree.treeJson('.powersTree').join(','));
                        })
                    </script>
                    <button class="btn btn-white back">返回</button>
                </div>
            </div>
        </form>
    </div>
</div>