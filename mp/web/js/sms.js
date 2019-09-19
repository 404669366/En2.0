window.sms = function (config) {
    $(config.click).click(function () {
        if (check($(config.telModel).val())) {
            $.getJSON('/basis/sms/send.html', {tel: $(config.telModel).val()}, function (re) {
                if (re.type) {
                    window.showMsg('验证码发送成功,请注意查收');
                    var text = $(config.click).text();
                    var time = config.timeout || 60;
                    $(config.click).text(time + 's').attr('send', true);
                    var id = setInterval(function () {
                        time--;
                        if (time < 0) {
                            $(config.click).text(text).removeAttr('send');
                            clearInterval(id);
                            return;
                        }
                        $(config.click).text(time + 's');
                    }, 1000);
                } else {
                    window.showMsg(re.msg);
                }
            })
        }
    });

    function check(tel) {
        if (!tel) {
            window.showMsg('请填写手机号');
            return false;
        }
        if (!window.checkTel(tel)) {
            window.showMsg('手机号有误');
            return false;
        }
        if ($(config.click).attr('send')) {
            window.showMsg('验证码已发送,请稍后再试');
            return false;
        }
        return true;
    }
};
