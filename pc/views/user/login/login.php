<!DOCTYPE html>
<html lang="zh">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width,initial-scale=1.0">
    <title>亿能科技</title>
    <link rel="shortcut icon" href="/favicon.ico">
    <link href="/css/login.css" rel="stylesheet">
    <script src="/js/common.js" type="text/javascript" charset='utf-8'></script>
    <script src="/js/sms.js" type="text/javascript" charset='utf-8'></script>
</head>
<body>
<div class="slider">
    <img class="slider-item" src="/img/1.jpg"/>
    <img class="slider-item" src="/img/2.jpg"/>
    <img class="slider-item" src="/img/3.jpg"/>
    <img class="slider-item" src="/img/4.jpg"/>
    <img class="slider-item" src="/img/5.jpg"/>
    <div class="slider-info">
        <h1>全球首家专业的新能源充电站投资平台</h1>
        <b>投资 | 建设 | 运营 | 维护</b>
        <form action="" method="post">
            <h2>登录 / 注册</h2>
            <input type="text" name="tel">
            <input type="text" name="code"><span class="smsClick">获取验证码</span>
            <input type="hidden" name="_csrf" value="<?= Yii::$app->request->csrfToken ?>">
            <button type="submit">登录 / 注册</button>
        </form>
        <div class="slider-box">
            <div>
                <b class="num">321</b>
                <b class="unit">天</b>
                <br/>成立至今<br/>不忘初心，方得始终
            </div>
            <div>
                <b class="num">66666</b>
                <b class="unit">位</b>
                <br/>参与人<br/>信任是我们最大的财富
            </div>
            <div>
                <b class="num">88888</b>
                <b class="unit">个</b>
                <br/>投资项目<br/>遍布全球各地
            </div>
            <div>
                <b class="num">99999</b>
                <b class="unit">亿</b>
                <br/>成交金额<br/>大量资金助力项目
            </div>
        </div>
    </div>
    <script>
        window.sms({telModel: '[name="tel"]', click: '.smsClick'});
    </script>
</div>
</body>
</html>