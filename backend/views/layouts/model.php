<?php
\app\assets\ModelAsset::register($this);
$this->beginPage();
?>
<!DOCTYPE html>
<html lang="zh">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>亿能科技</title>
    <link rel="shortcut icon" href="/favicon.ico">
    <?php $this->head(); ?>
</head>
<body class="gray-bg">
<?php $this->beginBody(); ?>
<?php $this->endBody(); ?>
<script>
    var btns = JSON.parse(`<?=json_encode($btns)?>`);
    $(function () {
        $.each($('.btn'), function (k, v) {
            var rid = $(v).data('rid');
            if (rid) {
                if (!btns[rid]) {
                    $(v).remove();
                }
            }
        });
        $('.back').prop('type', 'button').click(function () {
            history.go(-1);
        });
    });

    $('body').on('click', '[data-url]', function () {
        if($(this).data('url')){
            window.location.href = $(this).data('url');
        }
    });

    function timeToDate(timestamp = +new Date()) {
        var date = new Date(timestamp * 1000 + 8 * 3600 * 1000); // 增加8小时
        return date.toJSON().substr(0, 19).replace('T', ' ');
    }

    function linFeed(str, max) {
        if (str) {
            max = max || 25;
            if (str.length > max) {
                var newStr = '';
                var seat = 0;
                var time = Math.floor(str.length / max);
                for (var i = 1; i <= time; i++) {
                    newStr = newStr + str.substring(seat, i * max) + '<br/>';
                    seat = i * max;
                }
                newStr += str.substring(seat, str.length);
                return newStr;
            }
            return str;
        }
        return str;
    }


</script>
<?= $content ?>
</body>
</html>
<?php $this->endPage() ?>

