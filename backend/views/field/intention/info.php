<?php $this->registerJsFile('@web/js/upload.min.js', ['depends' => ['app\assets\ModelAsset']]) ?>
<div class="wrapper wrapper-content animated">
    <div class="ibox-content">
        <div class="form-horizontal">
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
                            <input type="text" class="form-control" placeholder="<?= $model->field ?>" readonly>
                        </div>
                    </div>
                    <div class="hr-line-dashed"></div>
                    <div class="form-group">
                        <label class="col-sm-4 control-label">意向用户</label>
                        <div class="col-sm-5">
                            <input type="text" class="form-control" placeholder="<?= $model->user ?>" readonly>
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
                            <input type="text" class="form-control" placeholder="<?= $model->ratio ?>" readonly>
                        </div>
                    </div>
                    <div class="hr-line-dashed"></div>
                    <div class="form-group">
                        <label class="col-sm-3 control-label">认购金额</label>
                        <div class="col-sm-5">
                            <input type="text" class="form-control" placeholder="<?= $model->amount ?>"
                                   readonly>
                        </div>
                    </div>
                    <div class="hr-line-dashed"></div>
                    <div class="form-group">
                        <label class="col-sm-3 control-label">意向合同</label>
                        <div class="col-sm-5">
                            <div class="contract"></div>
                        </div>
                        <script>
                            uploadFile('.contract', 'contract', '<?=$model->contract?>', true);
                        </script>
                    </div>
                </div>
            </div>
            <div class="hr-line-dashed"></div>
            <div class="form-group">
                <label class="col-sm-2 control-label">备注</label>
                <div class="col-sm-8">
                    <textarea class="form-control" rows="10" readonly><?= $model->remark ?></textarea>
                </div>
            </div>
            <div class="hr-line-dashed"></div>
            <div class="form-group">
                <div class="col-sm-5 col-sm-offset-2">
                    <button class="btn btn-white" type="button" data-url="/field/intention/list">返回</button>
                </div>
            </div>
        </div>
    </div>
</div>