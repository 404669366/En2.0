<style>
    .cf {
        width: 40vmin;
        height: 23vmin;
        margin: 15vmin auto 20vmin auto;
        position: relative;
    }

    .color {
        position: absolute;
        width: 100%;
        height: 100%;
        left: 0;
        top: 0;
        z-index: 98;
    }

    .mouse {
        position: absolute;
        width: 100%;
        height: 100%;
        left: 0;
        top: 0;
        z-index: 99;
        background: url("/img/mouse.png") no-repeat;
        background-size: 100.2% auto;
    }

    .slider {
        width: 60vmin;
        margin: 12vmin auto;
    }
</style>
<div class="cf">
    <div class="color"></div>
    <div class="mouse"></div>
</div>
<div id="s1" class="slider"></div>
<div id="s2" class="slider"></div>
<div id="s3" class="slider"></div>
<div id="s4" class="slider"></div>
<script>
    layui.use(['slider'], function() {
        var ratios = [0, 0, 0, 0];
        var face = new MIXCOLOR(
            [0, 3, 15, 2.5],
            [
                [0, 100, 38, 5],
                [0, 84, 26, 34],
                [0, 86, 87, 29],
                [0, 64, 100, 3]
            ],
            100
        );
        change(0, 0);
        layui.slider.render({
            elem: '#s1',
            theme: '#F20197',
            input: true ,
            tips: false,
            change: function(val) {
                change(0, val);
            }
        });
        layui.slider.render({
            elem: '#s2',
            theme: '#A81B7C',
            input: true ,
            tips: false,
            change: function(val) {
                change(1, val);
            }
        });
        layui.slider.render({
            elem: '#s3',
            theme: '#B61A18',
            input: true ,
            tips: false,
            change: function(val) {
                change(2, val);
            }
        });
        layui.slider.render({
            elem: '#s4',
            theme: '#F85A00',
            input: true ,
            tips: false,
            change: function(val) {
                change(3, val);
            }
        });

        function change(key, val) {
            ratios[key] = val / 100;
            var color = face.mix(ratios).mixToCMYK().toRGB();
            $('.color').css('background', 'rgb(' + color.r + ',' + color.g + ',' + color.b + ')')
        }
    });
</script>