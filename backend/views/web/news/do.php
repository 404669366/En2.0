<?php $this->registerCssFile('@web/css/summernote.css', ['depends' => ['app\assets\ModelAsset']]) ?>
<?php $this->registerCssFile('@web/css/summernote-bs4.css', ['depends' => ['app\assets\ModelAsset']]) ?>
<?php $this->registerJsFile('@web/js/summernote.js', ['depends' => ['app\assets\ModelAsset']]) ?>
<?php $this->registerJsFile('@web/js/summernote-zh-CN.js', ['depends' => ['app\assets\ModelAsset']]) ?>
<?php $this->registerJsFile('@web/js/oss.js', ['depends' => ['app\assets\ModelAsset']]) ?>
<div class="wrapper wrapper-content animated">
    <div class="ibox-content">
        <form method="post" class="form-horizontal">
            <input type="hidden" name="_csrf" value="<?= Yii::$app->request->csrfToken ?>">
            <div class="hr-line-dashed"></div>
            <div class="form-group">
                <label class="col-sm-2 control-label">新闻标题</label>
                <div class="col-sm-4">
                    <input type="text" class="form-control" name="title" value="<?= $model->title ?>">
                </div>
            </div>
            <div class="hr-line-dashed"></div>
            <div class="form-group">
                <label class="col-sm-2 control-label">新闻简介</label>
                <div class="col-sm-4">
                    <textarea class="form-control" name="intro" rows="10"><?= $model->intro ?></textarea>
                </div>
            </div>
            <div class="hr-line-dashed"></div>
            <div class="form-group">
                <label class="col-sm-2 control-label">新闻来源</label>
                <div class="col-sm-4">
                    <select class="form-control" name="source">
                        <?php foreach (\vendor\project\helpers\Constant::newsSource() as $k => $v): ?>
                            <option value="<?= $k ?>" <?= $model->source == $k ? 'selected' : '' ?>><?= $v['name'] ?></option>
                        <?php endforeach; ?>
                    </select>
                </div>
            </div>
            <div class="hr-line-dashed"></div>
            <div class="form-group">
                <label class="col-sm-2 control-label">来源路由</label>
                <div class="col-sm-4">
                    <input type="text" class="form-control" name="url" value="<?= $model->url ?>"
                           placeholder="原创新闻不用填写此项">
                </div>
            </div>
            <div class="hr-line-dashed"></div>
            <div class="form-group">
                <label class="col-sm-2 control-label">封面图片</label>
                <div class="col-sm-8">
                    <input type="file" class="form-control f" accept="image/*" style="margin-bottom: 1rem">
                    <input type="hidden" name="image" value="<?= $model->image ?>">
                    <div class="images image"></div>
                </div>
                <script>
                    window.preview.make('.image', '<?=$model->image?>', 'uploadPre');
                    $('.f').change(function () {
                        if ($('[name="image"]').val() && $('[name="image"]').val().split(',').length >= 1) {
                            window.showMsg('最多不超过1张');
                            $('.f').val('');
                            return;
                        }
                        window.oss.upload($(this)[0].files[0], function (src) {
                            $('.image').append('<img class="uploadPre" data-input="image" src="' + src + '"/>');
                            $('[name="image"]').val(function (i, v) {
                                if (v) {
                                    return v + ',' + src;
                                }
                                return src;
                            });
                            $('.f').val('');
                        })
                    });
                </script>
            </div>
            <div class="hr-line-dashed"></div>
            <div class="form-group">
                <label class="col-sm-2 control-label">新闻详情</label>
                <div class="col-sm-8">
                    <textarea class="form-control summernote" name="content"></textarea>
                </div>
                <script>
                    $('.summernote').summernote({lang: "zh-CN", height: 500});
                    $('.summernote').summernote('code', `<?=$content?>`);
                </script>
            </div>
            <div class="hr-line-dashed"></div>
            <div class="form-group">
                <div class="col-sm-4 col-sm-offset-2">
                    <button class="btn btn-primary" type="submit">保存内容</button>
                    &emsp;
                    <button class="btn btn-white back">返回</button>
                </div>
            </div>
        </form>
    </div>
</div>