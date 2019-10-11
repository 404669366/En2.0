<?php $this->registerJsFile('@web/js/geolocation.min.js', ['depends' => ['app\assets\ModelAsset']]) ?>
<?php $this->registerJsFile('@web/js/tencent.map.gl.min.js', ['depends' => ['app\assets\ModelAsset']]) ?>
<?php $this->registerJsFile('@web/js/modal.js', ['depends' => ['app\assets\ModelAsset']]) ?>
<style>
    body {
        height: 100%;
    }

    #map {
        width: 100%;
        height: 100%;
    }

    i {
        width: 1.4rem;
        text-align: center;
        margin-right: 0.2rem;
    }
</style>
<div id="map"></div>
<div class="html" style="display: none">
    <div style="padding: 1rem">
        <h4 style="font-size: 2.6rem;">awdawdwa</h4>
        <p style="font-size: 1.8rem"><i class="fa fa-phone" aria-hidden="true" style="width: 1.8rem"></i>qwedqdwasdawdawd</p>
        <p style="font-size: 1.8rem"><i class="fa fa-map-marker" style="width: 1.8rem"></i>qwedqdwasdawdawd</p>
    </div>
</div>
<script>
    var local = new qq.maps.Geolocation("LF3BZ-KUMCJ-RGZFW-F6HVB-AUIEJ-45BIO", "en-charge");
    local.getLocation(function (point) {

        var map = new TMap.Map("map", {
            zoom: 5,
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
                //var content = $('.html').html();
                var content =
                    '<div style="padding: 1rem">\n' +
                    '    <h4 style="font-size: 2.6rem;">' + field.name + '</h4>\n' +
                    '    <p style="font-size: 1.8rem"><i class="fa fa-phone" aria-hidden="true"></i>' + field.local + '</p>\n' +
                    '    <p style="font-size: 1.8rem"><i class="fa fa-map-marker"></i>' + field.address + '</p>\n' +
                    '</div>';
                window.modal({
                    title: '电站信息',
                    width: '80rem',
                    height: '40rem',
                    content: content,
                    callback: function (event) {
                        event.close();
                    }
                });
            }
        })
    });
</script>