<?php $this->registerCssFile('@web/css/bootstrap-treeview.css', ['depends' => ['app\assets\ModelAsset']]); ?>
<?php $this->registerJsFile('@web/js/bootstrap-treeview.js', ['depends' => ['app\assets\ModelAsset']]); ?>
<div class="wrapper wrapper-content animated">
    <div class="ibox-content">
        <form method="post" class="form-horizontal">
            <input type="hidden" name="_csrf" value="<?= Yii::$app->request->csrfToken ?>">
            <div class="hr-line-dashed"></div>
            <div class="form-group">
                <label class="col-sm-2 control-label">用户名</label>
                <div class="col-sm-2">
                    <input type="text" class="form-control" name="username" value="">
                </div>
            </div>
            <div class="hr-line-dashed"></div>
            <div class="form-group">
                <label class="col-sm-2 control-label">Tel</label>
                <div class="col-sm-2">
                    <input type="text" class="form-control" name="tel" value="">
                </div>
            </div>
            <div class="hr-line-dashed"></div>
            <div class="form-group">
                <label class="col-sm-2 control-label">密码</label>
                <div class="col-sm-2">
                    <input type="text" class="form-control" name="passwordA" value="" placeholder="密码最少6位">
                </div>
            </div>
            <div class="hr-line-dashed"></div>
            <div class="form-group">
                <label class="col-sm-2 control-label">职位</label>
                <input type="hidden" name="job_id" value="">
                <div class="col-sm-3 treeview"></div>
                <script>
                    $('.treeview').treeview({
                        color: "#428bca",
                        data: '<?=$jobs?>',
                        onNodeSelected: function (event, node) {
                            $('[name="job_id"]').val(node.id);
                        }
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