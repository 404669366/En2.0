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
<script>
    var local = new qq.maps.CityService({
        complete: function (result) {

            var map = new qq.maps.Map(document.getElementById("map"), {
                center: result.detail.latLng,
                zoom: 7
            });

            var cluster = new qq.maps.MarkerCluster({map: map, markers: makeMarkers(JSON.parse(`<?=$data?>`))});

            function makeMarkers(points) {
                var markers = new qq.maps.MVCArray();
                for (var i = 0; i < points.length; i++) {
                    var maker = new qq.maps.Marker({data: points[i]});
                    maker.setPosition(new qq.maps.LatLng(points[i]['lat'], points[i]['lng']));
                    qq.maps.event.addListener(maker, 'click', function (evt) {
                        var field = evt.target.data;
                        $.getJSON('/oam/field/map-info', {no: field.no}, function (re) {
                            var content =
                                '<div class="box gray-bg">\n' +
                                '        <h4>' + field.name + '</h4>\n' +
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
</script>