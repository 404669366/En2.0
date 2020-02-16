<?php $this->registerJsFile('@web/js/echarts.min.js', ['depends' => ['app\assets\ModelAsset']]) ?>
<div class="wrapper wrapper-content animated">
    <div class="row">
        <div class="col-sm-3">
            <div class="ibox">
                <div class="ibox-content">
                    <h5>累计充值</h5>
                    <h1 class="no-margins">&yen; <?= $invest['all'] ?></h1>
                </div>
            </div>
        </div>
        <div class="col-sm-3">
            <div class="ibox">
                <div class="ibox-content">
                    <h5>本年充值</h5>
                    <h1 class="no-margins">&yen; <?= $invest['year'] ?></h1>
                </div>
            </div>
        </div>
        <div class="col-sm-3">
            <div class="ibox">
                <div class="ibox-content">
                    <h5>本月充值</h5>
                    <h1 class="no-margins">&yen; <?= $invest['month'] ?></h1>
                </div>
            </div>
        </div>
        <div class="col-sm-3">
            <div class="ibox">
                <div class="ibox-content">
                    <h5>本日充值</h5>
                    <h1 class="no-margins">&yen; <?= $invest['day'] ?></h1>
                </div>
            </div>
        </div>
        <div class="col-sm-12">
            <div class="ibox" style="position: relative">
                <select class="form-control m-b year"
                        style="position: absolute;z-index: 999;width: 8%;right: 10px;top: 10px">
                    <?php foreach ($invest['years'] as $v): ?>
                        <option value="<?= $v ?>"><?= $v ?></option>
                    <?php endforeach; ?>
                </select>
                <div class="ibox-content" id="invest" style="height: 480px"></div>
            </div>
        </div>
        <div class="col-sm-12">
            <div class="ibox">
                <div class="ibox-content">
                    <h5 class="month-tit"></h5>
                    <table class="table table-striped table-bordered table-hover dataTable" id="table">
                        <thead>
                        <tr role="row">
                            <th>充值编号</th>
                            <th>充值用户</th>
                            <th>充值金额</th>
                            <th>当前余额</th>
                            <th>充值来源</th>
                            <th>创建时间</th>
                            <th>充值状态</th>
                        </tr>
                        </thead>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>
<script>
    var e = window.echarts.init(document.getElementById('invest'));
    e.setOption({
        title: {text: '充值统计'},
        color: ['#3398DB'],
        tooltip: {},
        xAxis: {
            type: 'category',
            data: ['01月', '02月', '03月', '04月', '05月', '06月', '07月', '08月', '09月', '10月', '11月', '12月']
        },
        yAxis: {type: 'value'},
        series: []
    });
    e.on('click', function (params) {
        showMonth($('.year').val() + '-' + params.name.replace('月', ''));
    });

    showInvest('');
    showMonth('<?= $month ?>');

    var table = myTable.baseShow({
        table: '#table',
        order: [5, 'desc'],
        columns: [
            {"data": "no"},
            {"data": "tel"},
            {"data": "money"},
            {"data": "balance"},
            {"data": "source"},
            {"data": "created_at"},
            {"data": "status"},
        ]
    });

    $('.year').change(function () {
        showInvest($(this).val());
    });

    function showInvest(year) {
        e.showLoading();
        $.getJSON('/finance/invest/report-data', {year: year}, function (re) {
            e.hideLoading();
            e.setOption({series: [{type: 'bar', data: re.data}]});
        });
    }

    function showMonth(month) {
        $('.month-tit').text('月充值列表(' + month + ')');
        $.getJSON('/finance/invest/month-data', {month: month}, function (re) {
            table.loadData(re.data);
        });
    }
</script>