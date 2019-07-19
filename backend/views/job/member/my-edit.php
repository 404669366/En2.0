<div class="wrapper wrapper-content animated">
    <div class="ibox-content">
        <form method="post" class="form-horizontal">
            <input type="hidden" name="_csrf" value="<?= Yii::$app->request->csrfToken ?>">
            <input type="hidden" name="company_id" value="<?= Yii::$app->user->identity->company_id ?>">
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
                <label class="col-sm-2 control-label">员工职位</label>
                <div class="col-sm-8">
                    <select class="form-control" name="job_id">
                        <?php foreach ($job as $v): ?>
                            <option <?= $model->job_id == $v['id'] ? 'selected' : '' ?>
                                    value="<?= $v['id'] ?>" title="<?= $v['remark'] ?>"><?= $v['name'] ?></option>
                        <?php endforeach; ?>
                    </select>
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