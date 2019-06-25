<div class="wrapper wrapper-content animated">
    <div class="ibox-content">
        <form method="post" class="form-horizontal">
            <input type="hidden" name="_csrf" value="<?= Yii::$app->request->csrfToken ?>">
            <div class="hr-line-dashed"></div>
            <div class="form-group">
                <label class="col-sm-2 control-label">权限名称</label>
                <div class="col-sm-2">
                    <input type="text" class="form-control" name="name" value="<?= $model->name ?>">
                </div>
            </div>
            <div class="hr-line-dashed"></div>
            <div class="form-group">
                <label class="col-sm-2 control-label">权限路由</label>
                <div class="col-sm-2">
                    <input type="text" name="url" class="form-control" value="<?= $model->url ?>">
                </div>
            </div>
            <div class="hr-line-dashed"></div>
            <div class="form-group">
                <label class="col-sm-2 control-label">权限类型</label>
                <div class="col-sm-2">
                    <select class="form-control m-b" name="type">
                        <?php foreach ($types as $k => $v): ?>
                            <option value="<?= $k ?>" <?= $k == $model->type ? 'selected' : '' ?>><?= $v ?></option>
                        <?php endforeach; ?>
                    </select>
                </div>
            </div>
            <div class="hr-line-dashed"></div>
            <div class="form-group">
                <label class="col-sm-2 control-label">上级</label>
                <div class="col-sm-2">
                    <select class="form-control m-b" name="last_id">
                        <option value="0">—顶级—</option>
                        <?php foreach ($tops as $v): ?>
                            <option value="<?= $v['id'] ?>" <?= $v['id'] == $model->last_id ? 'selected' : '' ?>><?= $v['name'] ?></option>
                        <?php endforeach; ?>
                    </select>
                </div>
            </div>
            <div class="hr-line-dashed"></div>
            <div class="form-group">
                <label class="col-sm-2 control-label">排序</label>
                <div class="col-sm-2">
                    <input type="text" class="form-control" name="sort" value="<?= $model->sort ?>">
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