<?php $this->registerJsFile('@web/js/lrz/lrz.all.bundle.js', ['depends' => ['app\assets\ModelAsset']]) ?>
<div class="wrapper wrapper-content animated">
    <div class="ibox-content">
        <div class="form-horizontal">
            <div class="hr-line-dashed"></div>
            <div class="form-group">
                <label class="col-sm-4 control-label">图片</label>
                <div class="col-sm-4">
                    <input type="file" accept=".jpg,.png,.gif" class="form-control file">
                </div>
            </div>
            <div class="hr-line-dashed"></div>
            <div class="form-group">
                <label class="col-sm-2 control-label">地址</label>
                <div class="col-sm-8" style="position: relative">
                    <input type="text" class="form-control url" value="" readonly>
                    <button class="btn btn-danger del" type="button" style="position: absolute;right: -4rem;top: 0;display: none">删除</button>
                </div>
            </div>
        </div>
    </div>
</div>
<script>
    $('.file').change(function () {
        if ($(this).val()) {
            $('.del').hide();
            var file = $(this)[0].files[0];
            lrz(file, {quality: 0.5}).then(function (re) {
                file = new File([re.file], file.name, {type: file.type});
                var formData = new FormData();
                formData.append('file', file);
                $.ajax({
                    url: '/basis/file/upload',
                    type: 'POST',
                    data: formData,
                    processData: false,
                    contentType: false,
                    success: function (re) {
                        re = JSON.parse(re);
                        if (re.type) {
                            showMsg("图片上传成功");
                            $('.url').val(re.data);
                            $('.del').show();
                        } else {
                            showMsg("图片上传失败");
                        }
                    }, error: function () {
                        showMsg("图片上传失败");
                    }
                });
            }).catch(function (err) {
                showMsg('图片压缩失败');
            });
        }
    });

    $('.del').click(function () {
        var val = $('.url').val();
        if (val) {
            $.getJSON('/basis/file/delete', {src: val}, function (re) {
                if (re.type) {
                    $('.file').val('');
                    $('.url').val('');
                    $('.del').hide();
                    showMsg('图片删除成功');
                } else {
                    showMsg('图片删除失败');
                }
            })
        }
    });
</script>