<?php $this->registerJsFile('@web/js/echarts.min.js', ['depends' => ['app\assets\ModelAsset']]) ?>
<style>
    .year {
        position: absolute;
        z-index: 999;
        width: 8%;
        right: 10px;
        top: 10px;
    }
</style>
<div class="wrapper wrapper-content animated">
    <div class="row">
        <h3 class="col-sm-12">
            <?= $no ?>电站股东统计
            <a href="/finance/stock/list" class="btn btn-sm btn-white">返回</a>
        </h3>
        <div class="col-sm-12">
            <div class="ibox">
                <div class="ibox-content" id="pie" style="height: 480px"></div>
            </div>
        </div>
        <div class="col-sm-12">
            <div class="ibox" style="position: relative">
                <select class="form-control m-b year">
                    <?php foreach ($years as $v): ?>
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
                            <th>订单编号</th>
                            <th>场站信息</th>
                            <th>消费信息</th>
                            <th>结算金额</th>
                            <th>创建时间</th>
                        </tr>
                        </thead>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>
<script>
    var sno = '';
    var name = '';
    var pie = window.echarts.init(document.getElementById('pie'), 'dark');
    var year = window.echarts.init(document.getElementById('year'));
    var month = window.echarts.init(document.getElementById('month'));
    pie.setOption({
        title: {
            text: '股权构成',
            left: 'center',
            top: 20
        },
        tooltip: {
            trigger: 'item',
            formatter: '股权 : {c} ({d}%)'
        },
        legend: {
            orient: 'vertical',
            left: 10,
            top: 10
        },
        series: [
            {
                type: 'pie',
                radius: ['50%', '70%'],
                label: {
                    normal: {
                        show: false,
                        position: 'center'
                    },
                    emphasis: {
                        show: true,
                        textStyle: {
                            fontSize: '20',
                            fontWeight: 'bold'
                        }
                    }
                },
                data: JSON.parse(`<?=$data?>`)
            }
        ]
    });
    year.setOption({
        title: {text: '年收益统计'},
        color: ['#3398DB'],
        tooltip: {},
        xAxis: {
            type: 'category',
            data: ['01月', '02月', '03月', '04月', '05月', '06月', '07月', '08月', '09月', '10月', '11月', '12月']
        },
        yAxis: {type: 'value'},
        series: [{type: 'bar', data: []}]
    });
    month.setOption({
        title: {text: '月收益统计'},
        color: ['#3398DB'],
        tooltip: {},
    });
    var table = myTable.baseShow({
        table: '#table',
        order: [4, 'desc'],
        columns: [
            {"data": "order"},
            {"data": "info2"},
            {"data": "info1"},
            {"data": "money"},
            {"data": "created_at"},
        ]
    });

    pie.on('click', function (params) {
        sno = params.data.no;
        name = params.data.name;
        month.setOption({
            title: {text: '月收益统计'},
            xAxis: {
                type: 'category',
                data: []
            },
            yAxis: {type: 'value'},
            series: [{type: 'bar', data: []}]
        });
        table.loadData([]);
        year.showLoading();
        $.getJSON('/finance/stock/year-data', {sno: sno, year: $('.year').val()}, function (re) {
            year.hideLoading();
            year.setOption({title: {text: name + $('.year').val() + '年收益统计'}, series: [{type: 'bar', data: re.data}]});
        });
    });
    $('.year').change(function () {
        month.setOption({
            title: {text: '月收益统计'},
            xAxis: {
                type: 'category',
                data: []
            },
            yAxis: {type: 'value'},
            series: [{type: 'bar', data: []}]
        });
        table.loadData([]);
        year.showLoading();
        $.getJSON('/finance/stock/year-data', {sno: sno, year: $(this).val()}, function (re) {
            year.hideLoading();
            year.setOption({title: {text: name + $('.year').val() + '年收益统计'}, series: [{type: 'bar', data: re.data}]});
        });
    });
    var monthTime = '';
    year.on('click', function (params) {
        table.loadData([]);
        monthTime = params.name.replace('月', '');
        month.showLoading();
        $.getJSON('/finance/stock/month-data', {sno: sno, year: $('.year').val(), month: monthTime}, function (re) {
            month.hideLoading();
            month.setOption({
                title: {text: name + $('.year').val() + '年' + params.name + '收益统计'},
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
        $.getJSON('/finance/stock/day-data', {sno: sno, date: date}, function (re) {
            table.loadData(re.data);
        });
    });
</script>