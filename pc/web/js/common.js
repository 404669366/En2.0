window.remSize = document.documentElement.getBoundingClientRect().width * 0.01;
document.documentElement.style.fontSize = window.remSize + 'px';

document.write("<link href='/css/common.css' rel='stylesheet'>");
document.write("<link href='/font/css/font-awesome.min.css' rel='stylesheet'>");
document.write("<script src='/js/jquery-3.3.1.min.js' type='text/javascript' charset='utf-8'></script>");
document.write("<script src='/js/jquery.cookie.js' type='text/javascript' charset='utf-8'></script>");
document.write("<script src='/js/layer/layer.min.js' type='text/javascript' charset='utf-8'></script>");
document.write("<script src='/js/dot.min.js' type='text/javascript' charset='utf-8'></script>");

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
        var height = window.screen.availHeight;
        var messageSize = size || ($.cookie('message-size') || '1rem');
        if (messageSize) {
            layer.msg(
                '<span style="font-size:' + messageSize + ';height:100%;line-height:100%">' + data + '</span>',
                {offset: (height - 160) / 2 + 'px'}
            );
        } else {
            layer.msg(data, {offset: (height - 160) / 2 + 'px'});
        }
    }
};

window.showMsgDo = function (data, time, func, size) {
    if (data) {
        var messageSize = size || ($.cookie('message-size') || '1rem');
        if (messageSize) {
            layer.msg('<span style="font-size:' + messageSize + ';height:100%;line-height:100%">' + data + '</span>', {time: time * 1000}, function () {
                func();
            });
        } else {
            layer.msg(data, {time: time * 1000}, function () {
                func();
            });
        }
    }
};

window.format = function (num, length) {
    return Math.round(num * Math.pow(10, length)) / Math.pow(10, length);
};

window.in_string = function (str, val) {
    return str.indexOf(val) > -1;
};

window.toDate = function (second) {
    second = second || 0;
    var date = {hour: 3600, minute: 60, second: 1};
    var dateName = {hour: '时', minute: '分', second: '秒'};
    var str = '';
    $.each(date, function (k, v) {
        if (second >= v) {
            var now = Math.floor(second / v);
            second = second - (now * v);
            now = k === 'day' ? now : window.prefixZero(now, 2);
            str += now + dateName[k];
        } else {
            str += '00' + dateName[k];
        }
    });
    return str;
};

window.prefixZero = function (num, length) {
    return (Array(length).join('0') + num).slice(-length);
};

window.html = function (inner_id, template_id, data) {
    $('#' + inner_id).html(doT.template($('#' + template_id).text())(data));
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
