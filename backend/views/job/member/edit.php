<div class="wrapper wrapper-content animated">
    <div class="ibox-content">
        <form method="post" class="form-horizontal">
            <input type="hidden" name="_csrf" value="<?= Yii::$app->request->csrfToken ?>">
            <div class="hr-line-dashed"></div>
            <div class="form-group">
                <label class="col-sm-2 control-label">手机号</label>
                <div class="col-sm-8">
                    <input type="text" class="form-control" name="tel" value="<?= $model->tel ?>">
                </div>
            </div>
            <div class="hr-line-dashed"></div>
            <div class="form-group">
                <label class="col-sm-2 control-label">账户密码</label>
                <div class="col-sm-8">
                    <input type="text" class="form-control" name="passwordA" value="" placeholder="填写则修改,不填则不改(最少6位)">
                </div>
            </div>
            <div class="hr-line-dashed"></div>
            <div class="form-group">
                <label class="col-sm-2 control-label">归属公司</label>
                <div class="col-sm-8">
                    <select class="form-control" name="company_id">
                        <option value="">--请选择--</option>
                        <?php foreach ($company as $v): ?>
                            <option <?= $model->company_id == $v['id'] ? 'selected' : '' ?>
                                    value="<?= $v['id'] ?>"><?= $v['name'] ?></option>
                        <?php endforeach; ?>
                    </select>
                </div>
            </div>
            <div class="hr-line-dashed"></div>
            <div class="form-group">
                <label class="col-sm-2 control-label">员工职位</label>
                <div class="col-sm-8">
                    <select class="form-control" name="job_id">
                        <?php foreach ($job as $v): ?>
                            <option <?= $model->job_id == $v['id'] ? 'selected' : '' ?>
                                    value="<?= $v['id'] ?>" title="<?= $v['remark'] ?>"><?= $v['name'] ?></option>
                        <?php endforeach; ?>
                    </select>
                </div>
                <script>
                    $('[name="company_id"]').on('change', function () {
                        $('[name="job_id"]').html('');
                        $.getJSON('/job/member/get-jobs', {company_id: $(this).val()}, function (re) {
                            if (re.type) {
                                var str = '';
                                $.each(re.data, function (k, v) {
                                    str += '<option value="' + v.id + '" title="' + v.remark + '">' + v.name + '</option>';
                                });
                                $('[name="job_id"]').html(str);
                            }
                        })
                    })
                </script>
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