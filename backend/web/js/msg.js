var messageSize = $.cookie('message-size');
var messageData = $.cookie('message-data');
if (messageData) {
    showMsg(messageData);
    $.cookie('message-data', '', {path: '/'})
}

function showMsg(data) {
    if (data) {
        if (messageSize) {
            layer.msg('<span style="font-size:' + messageSize + ';height:100%;line-height:100%">' + data + '</span>');
        } else {
            layer.msg(data);
        }
    }
}