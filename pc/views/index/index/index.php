<!DOCTYPE html>
<html lang="zh">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width,initial-scale=1.0">
    <title>亿能科技</title>
    <link rel="shortcut icon" href="/favicon.ico">
    <link href="/css/index.css" rel="stylesheet">
    <script src="/js/common.js" type="text/javascript" charset='utf-8'></script>
</head>
<body>
<div class="head">
    <div class="headContent">
        <img data-url="/<?= Yii::$app->params['defaultRoute'] ?>.html" src="/img/logo.png" alt="四川亿能天成新能源logo">
        <ul>
            <li class="active"><a href="/index/index/index.html">首页<span></span></a></li>
            <li><a href="/field/field/list.html">项目<span></span></a></li>
            <li><a href="/news/news/list.html">新闻<span></span></a></li>
            <li><a href="/about/about/center.html">关于<span></span></a></li>
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
<script>
    $(function () {
        $(document).scroll(function () {
            if ($(this).scrollTop() >= $('.head').height()) {
                $('.head').css('background', '#23252a');
            }
            if ($(this).scrollTop() < $('.head').height()) {
                $('.head').css('background', 'rgba(0, 0, 0, 0.3)');
            }
        });

        $(document).scroll();
    });
</script>
<div class="slider">
    <img class="slider-item" src="/img/1.jpg"/>
    <img class="slider-item" src="/img/2.jpg"/>
    <img class="slider-item" src="/img/3.jpg"/>
    <img class="slider-item" src="/img/4.jpg"/>
    <img class="slider-item" src="/img/5.jpg"/>
    <div class="slider-info">
        <h1>全球首家专业的新能源充电站投资平台</h1>
        <b>投资 | 建设 | 运营 | 维护</b>
        <div class="slider-btn-box">
            <?php if (Yii::$app->user->isGuest): ?>
                <div class="slider-btn" data-url="/<?= Yii::$app->params['loginRoute'] ?>.html">成为投资人</div>
            <?php else: ?>
                <div class="slider-btn" data-url="/user/field/create.html">
                    发起项目<a class="hide" href="/user/field/create.html">发起项目</a>
                </div>
                &nbsp;
                <div class="slider-btn" data-url="/field/field/list.html">
                    寻找项目<a class="hide" href="/user/field/list.html">寻找项目</a>
                </div>
            <?php endif; ?>
        </div>
        <div class="slider-box">
            <div>
                <b class="num"><?= \vendor\project\helpers\Constant::goDay() ?></b>
                <b class="unit">天</b>
                <br/>成立至今<br/>不忘初心，方得始终
            </div>
            <div>
                <b class="num"><?= \vendor\project\helpers\Constant::userCount() ?></b>
                <b class="unit">位</b>
                <br/>参与人<br/>信任是我们最大的财富
            </div>
            <div>
                <b class="num"><?= \vendor\project\helpers\Constant::fieldCount() ?></b>
                <b class="unit">个</b>
                <br/>投资项目<br/>遍布全球各地
            </div>
            <div>
                <b class="num"><?= \vendor\project\helpers\Constant::amountCount() ?></b>
                <b class="unit">亿</b>
                <br/>成交金额<br/>大量资金助力项目
            </div>
        </div>
    </div>
</div>
<div class="center">
    <div class="content">
        <div class="model">
            <div class="title">
                <p><b>Investment</b>投资美好生活</p>
                <div data-url="/field/field/list.html"><i class="fa fa-arrow-right" aria-hidden="true"></i></div>
            </div>
            <ul class="investment">
                <?php foreach ($field as $v): ?>
                    <li data-url="/field/field/detail.html?no=<?= $v['no'] ?>">
                        <div class="img">
                            <img src="<?= explode(',', $v['images'])[0] ?>" alt="<?= $v['title'] ?>">
                        </div>
                        <h3><?= $v['title'] ?></h3>
                        <p>
                            <?= \vendor\project\helpers\Constant::businessType()[$v['business_type']] ?>
                            &emsp;|&emsp;
                            <?= \vendor\project\helpers\Constant::investType()[$v['invest_type']] ?>
                        </p>
                        <div class="intro">
                            <?= $v['address'] ?>
                        </div>
                        <div class="progress">
                            <div>项目总额: <?= $v['budget_amount'] ?></div>
                            <div>认购进度: <span><?= $v['present_amount'] / $v['budget_amount'] * 100 ?>%</span></div>
                            <div style="background-size: <?= $v['present_amount'] / $v['budget_amount'] * 100 ?>% auto"></div>
                        </div>
                        <a class="hide" href="/field/field/detail.html?no=<?= $v['no'] ?>"><?= $v['title'] ?></a>
                    </li>
                <?php endforeach; ?>
                <div class="clearBoth"></div>
            </ul>
        </div>
        <div class="model">
            <div class="title">
                <p><b>Press</b>新闻</p>
                <div data-url="/news/news/list.html"><i class="fa fa-arrow-right" aria-hidden="true"></i></div>
            </div>
            <ul class="press">
                <?php foreach ($news as $v): ?>
                    <li data-url="/news/news/detail.html?id=<?= $v['id'] ?>">
                        <div class="img">
                            <img src="<?= $v['image'] ?>" alt="<?= $v['title'] ?>">
                        </div>
                        <p>
                            <img src="<?= $v['sourceLogo'] ?>">
                            <span><?= $v['source'] ?></span>
                        </p>
                        <h3><?= $v['title'] ?></h3>
                        <div class="intro"><?= $v['intro'] ?></div>
                        <a class="hide" href="/news/news/detail.html?id=<?= $v['id'] ?>"><?= $v['title'] ?></a>
                    </li>
                <?php endforeach; ?>
                <div class="clearBoth"></div>
            </ul>
        </div>
    </div>
</div>
<script>
    $('.investment>li,.press>li').hover(
        function () {
            $(this).find('.img>img').stop().animate({width: '125%', height: '125%'},500);
            $(this).find('h3').css('text-decoration', 'underline');
        },
        function () {
            $(this).find('.img>img').stop().animate({width: '100%', height: '100%'},500);
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