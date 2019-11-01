<style>
    table {
        width: 100%;
        line-height: 3rem;
        font-size: 1.4rem;
        text-align: center;
        border-color: silver;
        margin-bottom: 1rem;
    }
</style>
<div class="wrapper wrapper-content animated">
    <div class="ibox-content">
        <form method="post" class="form-horizontal">
            <div class="hr-line-dashed"></div>
            <div class="form-group">
                <label class="col-sm-2 control-label">意向编号</label>
                <div class="col-sm-8">
                    <input type="text" class="form-control" readonly placeholder="<?= $model->no ?>">
                </div>
            </div>
            <div class="hr-line-dashed"></div>
            <div class="form-group">
                <label class="col-sm-2 control-label">场站编号</label>
                <div class="col-sm-8">
                    <input type="text" class="form-control" readonly placeholder="<?= $model->field ?>">
                </div>
            </div>
            <div class="hr-line-dashed"></div>
            <div class="form-group">
                <label class="col-sm-2 control-label">意向用户</label>
                <div class="col-sm-8">
                    <input type="text" class="form-control" readonly placeholder="<?= $model->user->tel ?>">
                </div>
            </div>
            <div class="hr-line-dashed"></div>
            <div class="form-group">
                <label class="col-sm-2 control-label">股权数量</label>
                <div class="col-sm-8">
                    <input type="text" class="form-control" readonly placeholder="<?= $model->num ?>">
                </div>
            </div>
            <div class="hr-line-dashed"></div>
            <div class="form-group">
                <label class="col-sm-2 control-label">意向金额</label>
                <div class="col-sm-8">
                    <input type="text" class="form-control" readonly placeholder="<?= $model->amount ?>">
                </div>
            </div>
            <div class="hr-line-dashed"></div>
            <div class="form-group">
                <label class="col-sm-2 control-label">备注</label>
                <div class="col-sm-8">
                    <textarea class="form-control" <?= $model->status == 2 ? 'name="remark"' : 'readonly' ?>
                              rows="10"><?= $model->remark ?></textarea>
                </div>
            </div>
            <div class="hr-line-dashed"></div>
            <div class="form-group">
                <div class="col-sm-5 col-sm-offset-2">
                    <?php if ($model->status == 2): ?>
                        <input type="hidden" name="_csrf" value="<?= Yii::$app->request->csrfToken ?>">
                        <input type="hidden" name="status" data-status="<?= $model->status ?>" value="">
                        <button type="submit" class="btn btn-info pass">通过</button>
                        &emsp;
                        <button type="submit" class="btn btn-info noPass">驳回</button>
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
        $('[name="status"]').val(3);
    });
    $('.noPass').click(function () {
        if ($('[name="remark"]').val()) {
            $('[name="status"]').val(1);
            return true;
        }
        showMsg('请填写备注');
        return false;
    });
</script>