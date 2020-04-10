<?php
\app\assets\LoginAsset::register($this);
$this->beginPage();
?>
<!DOCTYPE html>
<html lang="zh">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0">
    <title>亿能科技 - 登录</title>
    <!--[if lt IE 9]>
    <meta http-equiv="refresh" content="0;ie.html"/>
    <![endif]-->
    <link rel="shortcut icon" href="/favicon.ico">
    <?php $this->head(); ?>
    <script>
        if (window.top !== window.self) {
            window.top.location = window.location;
        }
    </script>
</head>
<body class="signin">
<?php $this->beginBody(); ?>
<?php $this->endBody(); ?>
<div class="signinpanel">
    <div class="row">
        <div class="col-sm-7">
            <div class="signin-info">
                <div class="logopanel m-b">
                    <h1>[ EN ]</h1>
                </div>
                <div class="m-b"></div>
                <h4>欢迎使用亿能科技后台</h4>
            </div>
        </div>
        <div class="col-sm-5">
            <form method="post" action="">
                <input type="hidden" name="_csrf" value="<?= Yii::$app->request->csrfToken ?>"/>
                <h4 class="no-margins">登录：</h4>
                <input type="text" class="form-control uname" name="tel" placeholder="手机号"/>
                <input type="password" class="form-control pword" name="pwd" placeholder="密码"/>
                <img class="code" src="/member/login/code" onclick="this.src+='?'+Math.random()">
                <input type="text" class="form-control" name="code" placeholder="验证码"/>
                <button class="btn btn-success btn-block">登录</button>
            </form>
        </div>
    </div>
    <div class="signup-footer">
        <div class="pull-left">
            四川亿能天成科技有限公司 蜀ICP备19002778号-1 Copyright © 2019
        </div>
    </div>
</div>
</body>
</html>
<?php $this->endPage() ?>
