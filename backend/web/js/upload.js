document.write("<script src='/js/lrz/lrz.all.bundle.js' type='text/javascript' charset='utf-8'></script>");
$('body')
    .append('<style>.uploadImgPreBox{position: fixed;width: 100%;height: 100%;top: 0;left: 0;display: table;background: rgba(0,0,0,0.6);z-index: 999999;}.uploadImgPreBox>span{display: table-cell;vertical-align: middle;text-align: center;}.uploadImgPreBox>.btns{position: absolute;right:1rem;top:1rem;}.uploadImgBox {width: 100%;border: 2px dashed silver;padding: 0 15px 15px 0;}.uploadImgBox > .uploadImgOne {height: calc(17rem - 30px);margin: 15px 0 0 15px;width: calc(20% - 15px);}.uploadImgBox > .addImg {padding: 10px;}</style>')
    .on('mousewheel', '.uploadImgPreBox', function () {
        var img = $(this).find('img')[0];
        var zoom = parseInt(img.style.zoom, 10) || 100;
        zoom += event.wheelDelta / 4; //可适合修改
        if (zoom > 0) img.style.zoom = zoom + '%';
    })
    .on('click', '.uploadImgPreBoxClose', function () {
        hideUploadImg();
        $(this).parents('.uploadImgPreBox').remove();
    })
    .on('click', '.uploadImgPreBoxDel', function () {
        var btn = $(this);
        $.getJSON('/basis/file/delete', {src: btn.data('src')}, function (re) {
            if (re.type) {
                hideUploadImg();
                btn.parents('.uploadImgPreBox').remove();
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
                $(btn.data('node')).find('[src="' + btn.data('src') + '"]').remove();
                showMsg('图片删除成功');
            } else {
                hideUploadImg();
                btn.parents('.uploadImgPreBox').remove();
                showMsg('图片删除失败');
            }
        })
    });
var uploadScrollTop;

function showUploadImg() {
    uploadScrollTop = $(window).scrollTop();
    $('body').css({position: 'fixed', width: '100%'});
}

function hideUploadImg() {
    $('body').css({position: '', width: ''});
    $(window).scrollTop(uploadScrollTop);
}

function uploadImg(node, name, def, readOnly, max) {
    $(node).addClass('uploadImgBox');
    if (def) {
        var defArr = def.split(',');
        if (readOnly) {
            $.each(defArr, function (k, v) {
                $(node).append('<img class="uploadImgOne imgPre" src="' + v + '"/>');
            });
            return;
        }
        $(node).append('<input type="hidden" name="' + name + '" value="' + def + '"/>');
        $.each(defArr, function (k, v) {
            $(node).append('<img class="uploadImgOne imgOne" src="' + v + '"/>');
        });
    } else {
        if (readOnly) {
            $(node).append('<div class="uploadImgOne"></div>');
            return;
        }
        $(node).append('<input type="hidden" name="' + name + '"/>');
    }
    max = max ? max : 10;
    $(node)
        .append('<img class="uploadImgOne addImg" src="/img/addImg.jpg"/>')
        .append('<input type="file" accept=".jpg,.png,.gif" class="fileInput" style="display: none"/>')
        .on('click', '.addImg', function () {
            if ($(node).find('.imgOne').length + 1 > max) {
                showMsg('最多上传' + max + '张图片');
                return;
            }
            $(node).find('.fileInput').click();
        })
        .on('change', '.fileInput', function () {
            var file = $(this)[0].files[0];
            lrz(file, {quality: 0.5}).then(function (re) {
                file = new File([re.file], file.name, {type: file.type});
                var formData = new FormData();
                formData.append('file', file);
                $.ajax({
                    url: '/basis/file/upload',
                    type: 'POST',
                    data: formData,
                    processData: false,
                    contentType: false,
                    success: function (re) {
                        re = JSON.parse(re);
                        if (re.type) {
                            $(node).find('.addImg').before('<img class="uploadImgOne imgOne" src="' + re.data + '"/>');
                            $(node).find('[name="' + name + '"]').val(function (i, val) {
                                if (val) {
                                    return val + ',' + re.data;
                                }
                                return re.data;
                            });
                        } else {
                            showMsg("图片上传失败");
                        }
                    }, error: function () {
                        showMsg("图片上传失败");
                    }
                });
            }).catch(function (err) {
                showMsg('图片压缩失败');
            });
        })
        .on('click', '.imgOne', function () {
            showUploadImg();
            $('body').append('<div class="uploadImgPreBox"><div class="btns"><button type="button" class="btn btn-sm btn-waring uploadImgPreBoxClose">关闭</button>&nbsp;<button type="button" class="btn btn-sm btn-danger uploadImgPreBoxDel" data-src="' + $(this).prop('src') + '" data-node="' + node + '" data-name="' + name + '">删除</button></div><span><img src="' + $(this).prop('src') + '"></span></div>');
        });
    return;
}

