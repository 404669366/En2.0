<?php $this->registerJsFile('@web/js/imgPreview.js', ['depends' => ['app\assets\ModelAsset']]) ?>
<?php $this->registerJsFile('@web/js/map.js', ['depends' => ['app\assets\ModelAsset']]) ?>
<?php $this->registerJsFile('@web/js/upload.js', ['depends' => ['app\assets\ModelAsset']]) ?>
<div class="wrapper wrapper-content animated">
    <div class="ibox-content">
        <div class="form-horizontal">
            <div class="hr-line-dashed"></div>
            <div class="form-group">
                <label class="col-sm-2 control-label">场站状态</label>
                <div class="col-sm-8">
                    <input type="text" class="form-control"
                           placeholder="<?= \vendor\project\helpers\Constant::baseFieldStatus()[$model->status] ?>"
                           readonly>
                </div>
            </div>
            <div class="hr-line-dashed"></div>
            <div class="form-group">
                <label class="col-sm-2 control-label">转化场站</label>
                <div class="col-sm-8">
                    <input type="text" class="form-control" placeholder="<?= $model->field ? $model->field->no : '' ?>"
                           readonly>
                </div>
            </div>
            <div class="hr-line-dashed"></div>
            <div class="form-group">
                <label class="col-sm-2 control-label">场站用户</label>
                <div class="col-sm-8">
                    <input type="text" class="form-control" placeholder="<?= $model->user ? $model->user->tel : '' ?>"
                           readonly>
                </div>
            </div>
            <div class="hr-line-dashed"></div>
            <div class="form-group">
                <label class="col-sm-2 control-label">推荐用户</label>
                <div class="col-sm-8">
                    <input type="text" class="form-control"
                           placeholder="<?= $model->cobber ? $model->cobber->tel : '' ?>" readonly>
                </div>
            </div>
            <div class="hr-line-dashed"></div>
            <div class="form-group">
                <label class="col-sm-2 control-label">场站位置</label>
                <div class="col-sm-8">
                    <div class="myMap"></div>
                </div>
                <script>
                    map({
                        element: 'myMap',
                        default: {address: '<?=$model->address?>', lng: '<?=$model->lng?>', lat: '<?=$model->lat?>'}
                    });
                </script>
            </div>
            <div class="hr-line-dashed"></div>
            <div class="form-group">
                <label class="col-sm-2 control-label">备注</label>
                <div class="col-sm-8">
                    <textarea class="form-control" readonly rows="10"><?= $model->remark ?></textarea>
                </div>
            </div>
            <div class="hr-line-dashed"></div>
            <div class="form-group">
                <div class="col-sm-4 col-sm-offset-2">
                    <?php if($model->status == 1):?>
                    <a class="btn btn-primary" href="/field/base/change?id=<?= $model->id ?>">转化</a>
                    &emsp;
                    <?php endif;?>
                    <button class="btn btn-white back">返回</button>
                </div>
            </div>
        </div>
    </div>
</div>