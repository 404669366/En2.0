<?php $this->registerJsFile('@web/js/echarts.min.js', ['depends' => ['app\assets\ModelAsset']]) ?>
<div class="wrapper wrapper-content animated">
    <div class="row">
        <div class="col-sm-3">
            <div class="ibox">
                <div class="ibox-content mbox">
                    <h5 class="CFF009B">累计充值</h5>
                    <h1 class="no-margins">&yen; <?= $invest['all'] ?></h1>
                </div>
            </div>
        </div>
        <div class="col-sm-3">
            <div class="ibox">
                <div class="ibox-content mbox">
                    <h5 class="CFF00FF">本年充值</h5>
                    <h1 class="no-margins">&yen; <?= $invest['year'] ?></h1>
                </div>
            </div>
        </div>
        <div class="col-sm-3">
            <div class="ibox">
                <div class="ibox-content mbox">
                    <h5 class="C9B00FF">本月充值</h5>
                    <h1 class="no-margins">&yen; <?= $invest['month'] ?></h1>
                </div>
            </div>
        </div>
        <div class="col-sm-3">
            <div class="ibox">
                <div class="ibox-content mbox">
                    <h5 class="C0000FF">本日充值</h5>
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
                <div class="ibox-content" id="year" style="height: 480px"></div>
            </div>
        </div>
        <div class="col-sm-12">
            <div class="ibox" style="position: relative">
                <div class="ibox-content" id="month" style="height: 480px"></div>
            </div>
        </div>
        <div class="col-sm-12">
            <div class="ibox">
                <div class="ibox-content">
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
    var year = window.echarts.init(document.getElementById('year'));
    year.setOption({
        title: {text: '年充值统计'},
        color: ['#3398DB'],
        tooltip: {},
        xAxis: {
            type: 'category',
            data: ['01月', '02月', '03月', '04月', '05月', '06月', '07月', '08月', '09月', '10月', '11月', '12月']
        },
        yAxis: {type: 'value'},
        series: [{type: 'bar', data: JSON.parse(`<?=$data?>`)}]
    });
    var month = window.echarts.init(document.getElementById('month'));
    month.setOption({
        title: {text: '月充值统计'},
        color: ['#3398DB'],
        tooltip: {},
    });
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
        month.setOption({
            xAxis: {
                type: 'category',
                data: []
            },
            yAxis: {type: 'value'},
            series: [{type: 'bar', data: []}]
        });
        table.loadData([]);
        year.showLoading();
        $.getJSON('/finance/invest/year-data', {year: $(this).val()}, function (re) {
            year.hideLoading();
            year.setOption({series: [{type: 'bar', data: re.data}]});
        });
    });

    var monthTime = '';
    year.on('click', function (params) {
        table.loadData([]);
        monthTime = params.name.replace('月', '');
        month.showLoading();
        $.getJSON('/finance/invest/month-data', {year: $('.year').val(), month: monthTime}, function (re) {
            month.hideLoading();
            month.setOption({
                xAxis: {
                    type: 'category',
                    data: re.data.days
                },
                yAxis: {type: 'value'},
                series: [{type: 'bar', data: re.data.data}]
            });
        });
    });

    month.on('click', function (params) {
        var date = $('.year').val() + '-' + monthTime + '-' + params.name;
        $.getJSON('/finance/invest/day-data', {date: date}, function (re) {
            table.loadData(re.data);
        });
    });
</script>