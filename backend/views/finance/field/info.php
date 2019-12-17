<?php $this->registerJsFile('@web/js/echarts.min.js', ['depends' => ['app\assets\ModelAsset']]) ?>
<div class="wrapper wrapper-content animated">
    <div class="row">
        <h3 class="col-sm-12"><?= $no ?>电站消费统计 <a href="/finance/field/list" class="btn btn-sm btn-white">返回</a></h3>
        <div class="col-sm-3">
            <div class="ibox">
                <div class="ibox-content">
                    <h5>累计充电消费</h5>
                    <h1 class="no-margins">&yen; <?= $data['all'] ?></h1>
                </div>
            </div>
        </div>
        <div class="col-sm-3">
            <div class="ibox">
                <div class="ibox-content">
                    <h5>本年充电消费</h5>
                    <h1 class="no-margins">&yen; <?= $data['year'] ?></h1>
                </div>
            </div>
        </div>
        <div class="col-sm-3">
            <div class="ibox">
                <div class="ibox-content">
                    <h5>本月充电消费</h5>
                    <h1 class="no-margins">&yen; <?= $data['month'] ?></h1>
                </div>
            </div>
        </div>
        <div class="col-sm-3">
            <div class="ibox">
                <div class="ibox-content">
                    <h5>本日充电消费</h5>
                    <h1 class="no-margins">&yen; <?= $data['day'] ?></h1>
                </div>
            </div>
        </div>
        <div class="col-sm-12">
            <div class="ibox" style="position: relative">
                <select class="form-control m-b year"
                        style="position: absolute;z-index: 999;width: 8%;right: 10px;top: 10px">
                    <?php foreach ($data['years'] as $v): ?>
                        <option value="<?= $v ?>"><?= $v ?></option>
                    <?php endforeach; ?>
                </select>
                <div class="ibox-content" id="e" style="height: 480px"></div>
            </div>
        </div>
        <div class="col-sm-12">
            <div class="ibox">
                <div class="ibox-content">
                    <h5 class="month-tit"></h5>
                    <table class="table table-striped table-bordered table-hover dataTable" id="table">
                        <thead>
                        <tr role="row">
                            <th>订单编号</th>
                            <th>电桩编号</th>
                            <th>充电枪口</th>
                            <th>充电用户</th>
                            <th>充电电量</th>
                            <th>费用信息</th>
                            <th>创建时间</th>
                            <th>订单状态</th>
                        </tr>
                        </thead>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>
<script>
    var no = '<?=$no?>';
    var e = window.echarts.init(document.getElementById('e'));
    e.setOption({
        title: {text: '月消费统计'},
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

    showE('');
    showMonth('<?= $month ?>');
    var table = myTable.baseShow({
        table: '#table',
        order: [6, 'desc'],
        columns: [
            {"data": "no"},
            {"data": "pile"},
            {"data": "gun"},
            {"data": "tel"},
            {"data": "e"},
            {"data": "info"},
            {"data": "created_at"},
            {"data": "status"},
        ]
    });

    $('.year').change(function () {
        showE($(this).val());
    });

    function showE(year) {
        e.showLoading();
        $.getJSON('/finance/field/info-data', {year: year, no: no}, function (re) {
            e.hideLoading();
            e.setOption({series: [{type: 'bar', data: re.data}]});
        });
    }

    function showMonth(month) {
        $('.month-tit').text('月订单列表(' + month + ')');
        $.getJSON('/finance/field/month-data', {month: month, no: no}, function (re) {
            table.loadData(re.data);
        });
    }
</script>