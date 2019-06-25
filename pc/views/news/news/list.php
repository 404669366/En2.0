<!DOCTYPE html>
<html lang="zh">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width,initial-scale=1.0">
    <title>亿能科技</title>
    <link rel="shortcut icon" href="/favicon.ico">
    <link href="/css/news.css" rel="stylesheet">
    <script src="/js/common.js" type="text/javascript" charset='utf-8'></script>
</head>
<body>
<div class="head">
    <div class="headContent">
        <img data-url="/<?= Yii::$app->params['defaultRoute'] ?>.html" src="/img/logo.png" alt="四川亿能天成新能源logo">
        <ul>
            <li data-url="/index/index/index.html">首页<span></span></li>
            <li data-url="/field/field/list.html">项目<span></span></li>
            <li class="active" data-url="/news/news/list.html">新闻<span></span></li>
            <li data-url="/about/about/center.html">关于<span></span></li>
        </ul>
        <?php if (Yii::$app->user->isGuest): ?>
            <a href="/<?= Yii::$app->params['loginRoute'] ?>.html">登录 / 注册</a>
        <?php else: ?>
            <p>
                <span data-url="/user/user/center.html"><?= Yii::$app->user->getIdentity()->tel ?></span>
                <span data-url="/<?= Yii::$app->params['logoutRoute'] ?>.html">退出</span>
            </p>
        <?php endif; ?>
    </div>
</div>
<div class="center">
    <div class="list">
        <div class="title">
            <h2>Press</h2>
            <p>新闻</p>
        </div>
        <ul class="data">
            <?php foreach ($data as $v): ?>
                <li data-url="/news/news/detail.html?id=<?= $v['id'] ?>">
                    <div class="img"><img src="<?= $v['image'] ?>" alt="<?= $v['title'] ?>"></div>
                    <p>
                        <img src="<?= $v['sourceLogo'] ?>" alt="<?= $v['source'] ?>">
                        <span><?= $v['source'] ?></span>
                    </p>
                    <h3><?= $v['title'] ?></h3>
                </li>
            <?php endforeach; ?>
            <div class="clearBoth"></div>
        </ul>
    </div>
</div>
<script>
    $('.list>.data>li').hover(
        function () {
            $(this).find('.img>img').css({width: '125%', height: '125%'});
            $(this).find('h3').css('text-decoration', 'underline');
        },
        function () {
            $(this).find('.img>img').css({width: '100%', height: '100%'});
            $(this).find('h3').css('text-decoration', 'none');
        }
    );
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
            <p data-url="/user/field/create.html">发起项目</p>
            <p data-url="/field/field/list.html">投资项目</p>
        </div>
        <div>
            <h4>关于</h4>
            <p data-url="/about/about/company.html">公司介绍</p>
            <p data-url="/about/about/partner.html">合作伙伴</p>
            <p data-url="/about/about/contact.html">联系我们</p>
            <p data-url="/about/about/guide.html">用户指南</p>
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