function uploadFile(node, name, def, readOnly) {
    if (def) {
        if (readOnly) {
            $(node)
                .append('<a class="btn btn-sm btn-info" href="https://view.officeapps.live.com/op/embed.aspx?src=' + encodeURI(def) + '">查看</a>')
                .append('<a class="btn btn-sm btn-info look" href="' + def + '" style="margin-left: 1rem">下载</a>');
            return;
        }
        $(node)
            .append('<button type="button" class="btn btn-sm btn-info upFile" style="margin-right: 1rem">重新上传</button>')
            .append('<button class="btn btn-sm btn-danger look delFile" type="button" style="margin-right: 1rem" data-src="' + def + '">删除</button>')
            .append('<a class="btn btn-sm btn-info look" target="view_window" href="https://view.officeapps.live.com/op/embed.aspx?src=' + encodeURI(def) + '">查看</a>')
            .append('<a class="btn btn-sm btn-info look" href="' + def + '" style="margin-left: 1rem">下载</a>')
            .append('<input type="hidden" name="' + name + '" value="' + def + '"/>')
            .append('<input type="file" accept=".docx" class="fileInput" style="display: none"/>');
    } else {
        if (readOnly) {
            $(node).append('<button type="button" class="btn btn-sm btn-info">没有文件上传</button>');
            return;
        }
        $(node)
            .append('<button type="button" class="btn btn-sm btn-info upFile" style="margin-right: 1rem">上传文件</button>')
            .append('<input type="hidden" name="' + name + '"/>')
            .append('<input type="file" accept=".docx" class="fileInput" style="display: none"/>');
    }
    $(node)
        .on('click', '.upFile', function () {
            $(node).find('.fileInput').click();
        })
        .on('change', '.fileInput', function () {
            var formData = new FormData();
            formData.append('file', $(this)[0].files[0]);
            $.ajax({
                url: '/basis/file/upload',
                type: 'POST',
                data: formData,
                processData: false,
                contentType: false,
                success: function (re) {
                    re = JSON.parse(re);
                    if (re.type) {
                        $(node).find('.look').remove();
                        $(node).find('.upFile').text('重新上传').after('<button class="btn btn-sm btn-danger look delFile" type="button" style="margin-right: 1rem" data-src="' + re.data + '">删除</button><a class="btn btn-sm btn-info look" target="view_window" href="https://view.officeapps.live.com/op/embed.aspx?src=' + encodeURI(re.data) + '">查看</a><a class="btn btn-sm btn-info look" href="' + re.data + '" style="margin-left: 1rem">下载</a>');
                        $(node).find('[name="' + name + '"]').val(function (i, val) {
                            if (val) {
                                $.getJSON('/basis/file/delete', {src: val});
                            }
                            return re.data;
                        });
                        showMsg("文件上传成功");
                    } else {
                        showMsg("文件上传失败");
                    }
                }, error: function () {
                    showMsg("文件上传失败");
                }
            });
        })
        .on('click', '.delFile', function () {
            $.getJSON('/basis/file/delete', {src: $(this).data('src')}, function (re) {
                if (re.type) {
                    $(node).find('.look').remove();
                    $(node).find('.upFile').text('上传文件');
                    $(node).find('[name="' + name + '"]').val('');
                    showMsg("文件删除成功");
                    return;
                }
                showMsg("文件删除失败");
            });
        });
    return;
}