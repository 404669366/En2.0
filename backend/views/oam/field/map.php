<?php $this->registerJsFile('@web/js/geolocation.min.js', ['depends' => ['app\assets\ModelAsset']]) ?>
<?php $this->registerJsFile('@web/js/tencent.map.gl.min.js', ['depends' => ['app\assets\ModelAsset']]) ?>
<style>
    body {
        height: 100%;
    }

    #map {
        width: 100%;
        height: 100%;
    }
</style>
<div id="map"></div>
<script>
    var local = new qq.maps.Geolocation("LF3BZ-KUMCJ-RGZFW-F6HVB-AUIEJ-45BIO", "en-charge");
    local.getLocation(function (point) {

        var map = new TMap.Map("map", {
            zoom: 8,
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
                    radius: 5
                }
            },
            faceTo: "screen",

        }).addTo(map);

        dot.setData(JSON.parse(`<?=$data?>`));

        dot.on("click", function (evt) {
            if (evt.detail.dot) {
                console.log(evt);
            }
        })
    });
</script>