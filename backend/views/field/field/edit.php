<?php $this->registerCssFile('@web/css/summernote.css', ['depends' => ['app\assets\ModelAsset']]) ?>
<?php $this->registerCssFile('@web/css/summernote-bs4.css', ['depends' => ['app\assets\ModelAsset']]) ?>
<?php $this->registerJsFile('@web/js/summernote.js', ['depends' => ['app\assets\ModelAsset']]) ?>
<?php $this->registerJsFile('@web/js/summernote-zh-CN.js', ['depends' => ['app\assets\ModelAsset']]) ?>
<?php $this->registerJsFile('@web/js/map.js', ['depends' => ['app\assets\ModelAsset']]) ?>
<?php $this->registerJsFile('@web/js/upload.min.js', ['depends' => ['app\assets\ModelAsset']]) ?>
<div class="wrapper wrapper-content animated">
    <div class="ibox-content">
        <form method="post" class="form-horizontal">
            <input type="hidden" name="_csrf" value="<?= Yii::$app->request->csrfToken ?>">
            <input type="hidden" name="status" value="1">
            <div class="row">
                <div class="col-sm-6">
                    <div class="hr-line-dashed"></div>
                    <div class="form-group">
                        <label class="col-sm-4 control-label">场站编号</label>
                        <div class="col-sm-5">
                            <input type="text" class="form-control" placeholder="<?= $model->no ?>" readonly>
                        </div>
                    </div>
                    <div class="hr-line-dashed"></div>
                    <div class="form-group">
                        <label class="col-sm-4 control-label">场站名称</label>
                        <div class="col-sm-5">
                            <input type="text" class="form-control" name="name" value="<?= $model->name ?>">
                        </div>
                    </div>
                    <div class="hr-line-dashed"></div>
                    <div class="form-group">
                        <label class="col-sm-4 control-label">场站标题</label>
                        <div class="col-sm-5">
                            <input type="text" class="form-control" name="title" value="<?= $model->title ?>">
                        </div>
                    </div>
                    <div class="hr-line-dashed"></div>
                    <div class="form-group">
                        <label class="col-sm-4 control-label">场站特色</label>
                        <div class="col-sm-5">
                            <textarea class="form-control" name="trait" rows="8"><?= $model->trait ?></textarea>
                        </div>
                    </div>
                    <div class="hr-line-dashed"></div>
                    <div class="form-group">
                        <label class="col-sm-4 control-label">场站配置</label>
                        <div class="col-sm-5">
                            <textarea class="form-control" name="field_configure"
                                      rows="8"><?= $model->field_configure ?></textarea>
                        </div>
                    </div>
                </div>
                <div class="col-sm-6">
                    <div class="hr-line-dashed"></div>
                    <div class="form-group">
                        <label class="col-sm-3 control-label">业务类型</label>
                        <div class="col-sm-5">
                            <select class="form-control" name="business_type">
                                <?php foreach (\vendor\project\helpers\Constant::businessType() as $k => $v): ?>
                                    <option <?= $model->business_type == $k ? 'selected' : '' ?>
                                            value="<?= $k ?>"><?= $v ?></option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                    </div>
                    <div class="hr-line-dashed"></div>
                    <div class="form-group">
                        <label class="col-sm-3 control-label">投资类型</label>
                        <div class="col-sm-5">
                            <select class="form-control" name="invest_type">
                                <?php foreach (\vendor\project\helpers\Constant::investType() as $k => $v): ?>
                                    <option <?= $model->invest_type == $k ? 'selected' : '' ?>
                                            value="<?= $k ?>"><?= $v ?></option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                    </div>
                    <div class="hr-line-dashed three"></div>
                    <div class="form-group three">
                        <label class="col-sm-3 control-label">绑定用户</label>
                        <div class="col-sm-5">
                            <div class="input-group">
                                <input type="text" class="form-control local_tel" placeholder="填写用户手机号完成场地方绑定"
                                       value="<?= $model->local ? $model->local->tel : '' ?>">
                                <span class="input-group-btn">
                                    <button type="button" class="btn btn-primary searchLocal">搜索</button>
                                </span>
                            </div>
                            <small style="color: red">* 如为租用场地,不绑定此项 (反之必填)</small>
                            <input type="hidden" name="local_id" value="<?= $model->local_id ?: 0 ?>">
                        </div>
                        <script>
                            $('.searchLocal').click(function () {
                                $('[name="local_id"]').val(0);
                                $.getJSON('/field/field/user-search', {tel: $('.local_tel').val()}, function (re) {
                                    if (re.type) {
                                        $('[name="local_id"]').val(re.data);
                                        showMsg('用户绑定成功');
                                    } else {
                                        showMsg('用户不存在,请重新输入用户手机号进行绑定');
                                    }
                                })
                            });
                        </script>
                    </div>
                    <div class="hr-line-dashed three"></div>
                    <div class="form-group three">
                        <label class="col-sm-3 control-label">绑定推荐用户</label>
                        <div class="col-sm-5">
                            <div class="input-group">
                                <input type="text" class="form-control cobber_tel" placeholder="填写用户手机号完成场地方绑定"
                                       value="<?= $model->cobber ? $model->cobber->tel : '' ?>">
                                <span class="input-group-btn">
                                    <button type="button" class="btn btn-primary searchCobber">搜索</button>
                                </span>
                            </div>
                            <small style="color: red">* 如为租用场地或没有推荐用户,不绑定此项</small>
                            <input type="hidden" name="cobber_id" value="<?= $model->cobber_id ?: 0 ?>">
                        </div>
                        <script>
                            $('.searchCobber').click(function () {
                                $('[name="cobber_id"]').val(0);
                                $.getJSON('/field/field/user-search', {tel: $('.cobber_tel').val()}, function (re) {
                                    if (re.type) {
                                        $('[name="cobber_id"]').val(re.data);
                                        showMsg('推荐用户绑定成功');
                                    } else {
                                        showMsg('用户不存在,请重新输入用户手机号进行绑定');
                                    }
                                })
                            });
                        </script>
                    </div>
                    <div class="hr-line-dashed three"></div>
                    <div class="form-group three">
                        <label class="col-sm-3 control-label">投资方分成占比</label>
                        <div class="col-sm-5">
                            <input type="text" class="form-control" name="invest_ratio"
                                   value="<?= $model->invest_ratio ?: 0 ?>">
                        </div>
                    </div>
                    <div class="hr-line-dashed three"></div>
                    <div class="form-group three">
                        <label class="col-sm-3 control-label">场地方分成占比</label>
                        <div class="col-sm-5">
                            <input type="text" class="form-control" name="field_ratio"
                                   value="<?= $model->field_ratio ?: 0 ?>">
                            <small style="color: red">* 如为租用场地,此项填写0 (反之必填)</small>
                        </div>
                    </div>
                    <div class="hr-line-dashed"></div>
                    <div class="form-group">
                        <label class="col-sm-3 control-label">预算金额</label>
                        <div class="col-sm-5">
                            <input type="text" class="form-control" name="budget_amount"
                                   value="<?= $model->budget_amount ?: 0 ?>">
                        </div>
                    </div>
                    <div class="hr-line-dashed"></div>
                    <div class="form-group">
                        <label class="col-sm-3 control-label">起投金额</label>
                        <div class="col-sm-5">
                            <input type="text" class="form-control" name="lowest_amount"
                                   value="<?= $model->lowest_amount ?: 0 ?>">
                        </div>
                    </div>
                    <script>
                        <?= $model->invest_type == 4 ? '$(".three").hide();' : ''?>
                        $('[name="invest_type"]').on('change', function () {
                            if ($(this).val() === '4') {
                                $('.three').hide();
                            } else {
                                $('.three').show();
                            }
                        });
                    </script>
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
                <label class="col-sm-2 control-label">场站图片</label>
                <div class="col-sm-8">
                    <div class="images"></div>
                </div>
                <script>
                    uploadImg('.images', 'images', '<?=$model->images?>', false, 5);
                </script>
            </div>
            <div class="hr-line-dashed"></div>
            <div class="form-group">
                <label class="col-sm-2 control-label">场站介绍</label>
                <div class="col-sm-8">
                    <textarea class="form-control summernote" name="intro"></textarea>
                </div>
                <script>
                    $('.summernote').summernote({lang: "zh-CN", height: 500});
                    $('.summernote').summernote('code', `<?=$model->intro?>`);
                </script>
            </div>
            <div class="hr-line-dashed"></div>
            <div class="form-group">
                <label class="col-sm-2 control-label">备案文件</label>
                <div class="col-sm-8">
                    <div class="record_file"></div>
                </div>
                <script>
                    uploadFile('.record_file', 'record_file', '<?=$model->record_file?>');
                </script>
            </div>
            <div class="hr-line-dashed"></div>
            <div class="form-group">
                <label class="col-sm-2 control-label">场地合同</label>
                <div class="col-sm-8">
                    <div class="field_contract"></div>
                </div>
                <script>
                    uploadImg('.field_contract', 'field_contract', '<?=$model->field_contract?>', false, 3);
                </script>
            </div>
            <div class="hr-line-dashed"></div>
            <div class="form-group">
                <label class="col-sm-2 control-label">场站证明</label>
                <div class="col-sm-8">
                    <div class="field_prove"></div>
                </div>
                <script>
                    uploadImg('.field_prove', 'field_prove', '<?=$model->field_prove?>', false, 3);
                </script>
            </div>
            <div class="hr-line-dashed"></div>
            <div class="form-group">
                <label class="col-sm-2 control-label">施工图纸</label>
                <div class="col-sm-8">
                    <div class="field_drawing"></div>
                </div>
                <script>
                    uploadImg('.field_drawing', 'field_drawing', '<?=$model->field_drawing?>', false, 3);
                </script>
            </div>
            <div class="hr-line-dashed"></div>
            <div class="form-group">
                <label class="col-sm-2 control-label">变压器图纸</label>
                <div class="col-sm-8">
                    <div class="transformer_drawing"></div>
                </div>
                <script>
                    uploadImg('.transformer_drawing', 'transformer_drawing', '<?=$model->transformer_drawing?>', false, 3);
                </script>
            </div>
            <div class="hr-line-dashed"></div>
            <div class="form-group">
                <label class="col-sm-2 control-label">电力合同</label>
                <div class="col-sm-8">
                    <div class="power_contract"></div>
                </div>
                <script>
                    uploadImg('.power_contract', 'power_contract', '<?=$model->power_contract?>', false, 3);
                </script>
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
                <div class="col-sm-5 col-sm-offset-2">
                    <button class="btn btn-primary" type="submit">保存内容</button>
                    &emsp;
                    <button class="btn btn-primary toExamine" type="submit">提交审核</button>
                    &emsp;
                    <button class="btn btn-white back">返回</button>
                </div>
            </div>
        </form>
    </div>
</div>
<script>
    $('.toExamine').click(function () {
        $('[name="status"]').val('2');
    });
</script>