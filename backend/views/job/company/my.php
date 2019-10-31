<?php $this->registerCssFile('@web/css/summernote.css', ['depends' => ['app\assets\ModelAsset']]) ?>
<?php $this->registerCssFile('@web/css/summernote-bs4.css', ['depends' => ['app\assets\ModelAsset']]) ?>
<?php $this->registerJsFile('@web/js/summernote.js', ['depends' => ['app\assets\ModelAsset']]) ?>
<?php $this->registerJsFile('@web/js/summernote-zh-CN.js', ['depends' => ['app\assets\ModelAsset']]) ?>
<?php $this->registerJsFile('@web/js/upload.min.js', ['depends' => ['app\assets\ModelAsset']]) ?>
<?php $this->registerJsFile('@web/js/imgPreview.js', ['depends' => ['app\assets\ModelAsset']]) ?>
<div class="wrapper wrapper-content animated">
    <div class="ibox-content">
        <form method="post" class="form-horizontal">
            <input type="hidden" name="_csrf" value="<?= Yii::$app->request->csrfToken ?>">
            <div class="hr-line-dashed"></div>
            <div class="form-group">
                <label class="col-sm-2 control-label">入驻时间</label>
                <div class="col-sm-8">
                    <input type="text" class="form-control" readonly
                           placeholder="<?= date('Y-m-d H:i:s', $model->created_at) ?>">
                </div>
            </div>
            <div class="hr-line-dashed"></div>
            <div class="form-group">
                <label class="col-sm-2 control-label">公司名称</label>
                <div class="col-sm-8">
                    <input type="text" class="form-control" readonly placeholder="<?= $model->name ?>">
                </div>
            </div>
            <div class="hr-line-dashed"></div>
            <div class="form-group">
                <label class="col-sm-2 control-label">公司简称</label>
                <div class="col-sm-8">
                    <input type="text" class="form-control" name="abridge" value="<?= $model->abridge ?>">
                </div>
            </div>
            <div class="hr-line-dashed"></div>
            <div class="form-group">
                <label class="col-sm-2 control-label">公司地址</label>
                <div class="col-sm-8">
                    <input type="text" class="form-control" name="address" value="<?= $model->address ?>">
                </div>
            </div>
            <div class="hr-line-dashed"></div>
            <div class="form-group">
                <label class="col-sm-2 control-label">公司Logo</label>
                <div class="col-sm-8">
                    <div class="logo"></div>
                </div>
                <script>
                    uploadImg('.logo', 'logo', '<?= $model->logo ?>', false, 1);
                </script>
            </div>
            <div class="hr-line-dashed"></div>
            <div class="form-group">
                <label class="col-sm-2 control-label">公司介绍</label>
                <div class="col-sm-8">
                    <textarea class="form-control summernote" name="intro"></textarea>
                </div>
                <script>
                    $('.summernote').summernote({lang: "zh-CN", height: 500});
                    $('.summernote').summernote('code', `<?=$model->getIntro()?>`);
                </script>
            </div>
            <div class="hr-line-dashed"></div>
            <div class="form-group">
                <label class="col-sm-2 control-label">对公账户</label>
                <div class="col-sm-8">
                    <input type="text" class="form-control" readonly placeholder="<?= $model->account ?>">
                </div>
            </div>
            <div class="hr-line-dashed"></div>
            <div class="form-group">
                <label class="col-sm-2 control-label">开户银行</label>
                <div class="col-sm-8">
                    <input type="text" class="form-control" readonly placeholder="<?= $model->bank ?>">
                </div>
            </div>
            <div class="hr-line-dashed"></div>
            <div class="form-group">
                <label class="col-sm-2 control-label">营业执照</label>
                <div class="col-sm-8">
                    <div class="license"></div>
                </div>
                <script>
                    uploadImg('.license', 'license', '<?= $model->license ?>', true, 1);
                </script>
            </div>
            <div class="hr-line-dashed"></div>
            <div class="form-group">
                <label class="col-sm-2 control-label">法人手机号</label>
                <div class="col-sm-8">
                    <input type="text" class="form-control" readonly placeholder="<?= $model->legal ?>">
                </div>
            </div>
            <div class="hr-line-dashed"></div>
            <div class="form-group">
                <label class="col-sm-2 control-label">法人身份证正反面</label>
                <div class="col-sm-8">
                    <div class="legal_card"></div>
                </div>
                <script>
                    uploadImg('.legal_card', 'legal_card', '<?= $model->legal_card ?>', true, 2);
                </script>
            </div>
            <div class="hr-line-dashed"></div>
            <div class="form-group">
                <label class="col-sm-2 control-label">管理员手机号</label>
                <div class="col-sm-8">
                    <input type="text" class="form-control" readonly placeholder="<?= $model->admin ?>">
                </div>
            </div>
            <div class="hr-line-dashed"></div>
            <div class="form-group">
                <label class="col-sm-2 control-label">管理员身份证正反面</label>
                <div class="col-sm-8">
                    <div class="admin_card"></div>
                </div>
                <script>
                    uploadImg('.admin_card', 'admin_card', '<?= $model->admin_card ?>', true, 2);
                </script>
            </div>
            <div class="hr-line-dashed"></div>
            <div class="form-group">
                <div class="col-sm-4 col-sm-offset-2">
                    <button class="btn btn-primary" type="submit">保存内容</button>
                </div>
            </div>
        </form>
    </div>
</div>