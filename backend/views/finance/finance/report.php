<?php $this->registerJsFile('@web/js/echarts.min.js', ['depends' => ['app\assets\ModelAsset']]) ?>
<div class="wrapper wrapper-content animated">
    <div class="row">
        <div class="col-sm-3">
            <div class="ibox">
                <div class="ibox-content">
                    <h5>累计充值</h5>
                    <h1 class="no-margins">&yen; <?= $invest['allInvest'] ?></h1>
                </div>
            </div>
        </div>
        <div class="col-sm-3">
            <div class="ibox">
                <div class="ibox-content">
                    <h5>本年充值</h5>
                    <h1 class="no-margins">&yen; <?= $invest['yearInvest'] ?></h1>
                </div>
            </div>
        </div>
        <div class="col-sm-3">
            <div class="ibox">
                <div class="ibox-content">
                    <h5>本月充值</h5>
                    <h1 class="no-margins">&yen; <?= $invest['monthInvest'] ?></h1>
                </div>
            </div>
        </div>
        <div class="col-sm-3">
            <div class="ibox">
                <div class="ibox-content">
                    <h5>本日充值</h5>
                    <h1 class="no-margins">&yen; <?= $invest['dayInvest'] ?></h1>
                </div>
            </div>
        </div>
        <div class="col-sm-12">
            <div class="ibox" style="position: relative">
                <select class="form-control m-b investYear"
                        style="position: absolute;z-index: 999;width: 8%;right: 10px;top: 10px">
                    <?php foreach ($invest['years'] as $v): ?>
                        <option value="<?= $v ?>"><?= $v ?></option>
                    <?php endforeach; ?>
                </select>
                <div class="ibox-content" id="invest" style="height: 480px"></div>
            </div>
        </div>
        <div class="col-sm-3">
            <div class="ibox">
                <div class="ibox-content">
                    <h5>累计充电消费</h5>
                    <h1 class="no-margins">&yen; <?= $order['allOrder'] ?></h1>
                </div>
            </div>
        </div>
        <div class="col-sm-3">
            <div class="ibox">
                <div class="ibox-content">
                    <h5>本年充电消费</h5>
                    <h1 class="no-margins">&yen; <?= $order['yearOrder'] ?></h1>
                </div>
            </div>
        </div>
        <div class="col-sm-3">
            <div class="ibox">
                <div class="ibox-content">
                    <h5>本月充电消费</h5>
                    <h1 class="no-margins">&yen; <?= $order['monthOrder'] ?></h1>
                </div>
            </div>
        </div>
        <div class="col-sm-3">
            <div class="ibox">
                <div class="ibox-content">
                    <h5>本日充电消费</h5>
                    <h1 class="no-margins">&yen; <?= $order['dayOrder'] ?></h1>
                </div>
            </div>
        </div>
        <div class="col-sm-12">
            <div class="ibox" style="position: relative">
                <select class="form-control m-b orderYear"
                        style="position: absolute;z-index: 999;width: 8%;right: 10px;top: 10px">
                    <?php foreach ($order['years'] as $v): ?>
                        <option value="<?= $v ?>"><?= $v ?></option>
                    <?php endforeach; ?>
                </select>
                <div class="ibox-content" id="order" style="height: 480px"></div>
            </div>
        </div>
    </div>
</div>
<script>
    var e1 = window.echarts.init(document.getElementById('invest'));
    e1.setOption({
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

    showInvest('');
    $('.investYear').change(function () {
        showInvest($(this).val());
    });

    function showInvest(year) {
        e1.showLoading();
        $.getJSON('/finance/finance/invest-data', {year: year}, function (re) {
            e1.hideLoading();
            e1.setOption({series: [{type: 'bar', data: re.data}]});
        });
    }

    var e2 = window.echarts.init(document.getElementById('order'));
    e2.setOption({
        title: {text: '充电消费统计'},
        color: ['#3398DB'],
        tooltip: {},
        xAxis: {
            type: 'category',
            data: ['01月', '02月', '03月', '04月', '05月', '06月', '07月', '08月', '09月', '10月', '11月', '12月']
        },
        yAxis: {type: 'value'},
        series: []
    });

    showOrder('');
    $('.orderYear').change(function () {
        showOrder($(this).val());
    });

    function showOrder(year) {
        e2.showLoading();
        $.getJSON('/finance/finance/order-data', {year: year}, function (re) {
            e2.hideLoading();
            e2.setOption({series: [{type: 'bar', data: re.data}]});
        });
    }
</script>