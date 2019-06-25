<div class="wrapper wrapper-content animated">
    <div class="ibox-content">
        <form method="post" class="form-horizontal">
            <input type="hidden" name="_csrf" value="<?= Yii::$app->request->csrfToken ?>">
            <div class="hr-line-dashed"></div>
            <div class="form-group">
                <label class="col-sm-2 control-label">旧密码</label>
                <div class="col-sm-2">
                    <input type="password" class="form-control" name="password">
                </div>
            </div>
            <div class="hr-line-dashed"></div>
            <div class="form-group">
                <label class="col-sm-2 control-label">新密码</label>
                <div class="col-sm-2">
                    <input type="password" class="form-control" name="newPasswordA" placeholder="请输入新密码(最少6位)">
                </div>
            </div>
            <div class="hr-line-dashed"></div>
            <div class="form-group">
                <label class="col-sm-2 control-label">新密码</label>
                <div class="col-sm-2">
                    <input type="password" class="form-control" name="newPasswordB" value="" placeholder="请再次输入新密码">
                </div>
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