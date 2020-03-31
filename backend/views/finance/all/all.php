<?php $this->registerJsFile('@web/js/echarts.min.js', ['depends' => ['app\assets\ModelAsset']]) ?>
<div class="wrapper wrapper-content animated" id="head">
    <div class="row">
        <div class="col-sm-2" data-url="/finance/consume/report">
            <div class="ibox">
                <div class="ibox-content">
                    <h5>累计消费金额</h5>
                    <h4 class="no-margins">&yen; <?= $consume['all'] ?></h4>
                </div>
            </div>
        </div>
        <div class="col-sm-2" data-url="/finance/invest/report">
            <div class="ibox">
                <div class="ibox-content">
                    <h5>累计充值金额</h5>
                    <h4 class="no-margins">&yen; <?= $invest['all'] ?></h4>
                </div>
            </div>
        </div>
        <div class="col-sm-2" data-url="/finance/ele/list">
            <div class="ibox">
                <div class="ibox-content">
                    <h5>累计基础电费</h5>
                    <h4 class="no-margins">&yen; <?= $ele['all'] ?></h4>
                </div>
            </div>
        </div>
        <div class="col-sm-2" data-url="#e3">
            <div class="ibox">
                <div class="ibox-content">
                    <h5>累计充电次数</h5>
                    <h4 class="no-margins">次 <?= $times['all'] ?></h4>
                </div>
            </div>
        </div>
        <div class="col-sm-2" data-url="#e4">
            <div class="ibox">
                <div class="ibox-content">
                    <h5>累计充电电量</h5>
                    <h4 class="no-margins">kwh <?= $charge['all'] ?></h4>
                </div>
            </div>
        </div>
        <div class="col-sm-2">
            <div class="ibox">
                <div class="ibox-content">
                    <h5>累计用户数量</h5>
                    <h4 class="no-margins">个 <?= $user['all'] ?></h4>
                </div>
            </div>
        </div>
        <div class="col-sm-2" data-url="/finance/consume/report">
            <div class="ibox">
                <div class="ibox-content">
                    <h5>今日消费金额</h5>
                    <h4 class="no-margins">&yen; <?= $consume['today'] ?></h4>
                </div>
            </div>
        </div>
        <div class="col-sm-2" data-url="/finance/invest/report">
            <div class="ibox">
                <div class="ibox-content">
                    <h5>今日充值金额</h5>
                    <h4 class="no-margins">&yen; <?= $invest['today'] ?></h4>
                </div>
            </div>
        </div>
        <div class="col-sm-2" data-url="/finance/ele/list">
            <div class="ibox">
                <div class="ibox-content">
                    <h5>今日基础电费</h5>
                    <h4 class="no-margins">&yen; <?= $ele['today'] ?></h4>
                </div>
            </div>
        </div>
        <div class="col-sm-2" data-url="#e3">
            <div class="ibox">
                <div class="ibox-content">
                    <h5>今日充电次数</h5>
                    <h4 class="no-margins">次 <?= $times['today'] ?></h4>
                </div>
            </div>
        </div>
        <div class="col-sm-2" data-url="#e4">
            <div class="ibox">
                <div class="ibox-content">
                    <h5>今日充电电量</h5>
                    <h4 class="no-margins">kwh <?= $charge['today'] ?></h4>
                </div>
            </div>
        </div>
        <div class="col-sm-2">
            <div class="ibox">
                <div class="ibox-content">
                    <h5>今日新增用户</h5>
                    <h4 class="no-margins">个 <?= $user['today'] ?></h4>
                </div>
            </div>
        </div>
        <div class="col-sm-12">
            <div class="ibox" style="position: relative">
                <div class="ibox-content" id="e1" style="height: 480px"></div>
            </div>
        </div>
        <div class="col-sm-12">
            <div class="ibox" style="position: relative">
                <div class="ibox-content" id="e2" style="height: 480px"></div>
            </div>
        </div>
        <div class="col-sm-12">
            <div class="ibox" style="position: relative">
                <div class="ibox-content" id="e3" style="height: 480px"></div>
            </div>
        </div>
        <div class="col-sm-12">
            <div class="ibox" style="position: relative">
                <div class="ibox-content" id="e4" style="height: 480px"></div>
            </div>
        </div>
    </div>
