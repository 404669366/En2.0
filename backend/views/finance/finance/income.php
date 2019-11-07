<?php $this->registerJsFile('@web/js/echarts.min.js', ['depends' => ['app\assets\ModelAsset']]) ?>
<div class="wrapper wrapper-content animated">
    <div class="row">
        <div class="col-sm-3">
            <div class="ibox">
                <div class="ibox-content">
                    <h5>累计收益</h5>
                    <h1 class="no-margins">&yen; <?= $income['allIncome'] ?></h1>
                </div>
            </div>
        </div>
        <div class="col-sm-3">
            <div class="ibox">
                <div class="ibox-content">
                    <h5>本年收益</h5>
                    <h1 class="no-margins">&yen; <?= $income['yearIncome'] ?></h1>
                </div>
            </div>
        </div>
        <div class="col-sm-3">
            <div class="ibox">
                <div class="ibox-content">
                    <h5>本月收益</h5>
                    <h1 class="no-margins">&yen; <?= $income['monthIncome'] ?></h1>
                </div>
            </div>
        </div>
        <div class="col-sm-3">
            <div class="ibox">
                <div class="ibox-content">
                    <h5>本日收益</h5>
                    <h1 class="no-margins">&yen; <?= $income['dayIncome'] ?></h1>
                </div>
            </div>
        </div>
        <div class="col-sm-12">
            <div class="ibox" style="position: relative">
                <select class="form-control m-b incomeYear"
                        style="position: absolute;z-index: 999;width: 8%;right: 10px;top: 10px">
                    <?php foreach ($income['years'] as $v): ?>
                        <option value="<?= $v ?>"><?= $v ?></option>
                    <?php endforeach; ?>
                </select>
                <div class="ibox-content" id="income" style="height: 480px"></div>
            </div>
        </div>
    </div>
</div>
<script>
    var e = window.echarts.init(document.getElementById('income'));
    e.setOption({
        title: {text: '收益统计'},
        color: ['#3398DB'],
        tooltip: {},
        xAxis: {
            type: 'category',
            data: ['01月', '02月', '03月', '04月', '05月', '06月', '07月', '08月', '09月', '10月', '11月', '12月']
        },
        yAxis: {type: 'value'},
        series: []
    });

    showIncome('');
    $('.incomeYear').change(function () {
        showIncome($(this).val());
    });

    function showIncome(year) {
        e.showLoading();
        $.getJSON('/finance/finance/income-data', {year: year}, function (re) {
            e.hideLoading();
            e.setOption({series: [{type: 'bar', data: re.data}]});
        });
    }
</script>