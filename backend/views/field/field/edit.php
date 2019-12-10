<?php $this->registerCssFile('@web/css/summernote.css', ['depends' => ['app\assets\ModelAsset']]) ?>
<?php $this->registerCssFile('@web/css/summernote-bs4.css', ['depends' => ['app\assets\ModelAsset']]) ?>
<?php $this->registerJsFile('@web/js/summernote.js', ['depends' => ['app\assets\ModelAsset']]) ?>
<?php $this->registerJsFile('@web/js/summernote-zh-CN.js', ['depends' => ['app\assets\ModelAsset']]) ?>
<?php $this->registerJsFile('https://map.qq.com/api/js?v=2.exp&key=NZ7BZ-VWQHX-2XV4F-75J2W-UDF42-Q2BM2', ['depends' => ['app\assets\ModelAsset']]) ?>
<?php $this->registerJsFile('@web/js/oss.js', ['depends' => ['app\assets\ModelAsset']]) ?>
<?php $this->registerJsFile('@web/js/modal.js', ['depends' => ['app\assets\ModelAsset']]) ?>
<style>
    table {
        width: 100%;
        line-height: 3rem;
        font-size: 1.4rem;
        text-align: center;
        border-color: silver;
        margin-bottom: 1rem;
    }

    .stockInput {
        width: 100%;
        height: 4rem;
        padding: 0 1%;
        line-height: 4rem;
        font-size: 1.6rem;
        text-align: center;
        text-align-last: center;
    }
