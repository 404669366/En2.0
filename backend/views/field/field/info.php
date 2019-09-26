<?php $this->registerCssFile('@web/css/summernote.css', ['depends' => ['app\assets\ModelAsset']]) ?>
<?php $this->registerCssFile('@web/css/summernote-bs4.css', ['depends' => ['app\assets\ModelAsset']]) ?>
<?php $this->registerJsFile('@web/js/summernote.js', ['depends' => ['app\assets\ModelAsset']]) ?>
<?php $this->registerJsFile('@web/js/summernote-zh-CN.js', ['depends' => ['app\assets\ModelAsset']]) ?>
<?php $this->registerJsFile('@web/js/map.js', ['depends' => ['app\assets\ModelAsset']]) ?>
<?php $this->registerJsFile('@web/js/upload.min.js', ['depends' => ['app\assets\ModelAsset']]) ?>
<?php $this->registerJsFile('@web/js/imgPreview.js', ['depends' => ['app\assets\ModelAsset']]) ?>
<div class="wrapper wrapper-content animated">
    <div class="ibox-content">
        <form method="post" class="form-horizontal">
            <div class="row">
                <div class="col-sm-6">
                    <div class="hr-line-dashed"></div>
                    <div class="form-group">
                        <label class="col-sm-4 control-label">场站编号</label>
                        <div class="col-sm-5">
                            <input type="text" class="form-control" readonly placeholder="<?= $model->no ?>">
                        </div>
                    </div>
                    <div class="hr-line-dashed"></div>
                    <div class="form-group">
                        <label class="col-sm-4 control-label">场站名称</label>
                        <div class="col-sm-5">
                            <input type="text" class="form-control" readonly placeholder="<?= $model->name ?>">
                        </div>
                    </div>
                    <div class="hr-line-dashed"></div>
                    <div class="form-group">
                        <label class="col-sm-4 control-label">场站标题</label>
                        <div class="col-sm-5">
                            <input type="text" class="form-control" readonly placeholder="<?= $model->title ?>">
                        </div>
                    </div>
                    <div class="hr-line-dashed"></div>
                    <div class="form-group">
                        <label class="col-sm-4 control-label">场站特色</label>
                        <div class="col-sm-5">
                            <textarea class="form-control" rows="6" readonly><?= $model->trait ?></textarea>
                        </div>
                    </div>
                    <div class="hr-line-dashed"></div>
                    <div class="form-group">
                        <label class="col-sm-4 control-label">场站配置</label>
                        <div class="col-sm-5">
                            <textarea class="form-control" readonly
                                      rows="6"><?= str_replace('<br>', "\r\n", $model->field_configure) ?></textarea>
                        </div>
                    </div>
                </div>
                <div class="col-sm-6">
                    <div class="hr-line-dashed"></div>
                    <div class="form-group">
                        <label class="col-sm-3 control-label">业务类型</label>
                        <div class="col-sm-5">
                            <input type="text" class="form-control" readonly placeholder="<?= \vendor\project\helpers\Constant::businessType()[$model->business_type] ?>">
                        </div>
                    </div>
                    <div class="hr-line-dashed"></div>
                    <div class="form-group">
                        <label class="col-sm-3 control-label">投资类型</label>
                        <div class="col-sm-5">
                            <input type="text" class="form-control" readonly placeholder="<?= \vendor\project\helpers\Constant::investType()[$model->invest_type] ?>">
                        </div>
                    </div>
                    <div class="hr-line-dashed"></div>
                    <div class="form-group">
                        <label class="col-sm-3 control-label">预算金额</label>
                        <div class="col-sm-5">
                            <input type="text" class="form-control" readonly placeholder="<?= $model->budget_amount ?>">
                        </div>
                    </div>
                    <div class="hr-line-dashed"></div>
                    <div class="form-group">
                        <label class="col-sm-3 control-label">起投金额</label>
                        <div class="col-sm-5">
                            <input type="text" class="form-control" readonly placeholder="<?= $model->lowest_amount ?>">
                        </div>
                    </div>
                    <div class="hr-line-dashed"></div>
                    <div class="form-group">
                        <label class="col-sm-3 control-label">场地用户</label>
                        <div class="col-sm-5">
                            <input type="text" class="form-control" readonly placeholder="<?= $model->local ?>">
                        </div>
                    </div>
                    <div class="hr-line-dashed"></div>
                    <div class="form-group">
                        <label class="col-sm-3 control-label">场地分成占比</label>
                        <div class="col-sm-5">
                            <input type="text" class="form-control" readonly placeholder="<?= $model->field_ratio ?>">
                        </div>
                    </div>
                    <div class="hr-line-dashed"></div>
                    <div class="form-group">
                        <label class="col-sm-3 control-label">投资分成占比</label>
                        <div class="col-sm-5">
                            <input type="text" class="form-control" readonly placeholder="<?= $model->invest_ratio ?>">
                        </div>
                    </div>
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
                        default: {address: '<?=$model->address?>', lng: '<?=$model->lng?>', lat: '<?=$model->lat?>'},
                        readOnly: true,
                    });
                </script>
            </div>
            <div class="hr-line-dashed"></div>
            <div class="form-group">
                <label class="col-sm-2 control-label">场站图片</label>
                <div class="col-sm-8">
                    <div class="images"></div>
                </div>
                <script>
                    uploadImg('.images', 'images', '<?=$model->images?>', true, 5);
                </script>
            </div>
            <div class="hr-line-dashed"></div>
            <div class="form-group">
                <label class="col-sm-2 control-label">场站介绍</label>
                <div class="col-sm-8">
                    <textarea class="form-control summernote"></textarea>
                </div>
                <script>
                    $('.summernote').summernote({lang: "zh-CN", height: 500});
                    $('.summernote').summernote('code', `<?=$model->getIntro()?>`);
                    $('.summernote').summernote('disable');
                </script>
            </div>
            <div class="hr-line-dashed"></div>
            <div class="form-group">
                <label class="col-sm-2 control-label">备案文件</label>
                <div class="col-sm-8">
                    <div class="record_file"></div>
                </div>
                <script>
                    uploadFile('.record_file', 'record_file', '<?=$model->record_file?>',true);
                </script>
            </div>
            <div class="hr-line-dashed"></div>
            <div class="form-group">
                <label class="col-sm-2 control-label">场地合同</label>
                <div class="col-sm-8">
                    <div class="field_contract"></div>
                </div>
                <script>
                    uploadImg('.field_contract', 'field_contract', '<?=$model->field_contract?>', true);
                </script>
            </div>
            <div class="hr-line-dashed"></div>
            <div class="form-group">
                <label class="col-sm-2 control-label">场站证明</label>
                <div class="col-sm-8">
                    <div class="field_prove"></div>
                </div>
                <script>
                    uploadImg('.field_prove', 'field_prove', '<?=$model->field_prove?>', true);
                </script>
            </div>
            <div class="hr-line-dashed"></div>
            <div class="form-group">
                <label class="col-sm-2 control-label">施工图纸</label>
                <div class="col-sm-8">
                    <div class="field_drawing"></div>
                </div>
                <script>
                    uploadImg('.field_drawing', 'field_drawing', '<?=$model->field_drawing?>', true);
                </script>
            </div>
            <div class="hr-line-dashed"></div>
            <div class="form-group">
                <label class="col-sm-2 control-label">变压器图纸</label>
                <div class="col-sm-8">
                    <div class="transformer_drawing"></div>
                </div>
                <script>
                    uploadImg('.transformer_drawing', 'transformer_drawing', '<?=$model->transformer_drawing?>', true);
                </script>
            </div>
            <div class="hr-line-dashed"></div>
            <div class="form-group">
                <label class="col-sm-2 control-label">电力合同</label>
                <div class="col-sm-8">
                    <div class="power_contract"></div>
                </div>
                <script>
                    uploadImg('.power_contract', 'power_contract', '<?=$model->power_contract?>', true);
                </script>
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
                    <button class="btn btn-white back">返回</button>
                </div>
            </div>
        </form>
    </div>
</div>