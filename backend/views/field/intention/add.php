<?php $this->registerJsFile('@web/js/upload.js', ['depends' => ['app\assets\ModelAsset']]) ?>
<div class="wrapper wrapper-content animated">
    <div class="ibox-content">
        <form method="post" class="form-horizontal">
            <input type="hidden" name="_csrf" value="<?= Yii::$app->request->csrfToken ?>">
            <input type="hidden" name="commissioner_id" value="<?= Yii::$app->user->id ?>">
            <input type="hidden" name="source" value="2">
            <input type="hidden" name="created_at" value="<?= time() ?>">
            <input type="hidden" name="status" value="3">
            <div class="row">
                <div class="col-sm-6">
                    <div class="hr-line-dashed"></div>
                    <div class="form-group">
                        <label class="col-sm-4 control-label">绑定场站</label>
                        <div class="col-sm-5">
                            <div class="input-group">
                                <input type="text" class="form-control no" placeholder="填写场站编号完成场站绑定">
                                <span class="input-group-btn">
                            <button type="button" class="btn btn-primary searchField1">搜索</button>
                        </span>
                            </div>
                            <input type="hidden" name="field_id" value="0">
                        </div>
                        <script>
                            $('.searchField1').click(function () {
                                $('[name="field_id"]').val(0);
                                $.getJSON('/field/intention/field-search', {no: $('.no').val()}, function (re) {
                                    if (re.type) {
                                        $('[name="field_id"]').val(re.data);
                                        showMsg('场站绑定成功');
                                    } else {
                                        showMsg('场站不存在或状态错误,请重新输入场站编号进行绑定');
                                    }
                                })
                            });
                        </script>
                    </div>
                    <div class="hr-line-dashed"></div>
                    <div class="form-group">
                        <label class="col-sm-4 control-label">绑定用户</label>
                        <div class="col-sm-5">
                            <div class="input-group">
                                <input type="text" class="form-control tel" placeholder="填写用户手机号完成用户绑定">
                                <span class="input-group-btn">
                            <button type="button" class="btn btn-primary searchField2">搜索</button>
                        </span>
                            </div>
                            <input type="hidden" name="user_id" value="0">
                        </div>
                        <script>
                            $('.searchField2').click(function () {
                                $('[name="user_id"]').val(0);
                                $.getJSON('/field/field/user-search', {tel: $('.tel').val()}, function (re) {
                                    if (re.type) {
                                        $('[name="user_id"]').val(re.data);
                                        showMsg('用户绑定成功');
                                    } else {
                                        showMsg('用户不存在,请重新输入用户手机号进行绑定');
                                    }
                                })
                            });
                        </script>
                    </div>
                </div>
                <div class="col-sm-6">
                    <div class="hr-line-dashed"></div>
                    <div class="form-group">
                        <label class="col-sm-3 control-label">认购金额</label>
                        <div class="col-sm-5">
                            <input type="text" class="form-control" name="purchase_amount">
                        </div>
                    </div>
                    <div class="hr-line-dashed"></div>
                    <div class="form-group">
                        <label class="col-sm-3 control-label">分成比例</label>
                        <div class="col-sm-5">
                            <input type="text" class="form-control" name="part_ratio">
                        </div>
                    </div>
                </div>
            </div>
            <div class="hr-line-dashed"></div>
            <div class="form-group">
                <label class="col-sm-2 control-label">打款凭条</label>
                <div class="col-sm-8">
                    <div class="voucher"></div>
                </div>
                <script>
                    uploadImg('.voucher', 'voucher', '', false, 1);
                </script>
            </div>
            <div class="hr-line-dashed"></div>
            <div class="form-group">
                <label class="col-sm-2 control-label">意向合同</label>
                <div class="col-sm-8">
                    <div class="contract"></div>
                </div>
                <script>
                    uploadImg('.contract', 'contract', '', false, 4);
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
                    <button class="btn btn-primary" type="submit">提交审核</button>
                    &emsp;
                    <button class="btn btn-white back">返回</button>
                </div>
            </div>
        </form>
    </div>
</div>