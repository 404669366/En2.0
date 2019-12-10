<?php $this->registerJsFile('@web/js/lrz/lrz.all.bundle.js', ['depends' => ['app\assets\ModelAsset']]) ?>
<?php $this->registerJsFile('@web/js/oss.js', ['depends' => ['app\assets\ModelAsset']]) ?>
<div class="wrapper wrapper-content animated">
    <div class="ibox-content">
        <div class="form-horizontal">
            <div class="hr-line-dashed"></div>
            <div class="form-group">
                <label class="col-sm-4 control-label">图片</label>
                <div class="col-sm-4">
                    <input type="file" accept="image/*" class="form-control file">
                </div>
            </div>
            <div class="hr-line-dashed"></div>
            <div class="form-group">
                <label class="col-sm-4 control-label">地址</label>
                <div class="col-sm-4" style="position: relative">
                    <input type="text" class="form-control url" value="" readonly>
                </div>
            </div>
            <div class="hr-line-dashed"></div>
            <div class="form-group">
                <label class="col-sm-4 control-label">操作</label>
                <div class="col-sm-4 do">
                    <button class="btn btn-danger del" type="button">删除</button>
                    &emsp;
                    <button class="btn btn-info copy" type="button">复制</button>
                </div>
            </div>
        </div>
    </div>
</div>
<script>
    $('.do').hide();
    $('.file').change(function () {
        $('.do').hide();
        if ($(this).val()) {
            var file = $(this)[0].files[0];
            lrz(file, {quality: 0.5}).then(function (re) {
                file = new File([re.file], file.name, {type: file.type});
                window.oss.upload(file, function (src) {
                    window.showMsg("图片上传成功");
                    $('.url').val(src);
                    $('.do').show();
                });
            }).catch(function (err) {
                window.showMsg('图片压缩失败');
            });
        }
        $(this).val('');
    });

    $('.del').click(function () {
        $.getJSON('/basis/file/delete', {src: $('.url').val().replace('https://ascasc.oss-cn-hangzhou.aliyuncs.com/', '')}, function (re) {
            $('.url').val('');
            $('.do').hide();
            window.showMsg('图片删除成功');
        })
    });

    $('.copy').click(function () {
        $('.url').select();
        document.execCommand("Copy");
        window.showMsg('地址复制成功');
    });
</script>