</style>
<div class="wrapper wrapper-content animated">
    <div class="ibox-content">
        <form method="post" class="form-horizontal">
            <input type="hidden" name="_csrf" value="<?= Yii::$app->request->csrfToken ?>">
            <input type="hidden" name="status" value="1">
            <?php if ($model->user): ?>
                <div class="hr-line-dashed"></div>
                <div class="form-group">
                    <label class="col-sm-2 control-label">发布用户</label>
                    <div class="col-sm-8">
                        <input type="text" class="form-control" placeholder="<?= $model->user->tel ?>" readonly>
                    </div>
                </div>
            <?php endif; ?>
            <div class="row">
                <div class="col-sm-6">
                    <div class="hr-line-dashed"></div>
                    <div class="form-group">
                        <label class="col-sm-4 control-label">场站编号</label>
                        <div class="col-sm-5">
                            <?php if ($model->no): ?>
                                <input type="text" class="form-control no" name="no" value="<?= $model->no ?>" readonly>
                            <?php else: ?>
                                <input type="text" class="form-control no" name="no"
                                       value="<?= \vendor\project\helpers\Helper::createNo('F') ?>" readonly>
                            <?php endif; ?>
                        </div>
                    </div>
                    <div class="hr-line-dashed"></div>
                    <div class="form-group">
                        <label class="col-sm-4 control-label">场站名称</label>
                        <div class="col-sm-5">
                            <input type="text" class="form-control" name="name" value="<?= $model->name ?>">
                        </div>
                    </div>
                    <div class="hr-line-dashed"></div>
                    <div class="form-group">
                        <label class="col-sm-4 control-label">场站特色</label>
                        <div class="col-sm-5">
                            <textarea class="form-control" name="trait" rows="6"><?= $model->getTrait() ?></textarea>
                        </div>
                    </div>
                </div>
                <div class="col-sm-6">
                    <div class="hr-line-dashed"></div>
                    <div class="form-group">
                        <label class="col-sm-3 control-label">场站标题</label>
                        <div class="col-sm-5">
                            <input type="text" class="form-control" name="title" value="<?= $model->title ?>">
                        </div>
                    </div>
                    <div class="hr-line-dashed"></div>
                    <div class="form-group">
                        <label class="col-sm-3 control-label">股权单价</label>
                        <div class="col-sm-5">
                            <input type="text" class="form-control" name="univalence"
                                   value="<?= $model->univalence ?: 0 ?>">
                        </div>
                    </div>
                    <div class="hr-line-dashed"></div>
                    <div class="form-group">
                        <label class="col-sm-3 control-label">场站配置</label>
                        <div class="col-sm-5">
                            <textarea class="form-control" name="config" rows="6"><?= $model->getConfig() ?></textarea>
                        </div>
                    </div>
                </div>
            </div>
            <div class="hr-line-dashed"></div>
            <div class="form-group">
                <label class="col-sm-2 control-label">股权分配</label>
                <div class="col-sm-8">
                    <table class="stockTable" border="1">
                        <thead>
                        <tr>
                            <td>股权编号</td>
                            <td>股权类型</td>
                            <td>股权用户</td>
                            <td>股权数量</td>
                            <td>股权操作</td>
                        </tr>
                        </thead>
                        <tbody>
                        <?php foreach ($stock as $v): ?>
                            <tr>
                                <td><?= $v['no'] ?></td>
                                <td><?= $v['type'] ?></td>
                                <td><?= $v['key'] ?></td>
                                <td><?= $v['num'] ?></td>
                                <td>
                                    <button type="button" data-no="<?= $v['no'] ?>"
                                            class="btn btn-sm btn-danger delStock">删除
                                    </button>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                        <tr>
                            <td>----</td>
                            <td>----</td>
                            <td>----</td>
                            <td>----</td>
                            <td>
                                <button type="button" class="btn btn-sm btn-info addStock">添加</button>
                            </td>
                        </tr>
                        </tbody>
                    </table>
                    <small>* 项目股权总数为100股；此处分配剩余股权为本次项目平台融资股权；未配置此项表示平台融资100股；此项配置满100股审核过后场站状态自动变成融资完成且不进行平台融资；</small>
                </div>
            </div>
            <div class="hr-line-dashed"></div>
            <div class="form-group">
                <label class="col-sm-2 control-label">场站标点</label>
                <div class="col-sm-8">
                    <div id="map" style="height: 30rem"></div>
                    <input type="hidden" name="lat" value="<?= $model->lat ?>">
                    <input type="hidden" name="lng" value="<?= $model->lng ?>">
                </div>
                <script>
                    var lat = $('[name="lat"]');
                    var lng = $('[name="lng"]');
                    var local = new qq.maps.CityService({
                        complete: function (result) {
                            var map = new qq.maps.Map(document.getElementById("map"), {
                                center: result.detail.latLng,
                                zoom: 14
                            });
                            var marker = new qq.maps.Marker({map: map, animation: qq.maps.MarkerAnimation.BOUNCE});
                            if (lat.val() && lng.val()) {
                                map.setCenter(new qq.maps.LatLng(lat.val(), lng.val()));
                                marker.setPosition(map.getCenter());
                            }
                            var geocoder = new qq.maps.Geocoder();
                            geocoder.setComplete(function (result) {
                                result = result.detail.addressComponents;
                                var address = result.province + result.city + result.district + (result.streetNumber || result.street);
                                $('[name="address"]').val(address);
                            });
                            qq.maps.event.addListener(map, 'click', function (event) {
                                geocoder.getAddress(event.latLng);
                                marker.setMap(map);
                                marker.setPosition(event.latLng);
                                lat.val(event.latLng.getLat());
                                lng.val(event.latLng.getLng());
                            });
                        },
                        error: function (evt) {
                            window.showMsg('获取当前定位失败');
                        }
                    });
                    local.searchLocalCity();
                </script>
            </div>
            <div class="hr-line-dashed"></div>
            <div class="form-group">
                <label class="col-sm-2 control-label">场站地址</label>
                <div class="col-sm-8">
                    <input type="text" class="form-control" name="address" value="<?= $model->address ?>">
                </div>
            </div>
            <div class="hr-line-dashed"></div>
            <div class="form-group">
                <label class="col-sm-2 control-label">场站图片</label>
                <div class="col-sm-8">
                    <input type="file" class="form-control f" accept="image/*" style="margin-bottom: 1rem">
                    <input type="hidden" name="images" value="<?= $model->getImages() ?>">
                    <div class="images fre"></div>
                </div>
                <script>
                    window.preview.make('.fre', '<?=$model->getImages()?>', 'uploadPre');
                    $('.f').change(function () {
                        if ($('[name="images"]').val().split(',').length >= 5) {
                            $('.f').val('');
                            window.showMsg('最多不超过5张');
                            return;
                        }
                        window.oss.upload($(this)[0].files[0], function (src) {
                            $('.fre').append('<img class="uploadPre" data-input="images" src="' + src + '"/>');
                            $('[name="images"]').val(function (i, v) {
                                if (v) {
                                    return v + ',' + src;
                                }
                                return src;
                            });
                            $('.f').val('');
                        })
                    });
                </script>
            </div>
            <div class="hr-line-dashed"></div>
            <div class="form-group">
                <label class="col-sm-2 control-label">场站介绍</label>
                <div class="col-sm-8">
                    <textarea class="form-control summernote" name="intro"></textarea>
                </div>
                <script>
                    $('.summernote').summernote({lang: "zh-CN", height: 400});
                    $('.summernote').summernote('code', `<?=$model->getIntro()?>`);
                </script>
            </div>
            <div class="hr-line-dashed"></div>
            <div class="form-group">
                <label class="col-sm-2 control-label">备案文件</label>
                <div class="col-sm-8 recordBox">
                    <input type="file" class="form-control re"
                           accept="application/msword,application/pdf,application/vnd.openxmlformats-officedocument.wordprocessingml.document"
                           style="margin-bottom: 1rem">
                    <div class="rebtns" style="display: none">
                        <button type="button" class="btn btn-sm btn-info look">查看</button>&emsp;
                        <button type="button" class="btn btn-sm btn-danger del">删除</button>
                    </div>
                    <input type="hidden" name="record" value="<?= $model->record ?>">
                </div>
                <script>
                    var record = '<?=$model->record?>';
                    if (record) {
                        $('.rebtns').show();
                        $('.re').hide();
                    }
                    $('.re').change(function () {
                        window.oss.upload($(this)[0].files[0], function (src) {
                            record = src;
                            $('.rebtns').show();
                            $('.re').val('').hide();
                            $('[name="record"]').val(function (i, v) {
                                window.oss.remove(v);
                                return src;
                            });
                        });
                    });
                    $('.recordBox').on('click', '.look', function () {
                        if (record.indexOf('.pdf') >= 0) {
                            window.open(record);
                        } else {
                            window.open('https://view.officeapps.live.com/op/embed.aspx?src=' + record);
                        }
                    });
                    $('.recordBox').on('click', '.del', function () {
                        window.oss.remove(record);
                        $('[name="record"]').val('');
                        $('.rebtns').hide();
                        $('.re').show();
                        window.showMsg('删除成功');
                        record = '';
                    })
                </script>
            </div>
            <div class="hr-line-dashed"></div>
            <div class="form-group">
                <label class="col-sm-2 control-label">电力答复</label>
                <div class="col-sm-8">
                    <input type="file" class="form-control r" accept="image/*" style="margin-bottom: 1rem">
                    <input type="hidden" name="reply" value="<?= $model->reply ?>">
                    <div class="images reply"></div>
                </div>
                <script>
                    window.preview.make('.reply', '<?=$model->reply?>', 'uploadPre');
                    $('.r').change(function () {
                        if ($('[name="reply"]').val().split(',').length >= 3) {
                            $('.r').val('');
                            window.showMsg('最多不超过3张');
                            return;
                        }
                        window.oss.upload($(this)[0].files[0], function (src) {
                            $('.reply').append('<img class="uploadPre" data-input="reply" src="' + src + '"/>');
                            $('[name="reply"]').val(function (i, v) {
                                if (v) {
                                    return v + ',' + src;
                                }
                                return src;
                            });
                            $('.r').val('');
                        })
                    });
                </script>
            </div>
            <div class="hr-line-dashed"></div>
            <div class="form-group">
                <label class="col-sm-2 control-label">备注</label>
                <div class="col-sm-8">
                    <textarea class="form-control" name="remark" rows="10"><?= $model->getRemark() ?></textarea>
                </div>
            </div>
            <div class="hr-line-dashed"></div>
            <div class="form-group">
                <div class="col-sm-5 col-sm-offset-2">
                    <button class="btn btn-primary" type="submit">保存内容</button>
                    &emsp;
                    <button class="btn btn-primary toExamine" type="submit">提交审核</button>
                    &emsp;
                    <a class="btn btn-white" href="/field/field/list">返回</a>
                </div>
            </div>
        </form>
    </div>
