$(function () {
    $('body').on('click', '.imgPre', function () {
        showImg();
        $('body').append('<div class="imgPreBox"><span><img src="' + $(this).prop('src') + '"></span></div>');
    }).on('click', '.imgPreBox', function () {
        hideImg();
        $(this).remove();
    }).on('mousewheel', '.imgPreBox', function () {
        var img = $(this).find('img')[0];
        var zoom = parseInt(img.style.zoom, 10) || 100;
        zoom += event.wheelDelta / 4; //可适合修改
        if (zoom > 0) img.style.zoom = zoom + '%';
    }).append('<style>.imgPreBox{position: fixed;width: 100%;height: 100%;top: 0;left: 0;display: table;background: rgba(0,0,0,0.6);z-index: 999999;}.imgPreBox>span{display: table-cell;vertical-align: middle;text-align: center;}</style>');

    var scrollTop;

    function showImg() {
        scrollTop = $(window).scrollTop();
        $('body').css({position: 'fixed', width: '100%'});
    }

    function hideImg() {
        $('body').css({position: '', width: ''});
        $(window).scrollTop(scrollTop);
    }
});
