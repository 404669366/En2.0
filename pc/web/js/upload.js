document.write("<script src='/js/lrz/lrz.all.bundle.js' type='text/javascript' charset='utf-8'></script>");
document.write("<script src='/js/socket.io.js' type='text/javascript' charset='utf-8'></script>");

window.showUploadImg = function () {
    var html =
        '<div class="uploadImgPreBox">' +
        '   <div class="btns">' +
        '       <button type="button" class="uploadImgPreBoxClose">关闭</button>&emsp;' +
        '       <button type="button" class="uploadImgPreBoxDel">删除</button>' +
        '   </div>' +
        '   <span>' +
        '       <img src="">' +
        '   </span>' +
        '</div>';
    var handel = {
        show: function (src, name, readonly) {
            $('body').css('overflow-y', 'hidden').append(html);
            var delBtn = $('.uploadImgPreBoxDel');
            if (readonly) {
                delBtn.remove();
            } else {
                delBtn.data('src', src || '').data('name', name || '');
            }
            $('.uploadImgPreBox').find('img').prop('src', src || '');
        },
        hide: function () {
            $('body').css('overflow-y', 'auto');
            $('.uploadImgPreBox').remove();
        }
    };
    if ($('body').data('showUploadImg') !== 'ready') {
        $('body')
            .data('showUploadImg', 'ready')
            .on('mousewheel', '.uploadImgPreBox', function () {
                var img = $(this).find('img')[0];
                var zoom = parseInt(img.style.zoom, 10) || 100;
                zoom += event.wheelDelta / 4; //可适当修改
                if (zoom > 0) img.style.zoom = zoom + '%';
            })
            .on('click', '.uploadImgPreBoxClose', function () {
                $('body').css('overflow-y', 'auto');
                $('.uploadImgPreBox').remove();
            })
            .on('click', '.uploadImgPreBoxDel', function () {
                var btn = $(this);
                $.getJSON('/basis/file/delete.html', {src: btn.data('src')}, function (re) {
                    if (re.type) {
                        $('[name="' + btn.data('name') + '"]').val(function (i, val) {
                            val = val.split(',');
                            var newVal = [];
                            $.each(val, function (k, v) {
                                if (v !== btn.data('src')) {
                                    newVal.push(v);
                                }
                            });
                            return newVal.join(',');
                        });
                        $('[src="' + btn.data('src') + '"]').remove();
                        handel.hide();
                        window.showMsg('图片删除成功');
                    } else {
                        handel.hide();
                        window.showMsg('图片删除失败');
                    }
                })
            });
    }
    return handel;
};

window.progress = function () {
    var html =
        '<div class="upload-progress" style="position: fixed;top: 0;left: 0;z-index:99;height: 100%;width: 100%;display: table">' +
        '   <span style="display: table-cell;vertical-align: middle;">' +
        '       <div style="width: 40rem;height: 1rem;margin:0 auto;border-radius: 0.5rem;background: url(/img/blue.png) #f3f3f3 repeat-y;background-size:0% auto"></div>' +
        '   </span>' +
        '</div>';
    return {
        'bind': function (key) {
            $('body').css('overflow-y', 'hidden').append(html);
            var socket = io('http://127.0.0.1:2120');
            socket.on('connect', function () {
                socket.emit('bind', key);
            });
            socket.on('msg', function (msg) {
                var data = JSON.parse(msg);
                $('.upload-progress>span>div').css('background-size', (data.uploaded / data.uploadSize * 100) + '% auto');
                if (data.uploaded && data.uploadSize && data.uploaded >= data.uploadSize) {
                    $('body').css('overflow-y', 'auto');
                    $('.upload-progress').remove();
                    socket.close();
                }
            });
        },
        'close': function () {
            $('body').css('overflow-y', 'auto');
            $('.upload-progress').remove();
        }
    }
};

window.uploadImg = function (node, name, def, readonly, max) {
    node = node || '';
    name = name || '';
    def = def || '';
    max = max || 10;
    var body = $('body');
    if (!body.find('.fileInput').length) {
        body
            .append('<input type="file" accept=".jpg,.png,.gif" class="fileInput" style="display: none"/>')
            .on('click', '.addImg', function () {
                var thisNode = $(this).data('node');
                if ($(thisNode).find('.uploadImgOne:not(.addImg)').length + 1 > max) {
                    showMsg('最多上传' + max + '张图片');
                    return;
                }
                body.find('.fileInput').data('node', thisNode).data('name', $(this).data('name')).click();
            })
            .on('change', '.fileInput', function () {
                var thisNode = $(this).data('node');
                var thisName = $(this).data('name');
                var file = $(this)[0].files[0];
                lrz(file, {quality: 0.5}).then(function (re) {
                    file = new File([re.file], file.name, {type: file.type});
                    var formData = new FormData();
                    formData.append('file', file);
                    $.ajax({
                        url: '/basis/file/upload.html',
                        type: 'POST',
                        data: formData,
                        processData: false,
                        contentType: false,
                        beforeSend: function () {
                            window.progress().bind(file.name);
                        },
                        success: function (re) {
                            re = JSON.parse(re);
                            if (re.type) {
                                $(thisNode).find('.addImg').before('<img class="uploadImgOne" data-readonly="" data-name="' + thisName + '" src="' + re.data + '"/>');
                                $('[name="' + thisName + '"]').val(function (i, val) {
                                    if (val) {
                                        return val + ',' + re.data;
                                    }
                                    return re.data;
                                });
                                $('.fileInput').val('');
                            } else {
                                window.showMsg("图片上传失败");
                            }
                        },
                        error: function () {
                            $('.fileInput').val('');
                            window.progress().close();
                            window.showMsg("图片上传失败");
                        }
                    });
                }).catch(function (err) {
                    window.showMsg('图片压缩失败');
                });
            })
            .on('click', '.uploadImgOne:not(.addImg)', function () {
                window.showUploadImg().show($(this).prop('src'), $(this).data('name'), $(this).data('readonly'));
            });
    }

    $(node)
        .addClass('uploadImgBox')
        .after('<input type="hidden" name="' + name + '" value="' + def + '"/>');
    if (!readonly) {
        $(node).append('<img class="uploadImgOne addImg" data-node="' + node + '" data-name="' + name + '" src="/img/addImg.jpg"/>');
    }
    if (def) {
        $.each(def.split(','), function (k, v) {
            $(node).find('.addImg').before('<img class="uploadImgOne" data-readonly="' + readonly + '" data-name="' + name + '" src="' + v + '"/>');
        });
    }
};
