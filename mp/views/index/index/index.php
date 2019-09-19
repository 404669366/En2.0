<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1">
    <title>首页</title>
    <link rel="stylesheet" type="text/css" href="/resources/css/index.css"/>
    <link rel="stylesheet" type="text/css" href="/swiper/swiper.min.css"/>
    <script src="/resources/js/common.js" type="text/javascript" charset="utf-8"></script>
    <script src="/swiper/swiper.min.js" type="text/javascript" charset="utf-8"></script>
    <?php \vendor\helpers\Msg::run('0.46rem') ?>
</head>
<body>

<div class="banner">
    <div class="swiper-container">
        <div class="swiper-wrapper">
            <div class="swiper-slide"><img src="/resources/img/index1.jpg"></div>
        </div>
    </div>
</div>
<script>
    new Swiper('.swiper-container', {
        loop: true,
        grabCursor: true,
        autoplay: {
            delay: 5000,
            stopOnLastSlide: false,
            disableOnInteraction: true
        }
    });
</script>

<div class="bannerIcon jump" url="/user/user/user.html"><i class="fa fa-user-o" aria-hidden="true"></i></div>
<div class="bannerInfo">
    <div class="bannerTitle">专业 / 快速 / 省心</div>
    <div class="bannerDetail">
        <p>海量场地源</p>
        <p>省心上亿能</p>
    </div>
</div>

<div class="search jump" url="/index/index/list.html?focus=1">
    <i class="fa fa-search" aria-hidden="true"></i> 搜索场地
</div>

<div class="nav">
    <div class="jump" url="/index/index/list.html?type=1">
        <span><img src="/resources/img/icon_01.png"><br>最新</span>
    </div>
    <div class="jump" url="/index/index/list.html?type=2">
        <span><img src="/resources/img/icon_02.png"><br>融资</span>
    </div>
    <div class="jump" url="/index/index/list.html?type=3">
        <span><img src="/resources/img/icon_03.png"><br>人气</span>
    </div>
    <div class="jump" url="/index/index/list.html?type=4">
        <span><img src="/resources/img/icon_04.png"><br>点击</span>
    </div>
    <div class="jump" url="/index/index/list.html?type=5">
        <span><img src="/resources/img/icon_05.png"><br>车位</span>
    </div>
</div>

<div class="toolsBox">
    <span>常用工具</span>
    <div class="tools">
        <div class="jump" url="/user/release/release-basis.html">
            <span><img src="/resources/img/tool_01.png"><br>发布场地</span>
        </div>
        <div class="jump" url="/user/user/basis-field.html">
            <span><img src="/resources/img/tool_02.png"><br>我的发布</span>
        </div>
        <div class="jump" url="/user/intention/list.html">
            <span><img src="/resources/img/tool_03.png"><br>我的意向</span>
        </div>
        <div class="jump" url="/estimate/estimate/estimate.html">
            <span><img src="/resources/img/tool_04.png"><br>收益预测</span>
        </div>
        <div class="jump" url="tel:<?=\vendor\project\helpers\Constant::serviceTel() ?>">
            <span><img src="/resources/img/tool_09.png"><br>客服电话</span>
        </div>
    </div>
</div>

<div class="recommend">
    <div class="titleBox">
        <div class="title">为您推荐</div>
        <div class="type">
            <span class="jump" url="/index/index/index.html?type=4">点击</span>
            <span class="jump" url="/index/index/index.html?type=3">人气</span>
            <span class="jump" url="/index/index/index.html?type=2">火热</span>
            <span class="jump" url="/index/index/index.html?type=1">最新</span>
        </div>
    </div>
    <div class="cont">
        <?php foreach ($recommend as $data): ?>
            <div class="one jump" url="/index/index/details.html?no=<?= $data['no'] ?>">
                <img src="<?= $data['image'] ?>">
                <div class="oneInfo">
                    <div class="title"><?= $data['title'] ?></div>
                    <div class="info"><?= $data['full_name'] ?></div>
                    <div class="info"><?= $data['minimal'] ?>￥起投 / <?= $data['park'] ?>车位</div>
                    <div class="price"><?= $data['budget'] ?>￥</div>
                </div>
            </div>
        <?php endforeach; ?>
    </div>
    <div class="more jump">查看更多场地</div>
</div>
<script>
    var type = getParams('type', 1);
    $('.recommend>.titleBox>.type').find('span').removeClass('active').each(function (k, v) {
        if ($(v).attr('url') === ('/index/index/index.html?type=' + type)) {
            $(v).addClass('active');
            $('.recommend>.more').attr('url', '/index/index/list.html?type=' + type);
        }
    });
</script>

<div class="foot">
    <span>Copyright © 2018 en.ink, All Rights Reserved.<br>四川亿能天成科技有限公司</span>
</div>

</body>
</html>