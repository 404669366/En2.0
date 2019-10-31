<div class="wrapper wrapper-content animated">
    <div class="ibox-content">
        <form method="post" class="form-horizontal">
            <div class="hr-line-dashed"></div>
            <div class="form-group">
                <label class="col-sm-2 control-label">提现状态</label>
                <div class="col-sm-8">
                    <input type="text" class="form-control" placeholder="<?= $status[$model->status] ?>" readonly>
                </div>
            </div>
            <div class="hr-line-dashed"></div>
            <div class="form-group">
                <label class="col-sm-2 control-label">提现编号</label>
                <div class="col-sm-8">
                    <input type="text" class="form-control" placeholder="<?= $model->no ?>" readonly>
                </div>
            </div>
            <div class="hr-line-dashed"></div>
            <div class="form-group">
                <label class="col-sm-2 control-label">提现类型</label>
                <div class="col-sm-8">
                    <input type="text" class="form-control" placeholder="<?= $types[$model->type] ?>" readonly>
                </div>
            </div>
            <div class="hr-line-dashed"></div>
            <div class="form-group">
                <label class="col-sm-2 control-label">申请用户</label>
                <div class="col-sm-8">
                    <input type="text" class="form-control"
                           placeholder="<?= $model->type == 1 ? $model->company->name : $model->user->tel ?>" readonly>
                </div>
            </div>
            <div class="hr-line-dashed"></div>
            <div class="form-group">
                <label class="col-sm-2 control-label">提现金额</label>
                <div class="col-sm-8">
                    <input type="text" class="form-control" placeholder="<?= $model->money ?>" readonly>
                </div>
            </div>
            <div class="hr-line-dashed"></div>
            <div class="form-group">
                <label class="col-sm-2 control-label">备注</label>
                <div class="col-sm-8">
                    <textarea class="form-control" rows="10"
                        <?= $model->status == 0 ? 'name="remark"' : 'readonly' ?>><?= $model->remark ?></textarea>
                </div>
            </div>
            <div class="hr-line-dashed"></div>
            <div class="form-group">
                <div class="col-sm-5 col-sm-offset-2">
                    <?php if ($model->status == 0): ?>
                        <input type="hidden" name="_csrf" value="<?= Yii::$app->request->csrfToken ?>">
                        <input type="hidden" name="status" value="">
                        <button type="submit" class="btn btn-info pass" data-st="1">通过</button>
                        &emsp;
                        <button type="submit" class="btn btn-info pass" data-st="3">驳回</button>
                        &emsp;
                    <?php endif; ?>
                    <button class="btn btn-white back">返回</button>
                </div>
            </div>
        </form>
    </div>
</div>
<script>
    $('.pass').click(function () {
        $('[name="status"]').val($(this).data('st'));
        if ($(this).data('st') === 3 && !$('[name="remark"]').val()) {
            showMsg('请填写备注');
            return false;
        }
        return true;
    });
</script>
