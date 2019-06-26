<!DOCTYPE html>
<html lang="zh">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width,initial-scale=1.0">
    <title>亿能科技</title>
    <link rel="shortcut icon" href="/favicon.ico">
    <link href="/css/field.css" rel="stylesheet">
    <script src="/js/common.js" type="text/javascript" charset='utf-8'></script>
</head>
<body>
<div class="head">
    <div class="headContent">
        <img data-url="/<?= Yii::$app->params['defaultRoute'] ?>.html" src="/img/logo.png" alt="四川亿能天成新能源logo">
        <ul>
            <li><a href="/index/index/index.html">首页<span></span></a></li>
            <li class="active"><a href="/field/field/list.html">项目<span></span></a></li>
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
<div class="center">
    <div class="list">
        <div class="title">
            <h2>Investment</h2>
            <p>投资美好生活</p>
        </div>
        <div class="search">
            <div class="select">
                项目类型:
                <span><span class="now">全部</span><i class="fa fa-caret-down" aria-hidden="true"></i></span>
                <ul>
                    <li data-key="business_type" data-val="">全部</li>
                    <?php foreach (\vendor\project\helpers\Constant::businessType() as $k => $v): ?>
                        <li data-key="business_type" data-val="<?= $k ?>"><?= $v ?></li>
                    <?php endforeach; ?>
                </ul>
            </div>
            <div class="select">
                投资类型:
                <span><span class="now">全部</span><i class="fa fa-caret-down" aria-hidden="true"></i></span>
                <ul>
                    <li data-key="invest_type" data-val="">全部</li>
                    <?php foreach (\vendor\project\helpers\Constant::investType() as $k => $v): ?>
                        <li data-key="invest_type" data-val="<?= $k ?>"><?= $v ?></li>
                    <?php endforeach; ?>
                </ul>
            </div>
            <div class="searchBtn" data-url=""><i class="fa fa-search" aria-hidden="true"></i></div>
        </div>
        <script>

            var business_type = window.getParams('business_type', '');
            $('.select').find('[data-key="business_type"]').each(function (k, v) {
                if ($(v).data('val') == business_type) {
                    $(v).parents('.select').find('.now').text($(v).text());
                }
            });

            var invest_type = window.getParams('invest_type', '');
            $('.select').find('[data-key="invest_type"]').each(function (k, v) {
                if ($(v).data('val') == invest_type) {
                    $(v).parents('.select').find('.now').text($(v).text());
                }
            });

            $('.select>span').click(function () {
                $(this).next().slideToggle(200);
                $(this).find('i').toggleClass('fa-caret-down').toggleClass('fa-caret-up');
            });

            $('.select>ul>li').on('click', function () {
                $(this).parent().slideToggle(200);
                $(this).parents('.select').find('i').toggleClass('fa-caret-down').toggleClass('fa-caret-up');
                $(this).parents('.select').find('.now').text($(this).text());
                var url = window.location.href;
                var key = $(this).data('key');
                var val = $(this).data('val');
                var last = window.getParams(key);
                if (last !== null) {
                    window.location.href = url.replace(key + '=' + last, key + '=' + val);
                } else {
                    var mark = '&';
                    if (url === 'http://pc.en.com/field/field/list.html') {
                        mark = '?';
                    }
                    window.location.href = url + mark + key + '=' + val;
                }
            })
        </script>
        <?php if ($data): ?>
            <ul class="data">
                <?php foreach ($data as $v): ?>
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
                    </li>
                <?php endforeach; ?>
                <div class="clearBoth"></div>
            </ul>
        <?php else: ?>
            <div class="no-data">暂时没有该类项目</div>
        <?php endif; ?>
        <ul class="page" data-getby="http://pc.en.com/field/field/list.html">
            <li data-do="first">首&emsp;页</li>
            <li data-do="prev">上一页</li>
            <li data-do="next">下一页</li>
        </ul>
    </div>
</div>
<script>
    $('.list>.data>li').hover(
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