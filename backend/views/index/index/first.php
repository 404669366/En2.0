<?php $this->registerJsFile('https://map.qq.com/api/js?v=2.exp&key=NZ7BZ-VWQHX-2XV4F-75J2W-UDF42-Q2BM2', ['depends' => ['app\assets\ModelAsset']]) ?>
<?php $this->registerJsFile('@web/js/modal.js', ['depends' => ['app\assets\ModelAsset']]) ?>
<?php $this->registerJsFile('@web/js/jquery.peity.min.js', ['depends' => ['app\assets\ModelAsset']]) ?>
<style>
    body {
        height: 100%;
    }

    #map {
        width: 100%;
        height: 100%;
    }

    .search {
        position: fixed;
        top: 0.5rem;
        left: 50%;
        -webkit-transform: translate(-50%, 0);
        -o-transform: translate(-50%, 0);
        -moz-transform: translate(-50%, 0);
        transform: translate(-50%, 0);
        width: 36rem;
        height: 3rem;
        z-index: 888;
        padding: 0 0.36rem;
    }

    .box {
        padding: 1rem;
    }

    .box > h4 {
        font-size: 2.6rem;
    }

    .box > p {
        font-size: 1.8rem
    }

    .box > p > i {
        width: 1.4rem;
        text-align: center;
        margin-right: 0.2rem;
    }

    .box .ibox {
        margin-bottom: 10px;
    }

    .box .ibox > .ibox-content {
        padding: 10px;
    }
</style>
<div id="map"></div>
<input type="text" class="search" placeholder="回车搜索：编号/名称/地址/企业" value="<?= $key ?>">
<script>
    var field = {};
    $(document).keyup(function (event) {
        if (event.keyCode === 13) {
            window.location.href = '/index/index/first?key=' + $('.search').val();
        }
    });
    $('body')
        .on('click', '.up', function () {
            $.getJSON('/oam/field/up', {no: $(this).data('no')}, function (re) {
                window.showMsgDo(re.msg, 3, function () {
                    console.log(111);
                    makeModal();
                });
            });
        })
        .on('click', '.down', function () {
            $.getJSON('/oam/field/down', {no: $(this).data('no')}, function (re) {
                window.showMsgDo(re.msg, 3, function () {
                    console.log(222);
                    makeModal();
                });
            });
        });
    var local = new qq.maps.CityService({
        complete: function (result) {
            var map = new qq.maps.Map(document.getElementById("map"), {
                center: result.detail.latLng,
                zoom: 7
            });

            new qq.maps.MarkerCluster({map: map, markers: makeMarkers(JSON.parse(`<?=$data?>`))});

            function makeMarkers(points) {
                var markers = new qq.maps.MVCArray();
                for (var i = 0; i < points.length; i++) {
                    var maker = new qq.maps.Marker({data: points[i]});
                    maker.setPosition(new qq.maps.LatLng(points[i]['lat'], points[i]['lng']));
                    qq.maps.event.addListener(maker, 'click', function (evt) {
                        field = evt.target.data;
                        makeModal();
                    });
                    markers.push(maker);
                }
                return markers;
            }
        },
        error: function (evt) {
            window.showMsg('获取当前定位失败');
        }
    });
    local.searchLocalCity();

    function makeModal() {
        $.getJSON('/oam/field/map-info', {no: field.no}, function (re) {
            var btn = '';
            if (re.data.can == 1) {
                btn = ' <a href="/oam/field/pile?no=' + field.no + '" title="详情">>></a>';
                if (re.data.online === 0) {
                    btn += '<a style="float: right" class="btn btn-sm btn-info up" data-no="' + field.no + '">上线(待上线)</a>';
                }
                if (re.data.online === 1) {
                    btn += '<a style="float: right" class="btn btn-sm btn-danger down" data-no="' + field.no + '">下线(已上线)</a>';
                }
            }
            var content =
                '<div class="box gray-bg">\n' +
                '        <h4>' +
                field.name + '(' + field.abridge + ')' + btn +
                '        </h4>\n' +
                '        <p><i class="fa fa-map-marker"></i>' + field.address + '</p>\n' +
                '        <div class="row">\n' +
                '            <div class="col-sm-3">\n' +
                '                <div class="ibox">\n' +
                '                    <div class="ibox-content">\n' +
                '                        <h5>累计充电</h5>\n' +
                '                        <h1 class="no-margins">' + re.data.allCharge + 'kwh</h1>\n' +
                '                    </div>\n' +
                '                </div>\n' +
                '            </div>\n' +
                '            <div class="col-sm-3">\n' +
                '                <div class="ibox">\n' +
                '                    <div class="ibox-content">\n' +
                '                        <h5>累计消费</h5>\n' +
                '                        <h1 class="no-margins">' + re.data.allUse + '&yen;</h1>\n' +
                '                    </div>\n' +
                '                </div>\n' +
                '            </div>\n' +
                '            <div class="col-sm-3">\n' +
                '                <div class="ibox">\n' +
                '                    <div class="ibox-content">\n' +
                '                        <h5>成功充电</h5>\n' +
                '                        <h1 class="no-margins">' + re.data.useCount + '次</h1>\n' +
                '                    </div>\n' +
                '                </div>\n' +
                '            </div>\n' +
                '            <div class="col-sm-3">\n' +
                '                <div class="ibox">\n' +
                '                    <div class="ibox-content">\n' +
                '                        <h5>累计访问</h5>\n' +
                '                        <h1 class="no-margins">' + re.data.allCount + '次</h1>\n' +
                '                    </div>\n' +
                '                </div>\n' +
                '            </div>\n' +
                '            <div class="col-sm-12">\n' +
                '                <div class="ibox">\n' +
                '                    <div class="ibox-content">\n' +
                '                        <h5>近一年充电走向</h5>\n' +
                '                        <span class="chart">' + re.data.chart + '</span>\n' +
                '                    </div>\n' +
                '                </div>\n' +
                '            </div>\n' +
                '        </div>\n' +
                '    </div>';
            window.modal1({
                title: '电站信息',
                width: '80rem',
                height: 'auto',
                content: content,
                onLoad: function () {
                    $(".chart").peity("line", {
                        fill: "#1ab394",
                        stroke: "#169c81",
                        width: '100%',
                        height: '16rem'
                    });
                }
            });
        });
    }
</script>