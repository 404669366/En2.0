$('body')
    .append("<style>.images {width: 100%;border: 2px dashed silver;padding: 0 10px 10px 0;min-height: calc(3.6rem + 20px)}.images > img {height: 3.6rem;margin: 10px 0 0 10px;width: calc(5% - 10px);}</style>")
    .append('<style>.imgPreBox{position: fixed;width: 100%;height: 100%;top: 0;left: 0;background: rgba(0,0,0,0.6);z-index: 999999;}</style>')
    .append('<style>.imgPreBox>img{position: absolute;top: 50%;left: 50%;-webkit-transform: translate(-50%, -50%);-o-transform: translate(-50%, -50%);-moz-transform: translate(-50%, -50%);transform: translate(-50%, -50%);}</style>')
    .append('<style>.imgPreBox>.btns{position: absolute;top: 1rem;right: 1rem;}</style>')
    .on('click', '.imgPre', function () {
        document.body.style.overflowY = 'hidden';
        $('body').append('<div class="imgPreBox"><div class="btns"><button type="button" class="btn btn-sm btn-warning imgPreClose">关闭</button></div><img src="' + $(this).prop('src') + '" style="zoom:50%;"></div>');
    })
    .on('click', '.uploadPre', function () {
        document.body.style.overflowY = 'hidden';
        $('body').append('<div class="imgPreBox"><div class="btns"><button type="button" class="btn btn-sm btn-warning imgPreClose">关闭</button>&emsp;<button type="button" data-src="' + $(this).prop('src') + '" data-input="' + $(this).data('input') + '" class="btn btn-sm btn-danger imgPreDel">删除</button></div><img src="' + $(this).prop('src') + '" style="zoom:50%;"></div>');
    })
    .on('click', '.imgPreDel', function () {
        var box = $(this).parents('.imgPreBox');
        var input = $(this).data('input');
        var src = $(this).data('src');
        var fileName = src.replace('https://ascasc.oss-cn-hangzhou.aliyuncs.com/', '');
        $.getJSON('/basis/file/delete', {src: fileName}, function (re) {
            document.body.style.overflowY = 'visible';
            box.remove();
            $('[src="' + src + '"]').remove();
            $('[name="' + input + '"]').val(function (i, val) {
                val = val.split(',');
                var newVal = [];
                $.each(val, function (k, v) {
                    if (v !== src) {
                        newVal.push(v);
                    }
                });
                return newVal.join(',');
            });
            window.showMsg('删除成功');
        });
    })
    .on('click', '.imgPreClose', function () {
        document.body.style.overflowY = 'visible';
        $(this).parents('.imgPreBox').remove();
    })
    .on('mousewheel', '.imgPreBox', function () {
        var img = $(this).find('img')[0];
        var zoom = parseInt(img.style.zoom, 10) || 100;
        zoom += event.wheelDelta / 4; //可适合修改
        if (zoom > 0) img.style.zoom = zoom + '%';
    });

window.preview = function () {
    return {
        make: function (node, src, cls) {
            if (src) {
                cls = cls || 'imgPre';
                var srcArr = src.split(',');
                $.each(srcArr, function (k, v) {
                    $(node).append('<img class="' + cls + '" src="' + v + '"/>');
                });
            }
        }
    };
}();