</div>
<script>
    var consume = JSON.parse(`<?=$consume['report']?>`);
    window.echarts.init(document.getElementById('e1')).setOption({
        title: {text: '当月消费走势'},
        color: ['#3398DB'],
        tooltip: {
            trigger: 'axis',
            axisPointer: {
                type: 'line'
            }
        },
        xAxis: {
            type: 'category',
            data: consume.days,
            axisTick: {
                alignWithLabel: true
            }
        },
        yAxis: {
            type: 'value'
        },
        series: [{
            data: consume.data,
            type: 'line',
            smooth: true
        }]
    });
    var invest = JSON.parse(`<?=$invest['report']?>`);
    window.echarts.init(document.getElementById('e2')).setOption({
        title: {text: '当月充值走势'},
        color: ['#3398DB'],
        tooltip: {
            trigger: 'axis',
            axisPointer: {
                type: 'line'
            }
        },
        xAxis: {
            type: 'category',
            data: invest.days,
            axisTick: {
                alignWithLabel: true
            }
        },
        yAxis: {
            type: 'value'
        },
        series: [{
            data: invest.data,
            type: 'line',
            smooth: true
        }]
    });
    var times = JSON.parse(`<?=$times['report']?>`);
    window.echarts.init(document.getElementById('e3')).setOption({
        title: {text: '累计24时充电次数分布'},
        color: ['#3398DB'],
        tooltip: {
            trigger: 'axis',
            axisPointer: {
                type: 'line'
            }
        },
        xAxis: {
            type: 'category',
            data: ['00:00', '01:00', '02:00', '03:00', '04:00', '05:00', '06:00', '07:00', '08:00', '09:00', '10:00', '11:00', '12:00', '13:00', '14:00', '15:00', '16:00', '17:00', '18:00', '19:00', '20:00', '21:00', '22:00', '23:00'],
            axisTick: {
                alignWithLabel: true
            }
        },
        yAxis: {
            type: 'value'
        },
        series: [{
            data: times,
            type: 'line',
            smooth: true,
            areaStyle: {
                color: new echarts.graphic.LinearGradient(0, 0, 0, 1, [{
                    offset: 0,
                    color: 'rgb(248,248,255)'
                }, {
                    offset: 1,
                    color: 'rgb(51, 152, 219)'
                }])
            },
        }]
    });
    var charge = JSON.parse(`<?=$charge['report']?>`);
    window.echarts.init(document.getElementById('e4')).setOption({
        title: {text: '累计24时充电电量分布'},
        color: ['#3398DB'],
        tooltip: {
            trigger: 'axis',
            axisPointer: {
                type: 'line'
            }
        },
        xAxis: {
            type: 'category',
            data: ['00:00', '01:00', '02:00', '03:00', '04:00', '05:00', '06:00', '07:00', '08:00', '09:00', '10:00', '11:00', '12:00', '13:00', '14:00', '15:00', '16:00', '17:00', '18:00', '19:00', '20:00', '21:00', '22:00', '23:00'],
            axisTick: {
                alignWithLabel: true
            }
        },
        yAxis: {
            type: 'value'
        },
        series: [{
            data: charge,
            type: 'line',
            smooth: true,
            areaStyle: {
                color: new echarts.graphic.LinearGradient(0, 0, 0, 1, [{
                    offset: 0,
                    color: 'rgb(248,248,255)'
                }, {
                    offset: 1,
                    color: 'rgb(51, 152, 219)'
                }])
            },
        }]
    });
</script>