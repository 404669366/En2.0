<?php $this->registerJsFile('/js/qrCode.js', ['depends' => 'app\assets\ModelAsset']); ?>
<style>
    .qrcode > canvas {
        width: 190px;
        height: 190px;
        display: block;
        margin: 8px auto;
    }
</style>
<div class="wrapper wrapper-content animated">
    <div class="ibox-content">
        <div class="form-horizontal">
            <div class="row">
                <div class="col-sm-4">
                    <div class="hr-line-dashed"></div>
                    <div class="form-group">
                        <label class="col-sm-3 control-label">电桩编号 :</label>
                        <div class="col-sm-8">
                            <input type="text" class="form-control pile" placeholder="请填写电桩编号" value="">
                        </div>
                    </div>
                </div>
                <div class="col-sm-4">
                    <div class="hr-line-dashed"></div>
                    <div class="form-group">
                        <label class="col-sm-3 control-label">枪口数量 :</label>
                        <div class="col-sm-8">
                            <input type="number" class="form-control num" placeholder="请填写枪口数量" value="0">
                        </div>
                    </div>
                </div>
                <div class="col-sm-4">
                    <div class="hr-line-dashed"></div>
                    <div class="form-group">
                        <div class="col-sm-8 col-sm-offset-1">
                            <button class="btn btn-primary to" type="button">开始制作</button>
                        </div>
                    </div>
                </div>
                <div class="col-sm-12">
                    <div class="hr-line-dashed"></div>
                    <div class="form-group">
                        <label class="col-sm-1 control-label">二 维 码 :</label>
                        <div class="col-sm-10">
                            <div class="row box"></div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<script>

    $('.to').click(function () {
        var pile = $('.pile').val();
        var num = $('.num').val();
        if (!pile) {
            window.showMsg('请填写电桩编号');
            return false;
        }
        if (num > 0) {
            window.showMsg('请填写枪口数量');
            return false;
        }
        $('.box').html('');
        for (var i = 1; i <= num; i++) {
            $('.box').append('<div class="col-sm-2 qrcode" id="qrcode' + i + '"></div>');
            $('#qrcode' + i).qrcode({
                width: 320,
                height: 320,
                text: 'http://c.en.ink/c/c/c.html?n=' + pile + '-' + i,
                src: '/img/logo.jpg',
                imgWidth: 80,
                imgHeight: 80,
                content: 'NO: ' + pile + '-' + i,
                contentSize: 18
            });
        }
    });
</script>