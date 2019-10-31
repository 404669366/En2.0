document.write("<script src='/js/oss/crypto-min.js' type='text/javascript' charset='utf-8'></script>");
document.write("<script src='/js/oss/hmac-min.js' type='text/javascript' charset='utf-8'></script>");
document.write("<script src='/js/oss/sha1-min.js' type='text/javascript' charset='utf-8'></script>");
document.write("<script src='/js/oss/base64.js' type='text/javascript' charset='utf-8'></script>");
window.load(function () {
    window.oss = function () {
        var day = new Date();
        day.setDate(day.getDate() + 1);
        var config = {
            access_id: 'LTAI9s99tZC58pzG',
            access_key: 'usmBiqxU7jMYV9Gz7qSToq8J1Q8lWb',
            host: 'https://ascasc.oss-cn-hangzhou.aliyuncs.com/',
            policy_text: {
                //设置该Policy的失效时间，超过这个失效时间之后，就没有办法通过这个policy上传文件了
                "expiration": day.format("yyyy-MM-dd") + "T12:00:00.000Z",
                "conditions": [
                    // 设置上传文件的大小限制
                    ["content-length-range", 0, 1048576000]
                ]
            }
        };
        var policy_base64 = Base64.encode(JSON.stringify(config.policy_text));
        var signature = Crypto.util.bytesToBase64(Crypto.HMAC(Crypto.SHA1, policy_base64, config.access_key, {asBytes: true}));
        $('body').append(`<div class="progress" style="display: none;position: fixed;width: 100%;height: 100%;left: 0;top: 0"><div style="position: absolute;top: 50%;left: 50%;-webkit-transform: translate(-50%, -50%);-o-transform: translate(-50%, -50%);-moz-transform: translate(-50%, -50%);transform: translate(-50%, -50%);width: 20%;height: 0.12rem;border-radius: 0.06rem;background: url('data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAAJoAAAAVCAYAAAC+GfcaAAAAAXNSR0IArs4c6QAAAARnQU1BAACxjwv8YQUAAAAJcEhZcwAADsMAAA7DAcdvqGQAAABQSURBVGhD7dIhAQAgEAAxIpGN8G8hBacm1mBrn7nwm2gkRCMhGgnRSIhGQjQSopEQjYRoJEQjIRoJ0UiIRkI0EqKREI2EaCREIyEaCdEIzH1KhLlvuVhBNQAAAABJRU5ErkJggg==') #f3f3f3 repeat-y;background-size: 0% auto"></div></div>`);
        return {
            upload: function (file, success, error) {
                var formData = new FormData();
                formData.append('name', file.name);
                formData.append('key', '${filename}');
                formData.append('policy', policy_base64);
                formData.append('OSSAccessKeyId', config.access_id);
                formData.append('success_action_status', '200');
                formData.append('signature', signature);
                formData.append('file', file);
                $.ajax({
                    url: config.host,
                    type: 'POST',
                    data: formData,
                    processData: false,
                    contentType: false,
                    success: function (re) {
                        $('body').css('overflow-y', 'auto');
                        $('.progress>div').css('background-size', '100% auto').parent().hide();
                        if (success) {
                            success(file);
                        }
                    },
                    xhr: function () {
                        var xhr = new XMLHttpRequest();
                        xhr.upload.addEventListener('progress', function (e) {
                            var progress = (e.loaded / e.total) * 100;
                            $('body').css('overflow-y', 'hidden');
                            $('.progress>div').css('background-size', progress + '% auto').parent().show();
                        });
                        return xhr;
                    },
                    error: function (re) {
                        if (error) {
                            error(re);
                        }
                    }
                });
            },
            remove: function (fileName, callBack) {
                $.getJSON('/basis/file/delete.html', {name: fileName}, function (re) {
                    if (callBack) {
                        callBack(re);
                    }
                })
            }
        }
    }()
});