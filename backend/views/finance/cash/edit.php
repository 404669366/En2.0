<div class="wrapper wrapper-content animated">
    <div class="ibox-content">
        <form method="post" class="form-horizontal">
            <input type="hidden" name="_csrf" value="<?= Yii::$app->request->csrfToken ?>">
            <div class="hr-line-dashed"></div>
            <div class="form-group">
                <label class="col-sm-2 control-label">提现编号</label>
                <div class="col-sm-8">
                    <input type="text" class="form-control" readonly placeholder="<?= $model->no ?>">
                </div>
            </div>
            <div class="hr-line-dashed"></div>
            <div class="form-group">
                <label class="col-sm-2 control-label">可提收益</label>
                <div class="col-sm-8">
                    <input type="text" class="form-control" readonly placeholder="<?= $surplus ?>">
                </div>
            </div>
            <div class="hr-line-dashed"></div>
            <div class="form-group">
                <label class="col-sm-2 control-label">提现金额</label>
                <div class="col-sm-8">
                    <input type="text" class="form-control" name="money" value="<?= $model->money ?>">
                </div>
            </div>
            <?php if ($model->status == 3): ?>
                <div class="hr-line-dashed"></div>
                <div class="form-group">
                    <label class="col-sm-2 control-label">审核备注</label>
                    <div class="col-sm-8">
                        <textarea class="form-control" rows="5" readonly><?= $model->remark ?></textarea>
                    </div>
                </div>
            <?php endif; ?>
            <div class="hr-line-dashed"></div>
            <div class="form-group">
                <div class="col-sm-4 col-sm-offset-2">
                    <?php if (!$model->status): ?>
                        <button class="btn btn-primary" type="submit">保存</button>
                    <?php endif; ?>
                    <a class="btn btn-white" href="/finance/cash/list">返回</a>
                </div>
            </div>
        </form>
    </div>
</div>