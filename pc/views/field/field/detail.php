<!DOCTYPE html>
<html lang="zh">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width,initial-scale=1.0">
    <title>亿能科技</title>
    <link rel="shortcut icon" href="/favicon.ico">
    <link href="/css/field.css" rel="stylesheet">
    <script src="/js/common.js" type="text/javascript" charset='utf-8'></script>
    <script src="/js/map.js" type="text/javascript" charset='utf-8'></script>
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
                    <li><a href="/about/about/company.html">公司介绍</a></li>
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
    <div class="detail">
        <h1 class="title"><?= $detail->title ?></h1>
        <div class="details">
            <div class="slider">
                <?php foreach (\vendor\project\helpers\Helper::completionImg($detail->images) as $v): ?>
                    <img src="<?= $v ?>" alt="亿能天成新能源场站<?= $detail->no ?>图片"/>
                <?php endforeach; ?>
            </div>
            <div class="prove">
                <span>发改备案 : <i class="fa fa-check-circle-o" aria-hidden="true"></i></span>
                <span>电力审核 : <i class="fa fa-check-circle-o" aria-hidden="true"></i></span>
            </div>
            <p>项目介绍</p>
            <div class="intro"><?= $intro ?></div>
            <p>项目位置</p>
            <div class="address"><?= $detail->address ?></div>
            <div class="map">
                <div id="map"></div>
                <div class="back"><img src="/img/mapBack.png"></div>
            </div>
            <script>
                var map = new BMap.Map('map');
                var point = new BMap.Point('<?=$detail->lng?>' || 116.404, '<?=$detail->lat?>' || 39.915);
                map.centerAndZoom(point, 16);
                map.addOverlay(new BMap.Marker(point));
                map.addControl(new BMap.NavigationControl({
                    anchor: BMAP_ANCHOR_TOP_RIGHT,
                    type: BMAP_NAVIGATION_CONTROL_SMALL
                }));
                $('.back').click(function () {
                    map.centerAndZoom(point, 16);
                });
            </script>
        </div>
        <div class="fixed">
            <h2 class="name"><?= $detail->name ?></h2>
            <div class="type">
                <?= \vendor\project\helpers\Constant::businessType()[$detail->business_type] ?>
                <span> | </span>
                <?= \vendor\project\helpers\Constant::investType()[$detail->invest_type] ?>
            </div>
            <div class="trait"><?= $detail->trait ?></div>
            <div class="progress">
                <div>项目总额: <?= $detail->budget_amount ?></div>
                <?php if (in_array($detail->status, [1, 2, 3])): ?>
                    <div>认购进度: <span>100%</span></div>
                    <div style="background-size: 100% auto"></div>
                <?php else: ?>
                    <div>认购进度: <span><?= $detail->present_amount / $detail->budget_amount * 100 ?>%</span></div>
                    <div style="background-size: <?= $detail->present_amount / $detail->budget_amount * 100 ?>% auto"></div>
                <?php endif; ?>
            </div>
            <div class="info">
                <div><?= $detail->budget_amount ?><p>项目总额</p></div>
                <div><?= in_array($detail->status, [1, 2, 3]) ? $detail->budget_amount : $detail->present_amount ?><p>
                        已认购</p></div>
                <div><?= $detail->lowest_amount ?><p>起投金额</p></div>
            </div>
            <div class="btn" data-url="/user/field/buy.html?no=<?= $detail->no ?>">立即认购</div>
        </div>
        <div class="clearBoth"></div>
    </div>
</div>
<script>
    $(function () {
        var headHeight = $('.head').height();
        var fixedHeight = $('.fixed').outerHeight();
        var fixedRight = $('.fixed').offset().left + 'px';
        var detailTop = $('.details').offset().top - headHeight - window.remSize;
        var footerTop = $('.footer').offset().top - headHeight - fixedHeight - 4 * window.remSize;
        $(window).scroll(function () {
            var scrollTop = $(this).scrollTop();
            if (scrollTop >= detailTop && scrollTop <= footerTop) {
                $('.fixed').css({
                    'position': 'fixed',
                    'left': fixedRight,
                    'right': 'auto',
                    'bottom': 'auto',
                });
            }
            if (scrollTop > footerTop) {
                $('.fixed').css({
                    'position': 'absolute',
                    'left': 'auto',
                    'right': 0,
                    'bottom': 0,
                    'top': 'auto',
                });
            }
            if (scrollTop < detailTop) {
                $('.fixed').css({
                    'position': 'static',
                    'right': 'auto',
                    'bottom': 'auto',
                });
            }
        });

        $(window).scroll();
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