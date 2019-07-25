<!DOCTYPE html>
<html lang="zh">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width,initial-scale=1.0">
    <title>亿能科技</title>
    <link rel="shortcut icon" href="/favicon.ico">
    <link href="/css/buy.css" rel="stylesheet">
    <script src="/js/common.js" type="text/javascript" charset='utf-8'></script>
    <script src="/js/qrCode.js" type="text/javascript" charset='utf-8'></script>
    <script src="/js/socket.io.js" type="text/javascript" charset='utf-8'></script>
</head>
<body>
<div class="head">
    <div class="headContent">
        <img data-url="/<?= Yii::$app->params['defaultRoute'] ?>.html" src="/img/logo.png" alt="四川亿能天成新能源logo">
        <ul>
            <li><a href="/index/index/index.html">首页<span></span></a></li>
            <li class="active"><a href="/field/field/list.html">项目<span></span></a></li>
            <li><a href="/news/news/list.html">新闻<span></span></a></li>
            <li class="nav" onselectstart="return false">
                关于 <i class="fa fa-caret-down" aria-hidden="true"></i>
                <span></span>
                <ul>
                    <li class="active"><a href="/about/about/company.html">公司介绍</a></li>
                    <li><a href="/about/about/partner.html">合作伙伴</a></li>
                    <li><a href="/about/about/contact.html">联系我们</a></li>
                    <li><a href="/about/about/guide.html">用户指南</a></li>
                </ul>
            </li>
            <?php if (Yii::$app->user->isGuest): ?>
                <li>
                    <p data-url="/<?= Yii::$app->params['loginRoute'] ?>.html">登录 / 注册</p>
                    <a href="/<?= Yii::$app->params['loginRoute'] ?>.html" class="hide">登录 / 注册</a>
                </li>
            <?php else: ?>
                <li title="个人中心">
                    <a href="/user/user/center.html">
                        <?= Yii::$app->user->getIdentity()->tel ?><span></span>
                    </a>
                </li>
                <li><a href="/<?= Yii::$app->params['logoutRoute'] ?>.html">退出<span></span></a></li>
            <?php endif; ?>
        </ul>
    </div>
</div>
<script>
    $('.nav').hover(
        function () {
            $(this).find('ul').slideToggle(200);
            var i = $(this).find('i');
            if (i.hasClass("rotate180")) {
                i.removeClass("rotate180");
                i.addClass("rotate0");
            } else {
                i.removeClass("rotate0");
                i.addClass("rotate180");
            }
        },
        function () {
            $(this).find('ul').slideToggle(200);
            var i = $(this).find('i');
            if (i.hasClass("rotate180")) {
                i.removeClass("rotate180");
                i.addClass("rotate0");
            } else {
                i.removeClass("rotate0");
                i.addClass("rotate180");
            }
        }
    );
</script>
<div class="center">
    <div class="pay">
        <h1>Pay Deposit</h1>
        <h3>支付定金</h3>
        <div class="payBox">
            <h3>微信支付<span class="surplus">二维码剩余有效时间为分钟,请尽快支付</span></h3>
            <div class="scanBox">
                <div>
                    <div class="qrCode">
                        <div class="occlusion">点击刷新</div>
                    </div>
                    <div class="maowdal">
                        <img src="/img/scan.png" alt="">
                        <span>请使用微信扫一扫<br>扫描二维码支付</span>
                    </div>
                </div>
                <div>
                    <img src="/img/wxscan.png" alt="">
                </div>
            </div>
        </div>
        <div class="btnBox">
            <a href="/field/intention/no-pay.html?no=<?= $no ?>" class="btn">放弃支付</a>
        </div>
    </div>
</div>
<script>
    $('.qrCode').makeCode({
        text: `<?=$url?>`
    });
    var time = 7200;
    $('.surplus').text('二维码剩余有效时间为' + window.toDate(time) + ',请尽快支付');
    var i = setInterval(function () {
        time--;
        if (time > 0) {
            $('.surplus').text('二维码剩余有效时间为' + window.toDate(time) + ',请尽快支付');
        } else {
            clearInterval(i);
            $('.qrCode').css('position', 'relative');
            $('.occlusion').show();
            $('.surplus').text('二维码已过期,请刷新后再支付');
        }
    }, 1000);
    $('.occlusion').click(function () {
        window.location.reload();
    });
    var socket = io('http://127.0.0.1:2120');
    socket.on('connect', function () {
        socket.emit('bind', '<?=$no?>');
    });
    socket.on('msg', function (msg) {
        $.removeCookie('pay-times-<?=$id?>',{ path: '/'});
        if (msg) {
            window.showMsg('支付成功');
            setTimeout(function () {
                window.location.href = '/user/intention/list.html';
            }, 2000);
            return;
        }
        window.showMsg('错误操作');
        setTimeout(function () {
            window.location.href = '/field/field/detail.html?no=<?=$fieldNo?>';
        }, 2000);
    });
</script>
<div class="footer">
    <div class="info">
        <div>
            <h4>联系我们</h4>
            <p>客服电话：400-039-9918</p>
            <p>商务沟通：pr@en.ink</p>
            <p>服务时间：09:00-18:00</p>
            <p>公司地址：四川省成都市高新区天府软件园G5 3楼</p>
        </div>
        <div>
            <h4>项目</h4>
            <a href="/user/field/create.html">发起项目</a><br/>
            <a href="/field/field/list.html">投资项目</a><br/>
        </div>
        <div>
            <h4>关于</h4>
            <a href="/about/about/company.html">公司介绍</a><br/>
            <a href="/about/about/partner.html">合作伙伴</a><br/>
            <a href="/about/about/contact.html">联系我们</a><br/>
            <a href="/about/about/guide.html">用户指南</a><br/>
        </div>
        <div>
            <img src="/img/qrCode.jpg" alt="四川亿能天成微信公众号"><br/>
            扫码关注微信公众号
        </div>
    </div>
    <div class="friend">
        <?php foreach (\vendor\project\helpers\Constant::friends() as $v): ?>
            <span>
                <img src="<?= $v['logo'] ?>" alt="<?= $v['name'] ?>logo">
                <a href="<?= $v['url'] ?>" target="_blank"><?= $v['name'] ?></a>
            </span>
        <?php endforeach; ?>
    </div>
    <div class="icp">四川亿能天成科技有限公司 蜀ICP备19002778号-1 Copyright © 2019 en.ink 市场有风险 投资需谨慎</div>
</div>
</body>
</html>