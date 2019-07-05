window.remSize = document.documentElement.getBoundingClientRect().width * 0.01;
document.documentElement.style.fontSize = window.remSize + 'px';

document.write("<link href='/css/common.css' rel='stylesheet'>");
document.write("<link href='/css/font-awesome.min.css' rel='stylesheet'>");
document.write("<script src='/js/jquery-3.3.1.min.js' type='text/javascript' charset='utf-8'></script>");
document.write("<script src='/js/jquery.cookie.js' type='text/javascript' charset='utf-8'></script>");
document.write("<script src='/js/layer/layer.min.js' type='text/javascript' charset='utf-8'></script>");

window.load = function (func) {
    var oldLoad = window.onload;
    if (typeof window.onload !== 'function') {
        window.onload = func;
    }
    else {
        window.onload = function () {
            oldLoad();
            func();
        }
    }
};

window.getParams = function (name, def) {
    var reg = new RegExp("(^|&)" + name + "=([^&]*)(&|$)");
    var re = window.location.search.substr(1).match(reg);
    if (re !== null) {
        return decodeURI(re[2]);
    }
    if (def) {
        return def;
    }
    return null;
};

window.postCall = function (url, params, target) {
    var form = document.createElement("form");
    form.style.display = "none";
    form.action = url || '';
    form.method = "post";
    form.target = target || '_self';
    var opt;
    for (var x in params) {
        opt = document.createElement("input");
        opt.name = x;
        opt.value = params[x];
        form.appendChild(opt);
    }
    document.body.appendChild(form);
    form.submit();
    document.body.removeChild(form);
};

window.checkTel = function (tel) {
    if (tel) {
        return /^(((13[0-9]{1})|(14[0-9]{1})|(17[0]{1})|(15[0-3]{1})|(15[5-9]{1})|(18[0-9]{1}))+\d{8})$/.test(tel);
    }
    return false;
};

window.showMsg = function (data, size) {
    if (data) {
        var messageSize = size || ($.cookie('message-size') || '1rem');
        if (messageSize) {
            layer.msg('<span style="font-size:' + messageSize + ';height:100%;line-height:100%">' + data + '</span>');
        } else {
            layer.msg(data);
        }
    }
};


window.load(function () {

    $('*[data-url]').click(function () {
        var url = $(this).data('url');
        var newWindow = $(this).data('new');
        if (url) {
            if (newWindow && newWindow === true) {
                window.open(url);
            } else {
                window.location.href = url;
            }
        }
    });

    window.showMsg($.cookie('message-data'));
    $.cookie('message-data', '', {path: '/'});

    $('.page>li').click(function () {
        var url = window.location.href;
        var key = 'pageNum';
        var last = window.getParams(key);
        var val = 1;
        switch ($(this).data('do')) {
            case 'first':
                val = 1;
                break;
            case 'prev':
                if (last > 1) {
                    val = parseInt(last) - 1;
                }
                break;
            case 'next':
                if ($('.data').find('li').length < 6) {
                    val = last || 1;
                } else {
                    val = parseInt(last) + 1;
                }
                break;
        }
        if (last !== null) {
            window.location.href = url.replace(key + '=' + last, key + '=' + val);
        } else {
            var mark = '&';
            if (url === $(this).parent().data('getby')) {
                mark = '?';
            }
            window.location.href = url + mark + key + '=' + val;
        }
    });

});
