document.documentElement.style.fontSize = (document.documentElement.getBoundingClientRect().width * 0.01) + 'px';

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

window.showMsg = function (data) {
    if (data) {
        var messageSize = $.cookie('message-size');
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

});
