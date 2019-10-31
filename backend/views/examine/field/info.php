<?php $this->registerCssFile('@web/css/summernote.css', ['depends' => ['app\assets\ModelAsset']]) ?>
<?php $this->registerCssFile('@web/css/summernote-bs4.css', ['depends' => ['app\assets\ModelAsset']]) ?>
<?php $this->registerJsFile('@web/js/summernote.js', ['depends' => ['app\assets\ModelAsset']]) ?>
<?php $this->registerJsFile('@web/js/summernote-zh-CN.js', ['depends' => ['app\assets\ModelAsset']]) ?>
<?php $this->registerJsFile('https://map.qq.com/api/js?v=2.exp&key=NZ7BZ-VWQHX-2XV4F-75J2W-UDF42-Q2BM2', ['depends' => ['app\assets\ModelAsset']]) ?>
<?php $this->registerJsFile('@web/js/upload.min.js', ['depends' => ['app\assets\ModelAsset']]) ?>
<style>
    table {
        width: 100%;
        line-height: 3rem;
        font-size: 1.4rem;
        text-align: center;
        border-color: silver;
        margin-bottom: 1rem;
    }
</style>
<div class="wrapper wrapper-content animated">
    <div class="ibox-content">
        <form method="post" class="form-horizontal">
            <div class="row">
                <div class="col-sm-6">
                    <div class="hr-line-dashed"></div>
                    <div class="form-group">
                        <label class="col-sm-4 control-label">场站编号</label>
                        <div class="col-sm-5">
                            <input type="text" class="form-control" readonly placeholder="<?= $model->no ?>">
                        </div>
                    </div>
                    <div class="hr-line-dashed"></div>
                    <div class="form-group">
                        <label class="col-sm-4 control-label">场站名称</label>
                        <div class="col-sm-5">
                            <input type="text" class="form-control" readonly placeholder="<?= $model->name ?>">
                        </div>
                    </div>
                    <div class="hr-line-dashed"></div>
                    <div class="form-group">
                        <label class="col-sm-4 control-label">场站特色</label>
                        <div class="col-sm-5">
                            <textarea class="form-control" readonly rows="6"><?= $model->getTrait() ?></textarea>
                        </div>
                    </div>
                </div>
                <div class="col-sm-6">
                    <div class="hr-line-dashed"></div>
                    <div class="form-group">
                        <label class="col-sm-3 control-label">场站标题</label>
                        <div class="col-sm-5">
                            <input type="text" class="form-control" readonly placeholder="<?= $model->title ?>">
                        </div>
                    </div>
                    <div class="hr-line-dashed"></div>
                    <div class="form-group">
                        <label class="col-sm-3 control-label">股权单价</label>
                        <div class="col-sm-5">
                            <input type="text" class="form-control" readonly value="<?= $model->univalence ?>">
                        </div>
                    </div>
                    <div class="hr-line-dashed"></div>
                    <div class="form-group">
                        <label class="col-sm-3 control-label">场站配置</label>
                        <div class="col-sm-5">
                            <textarea class="form-control" readonly rows="6"><?= $model->getConfig() ?></textarea>
                        </div>
                    </div>
                </div>
            </div>
            <div class="hr-line-dashed"></div>
            <div class="form-group">
                <label class="col-sm-2 control-label">股权分配</label>
                <div class="col-sm-8">
                    <table class="gunTable" border="1">
                        <thead>
                        <tr>
                            <td>股权编号</td>
                            <td>股权类型</td>
                            <td>股权用户</td>
                            <td>股权数量</td>
                        </tr>
                        </thead>
                        <tbody>
                        <?php foreach ($stock as $v): ?>
                            <tr>
                                <td><?= $v['no'] ?></td>
                                <td><?= $v['type'] ?></td>
                                <td><?= $v['key'] ?></td>
                                <td><?= $v['num'] ?></td>
                            </tr>
                        <?php endforeach; ?>
                        </tbody>
                    </table>
                    <small>* 项目股权总数为100股；此处分配剩余股权为本次项目平台融资股权；未配置此项表示平台融资100股；此项配置满100股审核过后场站状态自动变成融资完成且不进行平台融资；</small>
                </div>
            </div>
            <div class="hr-line-dashed"></div>
            <div class="form-group">
                <label class="col-sm-2 control-label">场站标点</label>
                <div class="col-sm-8">
                    <div id="map" style="height:30rem;"></div>
                </div>
                <script>
                    var map = new qq.maps.Map(document.getElementById("map"), {
                        center: new qq.maps.LatLng('<?=$model->lat?>', '<?=$model->lng?>'),
                        zoom: 14
                    });
                    var marker = new qq.maps.Marker({
                        map: map,
                        animation: qq.maps.MarkerAnimation.BOUNCE,
                        position: map.getCenter()
                    });
                </script>
            </div>
            <div class="hr-line-dashed"></div>
            <div class="form-group">
                <label class="col-sm-2 control-label">场站地址</label>
                <div class="col-sm-8">
                    <input type="text" class="form-control" readonly placeholder="<?= $model->address ?>">
                </div>
            </div>
            <div class="hr-line-dashed"></div>
            <div class="form-group">
                <label class="col-sm-2 control-label">场站图片</label>
                <div class="col-sm-8">
                    <div class="images"></div>
                </div>
                <script>
                    uploadImg('.images', 'images', '<?=$model->getImages()?>', true, 5);
                </script>
            </div>
            <div class="hr-line-dashed"></div>
            <div class="form-group">
                <label class="col-sm-2 control-label">场站介绍</label>
                <div class="col-sm-8">
                    <textarea class="form-control summernote"></textarea>
                </div>
                <script>
                    $('.summernote').summernote({lang: "zh-CN", height: 500});
                    $('.summernote').summernote('code', `<?=$model->getIntro()?>`);
                    $('.summernote').summernote('disable');
                </script>
            </div>
            <div class="hr-line-dashed"></div>
            <div class="form-group">
                <label class="col-sm-2 control-label">备案文件</label>
                <div class="col-sm-8">
                    <div class="record"></div>
                </div>
                <script>
                    uploadFile('.record', 'record', '<?=$model->record?>', true);
                </script>
            </div>
            <div class="hr-line-dashed"></div>
            <div class="form-group">
                <label class="col-sm-2 control-label">电力答复</label>
                <div class="col-sm-8">
                    <div class="reply"></div>
                </div>
                <script>
                    uploadImg('.reply', 'reply', '<?=$model->reply?>', true, 3);
                </script>
            </div>
            <div class="hr-line-dashed"></div>
            <div class="form-group">
                <label class="col-sm-2 control-label">备注</label>
                <div class="col-sm-8">
                    <textarea class="form-control" <?= $model->status == 2 ? 'name="remark"' : 'readonly' ?>
                              rows="10"><?= $model->getRemark() ?></textarea>
                </div>
            </div>
            <div class="hr-line-dashed"></div>
            <div class="form-group">
                <div class="col-sm-5 col-sm-offset-2">
                    <?php if ($model->status == 2): ?>
                        <input type="hidden" name="_csrf" value="<?= Yii::$app->request->csrfToken ?>">
                        <input type="hidden" class="stock" value="<?= $model->getStock() ?>">
                        <input type="hidden" name="status" data-status="<?= $model->status ?>" value="">
                        <button type="submit" class="btn btn-info pass">通过</button>
                        &emsp;
                        <button type="submit" class="btn btn-info noPass">驳回</button>
                        &emsp;
                    <?php endif; ?>
                    <button class="btn btn-white back">返回</button>
                </div>
            </div>
        </form>
    </div>
</div>
<script>
    $('.pass').click(function () {
        if ($('.stock').val() < 100) {
            $('[name="status"]').val(3);
        } else {
            $('[name="status"]').val(4);
        }
    });
    $('.noPass').click(function () {
        if ($('[name="remark"]').val()) {
            $('[name="status"]').val(1);
            return true;
        }
        showMsg('请填写备注');
        return false;
    });
</script>