</div>
<script>
    $('.toExamine').click(function () {
        $('[name="status"]').val(2);
    });
    $('.addStock').on('click', function () {
        var content = '<input type="hidden" name="field" value="' + $('.no').val() + '"/>';
        content += '<select name="type" class="stockInput" style="padding: 0 42.08%;box-sizing: border-box;">';
        <?php foreach ($stockType as $k=>$v):?>
        content += '<option value="<?=$k?>"><?=$v?></option>';
        <?php endforeach;?>
        content += '</select>';
        content += '<input type="text" name="key" class="stockInput" value="" placeholder="绑定用户(平台,企业无需绑定)"/>';
        content += '<input type="text" name="num" class="stockInput" value="" placeholder="股权数量"/>';
        window.modal({
            title: '股权分配',
            width: '30rem',
            height: 'auto',
            content: content,
            callback: function (event) {
                var data = {
                    field: event.find('[name="field"]').val(),
                    type: event.find('[name="type"]').val(),
                    key: event.find('[name="key"]').val(),
                    num: event.find('[name="num"]').val()
                };
                $.getJSON('/field/field/stock-add', data, function (re) {
                    if (re.type) {
                        var stock = '<tr>';
                        stock += '<td>' + re.data.no + '</td>';
                        stock += '<td>' + re.data.type + '</td>';
                        stock += '<td>' + re.data.key + '</td>';
                        stock += '<td>' + re.data.num + '</td>';
                        stock += '<td><button type="button" data-no="' + re.data.no + '" class="btn btn-sm btn-danger delStock">删除</button></td>';
                        stock += '</tr>';
                        $('.addStock').parents('tr').before(stock);
                        event.close();
                        showMsg('股权添加成功');
                    } else {
                        showMsg(re.msg);
                    }
                })
            }
        });
    });
    $('.stockTable').on('click', '.delStock', function () {
        var btn = $(this);
        $.getJSON('/field/field/stock-del', {no: btn.data('no')}, function (re) {
            if (re.type) {
                btn.parents('tr').remove();
                showMsg('股权删除成功');
            } else {
                showMsg(re.msg);
            }
        })
    })
</script>