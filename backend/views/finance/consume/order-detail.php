<?php $this->registerJsFile('@web/js/echarts.min.js', ['depends' => ['app\assets\ModelAsset']]) ?>
<div class="wrapper wrapper-content animated">
    <div class="row">
        <h3 class="col-sm-12">
            <?= $no ?>订单详情
            <a href="/finance/consume/field-report?no=<?= $fno ?>#head" class="btn btn-sm btn-white">返回</a>
        </h3>
    </div>
</div>
<script>
</script>