<?php $this->registerCssFile('@web/css/summernote.css', ['depends' => ['app\assets\ModelAsset']]) ?>
<?php $this->registerCssFile('@web/css/summernote-bs4.css', ['depends' => ['app\assets\ModelAsset']]) ?>
<?php $this->registerJsFile('@web/js/summernote.js', ['depends' => ['app\assets\ModelAsset']]) ?>
<?php $this->registerJsFile('@web/js/summernote-zh-CN.js', ['depends' => ['app\assets\ModelAsset']]) ?>
<?php $this->registerJsFile('@web/js/map.js', ['depends' => ['app\assets\ModelAsset']]) ?>
<?php $this->registerJsFile('@web/js/upload.js', ['depends' => ['app\assets\ModelAsset']]) ?>
<div class="wrapper wrapper-content animated">
    <div class="ibox-content">
        <form method="post" class="form-horizontal">
            <input type="hidden" name="_csrf" value="<?= Yii::$app->request->csrfToken ?>">
            <input type="hidden" name="commissioner_id" value="<?= Yii::$app->user->id ?>">
            <input type="hidden" name="source" value="2">
            <input type="hidden" name="created_at" value="<?= time() ?>">
            <input type="hidden" name="status" value="1">
            <div class="row">
                <div class="col-sm-6">
                    <div class="hr-line-dashed"></div>
                    <div class="form-group">
                        <label class="col-sm-4 control-label">场站编号</label>
                        <div class="col-sm-5">
                            <input type="text" class="form-control" name="no"
                                   value="<?= vendor\project\helpers\Helper::createNo('F') ?>" readonly>
                        </div>
                    </div>
                    <div class="hr-line-dashed"></div>
                    <div class="form-group">
                        <label class="col-sm-4 control-label">绑定用户</label>
                        <div class="col-sm-5">
                            <div class="input-group">
                                <input type="text" class="form-control tel" placeholder="填写用户手机号完成场地方绑定">
                                <span class="input-group-btn">
                                    <button type="button" class="btn btn-primary searchField">搜索</button>
                                </span>
                            </div>
                            <small>如为租用场地,不用绑定此项</small>
                            <input type="hidden" name="local_id" value="0">
                        </div>
                        <script>
                            $('.searchField').click(function () {
                                $('[name="local_id"]').val(0);
                                $.getJSON('/field/field/user-search', {tel: $('.tel').val()}, function (re) {
                                    if (re.type) {
                                        $('[name="local_id"]').val(re.data);
                                        showMsg('场地方绑定成功');
                                    } else {
                                        showMsg('用户不存在,请重新输入用户手机号进行绑定');
                                    }
                                })
                            });
                        </script>
                    </div>
                    <div class="hr-line-dashed"></div>
                    <div class="form-group">
                        <label class="col-sm-4 control-label">业务类型</label>
                        <div class="col-sm-5">
                            <select class="form-control" name="business_type">
                                <?php foreach (\vendor\project\helpers\Constant::businessType() as $k => $v): ?>
                                    <option value="<?= $k ?>"><?= $v ?></option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                    </div>
                    <div class="hr-line-dashed"></div>
                    <div class="form-group">
                        <label class="col-sm-4 control-label">投资类型</label>
                        <div class="col-sm-5">
                            <select class="form-control" name="invest_type">
                                <?php foreach (\vendor\project\helpers\Constant::investType() as $k => $v): ?>
                                    <option value="<?= $k ?>"><?= $v ?></option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                    </div>
                    <div class="hr-line-dashed"></div>
                    <div class="form-group">
                        <label class="col-sm-4 control-label">投资方分成占比</label>
                        <div class="col-sm-5">
                            <input type="text" class="form-control" name="invest_ratio">
                        </div>
                    </div>
                    <div class="hr-line-dashed"></div>
                    <div class="form-group">
                        <label class="col-sm-4 control-label">场地方分成占比</label>
                        <div class="col-sm-5">
                            <input type="text" class="form-control" name="field_ratio">
                            <small>如为租用场地,此项填写0</small>
                        </div>
                    </div>
                    <div class="hr-line-dashed"></div>
                    <div class="form-group">
                        <label class="col-sm-4 control-label">场站名称</label>
                        <div class="col-sm-5">
                            <input type="text" class="form-control" name="name">
                        </div>
                    </div>
                    <div class="hr-line-dashed"></div>
                    <div class="form-group">
                        <label class="col-sm-4 control-label">场站标题</label>
                        <div class="col-sm-5">
                            <input type="text" class="form-control" name="title">
                        </div>
                    </div>
                </div>
                <div class="col-sm-6">
                    <div class="hr-line-dashed"></div>
                    <div class="form-group">
                        <label class="col-sm-3 control-label">占地面积</label>
                        <div class="col-sm-5">
                            <input type="text" class="form-control" name="area">
                        </div>
                    </div>
                    <div class="hr-line-dashed"></div>
                    <div class="form-group">
                        <label class="col-sm-3 control-label">投建功率</label>
                        <div class="col-sm-5">
                            <input type="text" class="form-control" name="capacity">
                        </div>
                    </div>
                    <div class="hr-line-dashed"></div>
                    <div class="form-group">
                        <label class="col-sm-3 control-label">终端数量</label>
                        <div class="col-sm-5">
                            <input type="text" class="form-control" name="terminal">
                        </div>
                    </div>
                    <div class="hr-line-dashed"></div>
                    <div class="form-group">
                        <label class="col-sm-3 control-label">场站配置</label>
                        <div class="col-sm-5">
                            <textarea class="form-control" name="field_configure" rows="8"></textarea>
                        </div>
                    </div>
                    <div class="hr-line-dashed"></div>
                    <div class="form-group">
                        <label class="col-sm-3 control-label">预算金额</label>
                        <div class="col-sm-5">
                            <input type="text" class="form-control" name="budget_amount">
                        </div>
                    </div>
                    <div class="hr-line-dashed"></div>
                    <div class="form-group">
                        <label class="col-sm-3 control-label">起投金额</label>
                        <div class="col-sm-5">
                            <input type="text" class="form-control" name="lowest_amount">
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
                    map({element: 'myMap'});
                </script>
            </div>
            <div class="hr-line-dashed"></div>
            <div class="form-group">
                <label class="col-sm-2 control-label">场站图片</label>
                <div class="col-sm-8">
                    <div class="images"></div>
                </div>
                <script>
                    uploadImg('.images', 'images', '', false, 6);
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
                </script>
            </div>
            <div class="hr-line-dashed"></div>
            <div class="form-group">
                <label class="col-sm-2 control-label">备案文件</label>
                <div class="col-sm-8">
                    <div class="record_file"></div>
                </div>
                <script>
                    uploadFile('.record_file', 'record_file');
                </script>
            </div>
            <div class="hr-line-dashed"></div>
            <div class="form-group">
                <label class="col-sm-2 control-label">场地合同</label>
                <div class="col-sm-8">
                    <div class="field_contract"></div>
                </div>
                <script>
                    uploadImg('.field_contract', 'field_contract', '', false, 3);
                </script>
            </div>
            <div class="hr-line-dashed"></div>
            <div class="form-group">
                <label class="col-sm-2 control-label">场站证明</label>
                <div class="col-sm-8">
                    <div class="field_prove"></div>
                </div>
                <script>
                    uploadImg('.field_prove', 'field_prove', '', false, 3);
                </script>
            </div>
            <div class="hr-line-dashed"></div>
            <div class="form-group">
                <label class="col-sm-2 control-label">施工图纸</label>
                <div class="col-sm-8">
                    <div class="field_drawing"></div>
                </div>
                <script>
                    uploadImg('.field_drawing', 'field_drawing', '', false, 3);
                </script>
            </div>
            <div class="hr-line-dashed"></div>
            <div class="form-group">
                <label class="col-sm-2 control-label">变压器图纸</label>
                <div class="col-sm-8">
                    <div class="transformer_drawing"></div>
                </div>
                <script>
                    uploadImg('.transformer_drawing', 'transformer_drawing', '', false, 3);
                </script>
            </div>
            <div class="hr-line-dashed"></div>
            <div class="form-group">
                <label class="col-sm-2 control-label">电力合同</label>
                <div class="col-sm-8">
                    <div class="power_contract"></div>
                </div>
                <script>
                    uploadImg('.power_contract', 'power_contract', '', false, 3);
                </script>
            </div>
            <div class="hr-line-dashed"></div>
            <div class="form-group">
                <label class="col-sm-2 control-label">备注</label>
                <div class="col-sm-8">
                    <textarea class="form-control" name="remark" rows="10"></textarea>
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