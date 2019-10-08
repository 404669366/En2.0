<?php $this->registerJsFile('@web/js/upload.min.js', ['depends' => ['app\assets\ModelAsset']]) ?>
<div class="wrapper wrapper-content animated">
    <div class="ibox-content">
        <form method="post" class="form-horizontal">
            <input type="hidden" name="_csrf" value="<?= Yii::$app->request->csrfToken ?>">
            <div class="hr-line-dashed"></div>
            <div class="form-group">
                <label class="col-sm-2 control-label">电桩名称</label>
                <div class="col-sm-8">
                    <input type="text" class="form-control" name="name" value="<?= $model->name ?>">
                </div>
            </div>
            <div class="hr-line-dashed"></div>
            <div class="form-group">
                <label class="col-sm-2 control-label">电桩品牌</label>
                <div class="col-sm-8">
                    <input type="text" class="form-control" name="brand" value="<?= $model->brand ?>">
                </div>
            </div>
            <div class="hr-line-dashed"></div>
            <div class="form-group">
                <label class="col-sm-2 control-label">电桩图片</label>
                <div class="col-sm-8">
                    <div class="images"></div>
                </div>
                <script>
                    uploadImg('.images', 'images', '<?=$model->images?>', false, 5);
                </script>
            </div>
            <div class="hr-line-dashed"></div>
            <div class="form-group">
                <label class="col-sm-2 control-label">电桩功率</label>
                <div class="col-sm-8">
                    <input type="text" class="form-control" name="power" value="<?= $model->power ?>">
                </div>
            </div>
            <div class="hr-line-dashed"></div>
            <div class="form-group">
                <label class="col-sm-2 control-label">电桩电压</label>
                <div class="col-sm-8">
                    <input type="text" class="form-control" name="voltage" value="<?= $model->voltage ?>">
                </div>
            </div>
            <div class="hr-line-dashed"></div>
            <div class="form-group">
                <label class="col-sm-2 control-label">电桩电流</label>
                <div class="col-sm-8">
                    <input type="text" class="form-control" name="current" value="<?= $model->current ?>">
                </div>
            </div>
            <div class="hr-line-dashed"></div>
            <div class="form-group">
                <label class="col-sm-2 control-label">电桩类型</label>
                <div class="col-sm-8">
                    <select class="form-control" name="type">
                        <?php foreach (\vendor\project\helpers\Constant::pileType() as $k => $v): ?>
                            <option <?= $model->type == $k ? 'selected' : '' ?>
                                    value="<?= $k ?>"><?= $v ?></option>
                        <?php endforeach; ?>
                    </select>
                </div>
            </div>
            <div class="hr-line-dashed"></div>
            <div class="form-group">
                <label class="col-sm-2 control-label">电桩标准</label>
                <div class="col-sm-8">
                    <select class="form-control" name="standard">
                        <?php foreach (\vendor\project\helpers\Constant::pileStandard() as $k => $v): ?>
                            <option <?= $model->standard == $k ? 'selected' : '' ?>
                                    value="<?= $k ?>"><?= $v ?></option>
                        <?php endforeach; ?>
                    </select>
                </div>
            </div>
            <div class="hr-line-dashed"></div>
            <div class="form-group">
                <div class="col-sm-4 col-sm-offset-2">
                    <button class="btn btn-primary" type="submit">保存</button>
                    <a class="btn btn-white" href="/oam/model/list">返回</a>
                </div>
            </div>
        </form>
    </div>
</div>