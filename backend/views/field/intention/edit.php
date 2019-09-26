<?php $this->registerJsFile('@web/js/upload.min.js', ['depends' => ['app\assets\ModelAsset']]) ?>
<div class="wrapper wrapper-content animated">
    <div class="ibox-content">
        <form method="post" class="form-horizontal">
            <input type="hidden" name="_csrf" value="<?= Yii::$app->request->csrfToken ?>">
            <input type="hidden" name="status" value="<?= $model->status ?>">
            <div class="row">
                <div class="col-sm-6">
                    <div class="hr-line-dashed"></div>
                    <div class="form-group">
                        <label class="col-sm-4 control-label">意向编号</label>
                        <div class="col-sm-5">
                            <input type="text" class="form-control" placeholder="<?= $model->no ?>" readonly>
                        </div>
                    </div>
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
                            <select class="form-control" name="field">
                                <option value="">----</option>
                                <?php foreach ($fields as $v): ?>
                                    <option <?= $model->field == $v['no'] ? 'selected' : '' ?>
                                            value="<?= $v['no'] ?>"><?= $v['no'] ?></option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                    </div>
                    <div class="hr-line-dashed"></div>
                    <div class="form-group">
                        <label class="col-sm-4 control-label">意向用户</label>
                        <div class="col-sm-5">
                            <input type="text" class="form-control" name="user" value="<?= $model->user ?>"
                                   placeholder="请填写用户手机号">
                            <small>* 账户不存在将自动创建</small>
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
                        <label class="col-sm-3 control-label">分成比例</label>
                        <div class="col-sm-5">
                            <input type="text" class="form-control"
                                   placeholder="<?= $model->ratio ? $model->ratio : '系统自动完成计算' ?>" readonly>
                        </div>
                    </div>
                    <div class="hr-line-dashed"></div>
                    <div class="form-group">
                        <label class="col-sm-3 control-label">认购金额</label>
                        <div class="col-sm-5">
                            <input type="text" class="form-control" name="amount" value="<?= $model->amount ?: 0 ?>">
                        </div>
                    </div>
                    <div class="hr-line-dashed"></div>
                    <div class="form-group">
                        <label class="col-sm-3 control-label">意向合同</label>
                        <div class="col-sm-5">
                            <div class="contract"></div>
                        </div>
                        <script>
                            uploadFile('.contract', 'contract', '<?=$model->contract?>');
                            $('.contract').find('.fileInput').prop('accept', '.pdf');
                        </script>
                    </div>
                </div>
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
                <div class="col-sm-5 col-sm-offset-2 btnBox">
                    <button class="btn btn-primary" type="submit">保存内容</button> &emsp;
                    <?php if ($model->status == 1): ?>
                        <button class="btn btn-warning save" data-s="2" type="submit">保存合同</button> &emsp;
                    <?php endif; ?>
                    <?php if ($model->status == 2): ?>
                        <button class="btn btn-warning save" data-s="3" type="submit">提交审核</button> &emsp;
                    <?php endif; ?>
                    <button class="btn btn-white jump" type="button" data-url="/field/intention/list">返回</button>
                </div>
            </div>
        </form>
    </div>
</div>
<script>
    $('.btnBox').on('click', '.save', function () {
        $('[name="status"]').val($(this).data('s'));
    });
</script>