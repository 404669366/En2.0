<?php $this->registerCssFile('@web/css/summernote.css', ['depends' => ['app\assets\ModelAsset']]) ?>
<?php $this->registerCssFile('@web/css/summernote-bs4.css', ['depends' => ['app\assets\ModelAsset']]) ?>
<?php $this->registerJsFile('@web/js/summernote.js', ['depends' => ['app\assets\ModelAsset']]) ?>
<?php $this->registerJsFile('@web/js/summernote-zh-CN.js', ['depends' => ['app\assets\ModelAsset']]) ?>
<?php $this->registerJsFile('@web/js/upload.min.js', ['depends' => ['app\assets\ModelAsset']]) ?>
<?php $this->registerCssFile('@web/css/selectTree.css', ['depends' => ['app\assets\ModelAsset']]) ?>
<?php $this->registerJsFile('@web/js/selectTree.js', ['depends' => ['app\assets\ModelAsset']]) ?>
<div class="wrapper wrapper-content animated">
    <div class="ibox-content">
        <form method="post" class="form-horizontal">
            <input type="hidden" name="_csrf" value="<?= Yii::$app->request->csrfToken ?>">
            <div class="hr-line-dashed"></div>
            <div class="form-group">
                <label class="col-sm-2 control-label">公司名称</label>
                <div class="col-sm-8">
                    <input type="text" class="form-control" name="name" value="<?= $model->name ?>">
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
                <label class="col-sm-2 control-label">对公账户</label>
                <div class="col-sm-8">
                    <input type="text" class="form-control" name="account" value="<?= $model->account ?>">
                </div>
            </div>
            <div class="hr-line-dashed"></div>
            <div class="form-group">
                <label class="col-sm-2 control-label">开户银行</label>
                <div class="col-sm-8">
                    <input type="text" class="form-control" name="bank" value="<?= $model->bank ?>">
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
                <label class="col-sm-2 control-label">营业执照</label>
                <div class="col-sm-8">
                    <div class="license"></div>
                </div>
                <script>
                    uploadImg('.license', 'license', '<?= $model->license ?>', false, 1);
                </script>
            </div>
            <div class="hr-line-dashed"></div>
            <div class="form-group">
                <label class="col-sm-2 control-label">法人手机号</label>
                <div class="col-sm-8">
                    <input type="text" class="form-control" name="legal" value="<?= $model->legal ?>">
                </div>
            </div>
            <div class="hr-line-dashed"></div>
            <div class="form-group">
                <label class="col-sm-2 control-label">法人身份证正反面</label>
                <div class="col-sm-8">
                    <div class="legal_card"></div>
                </div>
                <script>
                    uploadImg('.legal_card', 'legal_card', '<?= $model->legal_card ?>', false, 2);
                </script>
            </div>
            <div class="hr-line-dashed"></div>
            <div class="form-group">
                <label class="col-sm-2 control-label">管理员手机号</label>
                <div class="col-sm-8">
                    <input type="text" class="form-control" name="admin" value="<?= $model->admin ?>">
                    <small style="color: red">* 将自动生成一个管理员账号(默认密码手机号后六位,修改此项旧管理员账户将被删除)</small>
                </div>
            </div>
            <div class="hr-line-dashed"></div>
            <div class="form-group">
                <label class="col-sm-2 control-label">管理员身份证正反面</label>
                <div class="col-sm-8">
                    <div class="admin_card"></div>
                </div>
                <script>
                    uploadImg('.admin_card', 'admin_card', '<?= $model->admin_card ?>', false, 2);
                </script>
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
                    $('.powersTree').on('click', '.f-treeList-radio', function () {
                        $('[name="powers"]').val(selectTree.treeJson('.powersTree').join(','));
                    });
                </script>
            </div>
            <div class="hr-line-dashed"></div>
            <div class="form-group">
                <label class="col-sm-2 control-label">备注</label>
                <div class="col-sm-8">
                    <textarea class="form-control" name="remark"><?= $model->remark ?></textarea>
                </div>
            </div>
            <div class="hr-line-dashed"></div>
            <div class="form-group">
                <div class="col-sm-4 col-sm-offset-2">
                    <button class="btn btn-primary" type="submit">保存内容</button>
                    <button class="btn btn-white back">返回</button>
                </div>
            </div>
        </form>
    </div>
</div>