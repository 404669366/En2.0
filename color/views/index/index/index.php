<style>
    .color {
        width: 40vmin;
        height: 40vmin;
        margin: 20vmin auto;
    }

    .slider {
        width: 60vmin;
        margin: 8vmin auto;
    }
</style>
<div class="color"></div>
<div id="s1" class="slider"></div>
<div id="s2" class="slider"></div>
<div id="s3" class="slider"></div>
<div id="s4" class="slider"></div>
<script>
    layui.use(['slider'], function() {
        var ratios = [0, 0, 0, 0];
        var cmykset = new CMYKSET([
            [0, 100, 38, 5],
            [0, 84, 26, 34],
            [0, 86, 87, 29],
            [0, 64, 100, 3]
        ]);
        layui.slider.render({
            elem: '#s1',
            theme: '#F20197',
            setTips: function(val) {
                return val + '%';
            },
            change: function(val) {
                change(0, val);
            }
        });
        layui.slider.render({
            elem: '#s2',
            theme: '#A81B7C',
            setTips: function(val) {
                return val + '%';
            },
            change: function(val) {
                change(1, val);
            }
        });
        layui.slider.render({
            elem: '#s3',
            theme: '#B61A18',
            setTips: function(val) {
                return val + '%';
            },
            change: function(val) {
                change(2, val);
            }
        });
        layui.slider.render({
            elem: '#s4',
            theme: '#F85A00',
            setTips: function(val) {
                return val + '%';
            },
            change: function(val) {
                change(3, val);
            }
        });

        function change(key, val) {
            ratios[key] = val / 100;
            var color = cmykset.mix(ratios).toRGB();
            $('.color').css('background', 'rgb(' + color.r + ',' + color.g + ',' + color.b + ')')
        }
    });
</script>