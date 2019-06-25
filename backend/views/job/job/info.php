<?php $this->registerJsFile('@web/js/jquery.nestable.js', ['depends' => ['app\assets\ModelAsset']]) ?>
<?php $this->registerCssFile('@web/css/selectTree.css', ['depends' => ['app\assets\ModelAsset']]) ?>
<?php $this->registerJsFile('@web/js/selectTree.js', ['depends' => ['app\assets\ModelAsset']]) ?>
<div class="wrapper wrapper-content animated">
    <div class="ibox-content">
        <div class="row">
            <div class="col-md-8 col-sm-12">
                <div class="m-t-md">
                    <h5>
                        按住鼠标拖动排序&emsp;点击选项进行修改
                        &emsp;
                        <button type="button" class="btn btn-sm btn-info addJob">添加职位</button>
                    </h5>
                </div>
                <div class="dd" id="nestable2"><?= $relation ?></div>
            </div>
            <div class="col-md-4 col-sm-12">
                <div class="m-t-md">
                    <h5 class="jobTitle">添加职位</h5>
                </div>
                <form method="post" class="form-horizontal jobForm" action="/job/job/do">
                    <input type="hidden" name="_csrf" value="<?= Yii::$app->request->csrfToken ?>">
                    <div class="hr-line-dashed"></div>
                    <div class="form-group">
                        <label class="col-md-3 col-sm-12 control-label">职位名称</label>
                        <div class="col-md-6 col-sm-12">
                            <input type="text" class="form-control" name="job" value="">
                        </div>
                    </div>
                    <div class="hr-line-dashed"></div>
                    <div class="form-group">
                        <label class="col-md-3 col-sm-12 control-label">备注</label>
                        <div class="col-md-6 col-sm-12">
                            <textarea name="remark" class="form-control" rows="4"></textarea>
                        </div>
                    </div>
                    <div class="hr-line-dashed"></div>
                    <div class="form-group">
                        <label class="col-md-3 col-sm-12 control-label">Powers</label>
                        <input type="hidden" name="powers" value="">
                        <div class="col-md-6 col-sm-12 powersTree"></div>
                    </div>
                    <div class="hr-line-dashed"></div>
                    <div class="form-group">
                        <div class="col-md-4 col-md-offset-3 col-sm-8 col-sm-offset-2">
                            <button class="btn btn-primary" type="submit">保存</button>
                            &emsp;
                            <a class="btn btn-danger delJob" style="display: none">删除</a>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<script>
    $(function () {
        var powersData = JSON.parse('<?=json_encode($powers)?>');

        selectTree.tree({type: 1, wrapper: '.powersTree', data: powersData});

        $('.powersTree').on('click', '.f-treeList-radio', function () {
            var re = selectTree.treeJson('.powersTree');
            $('[name="powers"]').val(re.join(','));
        });

        $('.addJob').click(function () {
            $('.jobTitle').text('添加职位');
            $('.jobForm').prop('action', '/job/job/do');
            $('[name="job"]').val('');
            $('[name="remark"]').val('');
            $('[name="powers"]').val('');
            $('.delJob').hide();
            $('.powersTree').html('');
            selectTree.tree({type: 1, wrapper: '.powersTree', data: powersData, check: []});
        });

        $("#nestable2").on('mousedown', '.dd-handle', function () {
            $('.jobTitle').text('添加职位');
            $('.jobForm').prop('action', '/job/job/do');
            $('[name="job"]').val('');
            $('[name="remark"]').val('');
            $('[name="powers"]').val('');
            $('.delJob').hide();
            var id = $(this).parent('.dd-item').data('id');
            $.getJSON('/job/job/get-job', {id: id}, function (re) {
                if (re.type) {
                    $('.jobTitle').text('修改职位');
                    $('.jobForm').prop('action', '/job/job/do?id=' + id);
                    $('[name="job"]').val(re.data.job);
                    $('[name="remark"]').val(re.data.remark);
                    $('[name="powers"]').val(re.data.powers);
                    $('.powersTree').html('');
                    selectTree.tree({
                        type: 1,
                        wrapper: '.powersTree',
                        data: powersData,
                        check: re.data.powers.split(',')
                    });
                    $('.delJob').show().prop('href', '/job/job/del?id=' + id);
                } else {
                    showMsg(re.msg);
                }
            });
        }).nestable({group: 1}).on("change", function (e) {
            var list = e.length ? e : $(e.target);
            list = list.children('.dd-list');
            var map = {};
            $.getJSON('/job/job/save', {
                data: JSON.stringify(analysis(list, map)),
                map: JSON.stringify(map)
            }, function (re) {
                if (!re.type) {
                    showMsg(re.msg);
                }
            });
        });

        function analysis(nowList, map) {
            var thisData = [];
            var pid = nowList.parent('.dd-item');
            pid = pid.length ? pid.data('id') : 0;
            nowList.children('.dd-item').each(function (k, v) {
                thisData[k] = {
                    id: $(v).data('id'),
                    name: $(v).children('.dd-handle').text(),
                    pid: pid
                };
                if (map) {
                    map[$(v).data('id')] = {pid: pid, children: false};
                }
                var thisList = $(v).children('.dd-list');
                if (thisList.children('.dd-item').length) {
                    thisData[k]['children'] = analysis(thisList, map);
                    if (map) {
                        map[$(v).data('id')]['children'] = true;
                    }
                }
            });
            return thisData;
        }
    });
</script>