<?php $this->registerJsFile('@web/js/imgPreview.js', ['depends' => ['app\assets\ModelAsset']]) ?>
<?php $this->registerJsFile('@web/js/upload.min.js', ['depends' => ['app\assets\ModelAsset']]) ?>
<div class="wrapper wrapper-content animated">
    <div class="ibox-content">
        <form method="post" class="form-horizontal">
            <div class="row">
                <div class="col-sm-6">
                    <div class="hr-line-dashed"></div>
                    <div class="form-group">
                        <label class="col-sm-4 control-label">意向状态</label>
                        <div class="col-sm-5">
                            <input type="text" class="form-control"
                                   placeholder="<?= \vendor\project\helpers\Constant::intentionStatus()[$model->status] ?>"
                                   readonly>
                        </div>
                    </div>
                    <div class="hr-line-dashed"></div>
                    <div class="form-group">
                        <label class="col-sm-4 control-label">场站编号</label>
                        <div class="col-sm-5">
                            <input type="text" class="form-control" placeholder="<?= $model->field->no ?>" readonly>
                        </div>
                    </div>
                    <div class="hr-line-dashed"></div>
                    <div class="form-group">
                        <label class="col-sm-4 control-label">意向用户</label>
                        <div class="col-sm-5">
                            <input type="text" class="form-control" placeholder="<?= $model->user->tel ?>" readonly>
                        </div>
                    </div>
                    <div class="hr-line-dashed"></div>
                    <div class="form-group">
                        <label class="col-sm-4 control-label">推荐用户</label>
                        <div class="col-sm-5">
                            <input type="text" class="form-control"
                                   placeholder="<?= $model->cobber ? $model->cobber->tel : '' ?>" readonly>
                        </div>
                    </div>
                </div>
                <div class="col-sm-6">
                    <div class="hr-line-dashed"></div>
                    <div class="form-group">
                        <label class="col-sm-3 control-label">意向来源</label>
                        <div class="col-sm-5">
                            <input type="text" class="form-control"
                                   placeholder="<?= \vendor\project\helpers\Constant::intentionSource()[$model->source] ?>"
                                   readonly>
                        </div>
                    </div>
                    <div class="hr-line-dashed"></div>
                    <div class="form-group">
                        <label class="col-sm-3 control-label">认购金额</label>
                        <div class="col-sm-5">
                            <input type="text" class="form-control" placeholder="<?= $model->purchase_amount ?>"
                                   readonly>
                        </div>
                    </div>
                    <div class="hr-line-dashed"></div>
                    <div class="form-group">
                        <label class="col-sm-3 control-label">定金金额</label>
                        <div class="col-sm-5">
                            <input type="text" class="form-control" placeholder="<?= $model->order_amount ?>" readonly>
                        </div>
                    </div>
                    <div class="hr-line-dashed"></div>
                    <div class="form-group">
                        <label class="col-sm-3 control-label">分成比例</label>
                        <div class="col-sm-5">
                            <input type="text" class="form-control" placeholder="<?= $model->part_ratio ?>" readonly>
                        </div>
                    </div>
                </div>
            </div>
            <div class="hr-line-dashed"></div>
            <div class="form-group">
                <label class="col-sm-2 control-label">打款凭条</label>
                <div class="col-sm-8">
                    <div class="voucher"></div>
                </div>
                <script>
                    uploadImg('.voucher', 'voucher', '<?=$model->voucher?>', true, 1);
                </script>
            </div>
            <div class="hr-line-dashed"></div>
            <div class="form-group">
                <label class="col-sm-2 control-label">意向合同</label>
                <div class="col-sm-8">
                    <div class="contract"></div>
                </div>
                <script>
                    uploadImg('.contract', 'contract', '<?=$model->contract?>', true, 4);
                </script>
            </div>
            <div class="hr-line-dashed"></div>
            <div class="form-group">
                <label class="col-sm-2 control-label">备注</label>
                <div class="col-sm-8">
                    <textarea class="form-control" rows="10"
                        <?= in_array($model->status, [4, 5]) ? 'readonly' : 'name="remark"' ?>><?= $model->remark ?></textarea>
                </div>
            </div>
            <div class="hr-line-dashed"></div>
            <div class="form-group">
                <div class="col-sm-5 col-sm-offset-2">
                    <?php if ($model->status == 3): ?>
                        <input type="hidden" name="_csrf" value="<?= Yii::$app->request->csrfToken ?>">
                        <input type="hidden" name="status" value="4">
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
        $('[name="status"]').val(4);
    });
    $('.noPass').click(function () {
        if ($('[name="remark"]').val()) {
            $('[name="status"]').val(5);
            return true;
        }
        showMsg('请填写备注');
        return false;
    });
</script>
