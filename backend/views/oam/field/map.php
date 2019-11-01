<?php $this->registerJsFile('@web/js/geolocation.min.js', ['depends' => ['app\assets\ModelAsset']]) ?>
<?php $this->registerJsFile('@web/js/tencent.map.gl.min.js', ['depends' => ['app\assets\ModelAsset']]) ?>
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
<div class="html" style="display: none">
</div>
<script>
    var local = new qq.maps.Geolocation("LF3BZ-KUMCJ-RGZFW-F6HVB-AUIEJ-45BIO", "en-charge");
    local.getLocation(function (point) {

        var map = new TMap.Map("map", {
            zoom: 7,
            center: new TMap.LatLng(point.lat, point.lng),
            mapStyleId: "style1"
        });

        var dot = new TMap.visualization.Dot({
            styles: {
                circle: {
                    type: "circle",
                    fillColor: "#0058e7",
                    strokeColor: "#FFFFFF",
                    strokeWidth: 2,
                    radius: 8
                }
            },
            faceTo: "screen",

        }).addTo(map);

        dot.setData(JSON.parse(`<?=$data?>`));

        dot.on("click", function (evt) {
            if (evt.detail.dot) {
                var field = evt.detail.dot;
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
            }
        })
    });
</script>