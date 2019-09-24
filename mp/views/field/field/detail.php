<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1">
    <title>场地详情</title>
    <link rel="stylesheet" type="text/css" href="/resources/css/details.css"/>
    <link rel="stylesheet" type="text/css" href="/swiper/idangerous.swiper.css"/>
    <script src="/resources/js/common.js" type="text/javascript" charset="utf-8"></script>
    <script src="/swiper/idangerous.swiper.js" type="text/javascript" charset="utf-8"></script>
    <script src="/resources/js/map.js" type="text/javascript" charset="utf-8"></script>
    <?php \vendor\helpers\Msg::run('0.46rem') ?>
</head>
<body>
<div class="header">
    <a href="javascript:history.back(-1)" class="pic">
        <i class="fa fa-angle-left" aria-hidden="true"></i>
        <img src="/resources/img/logo.png"/>
    </a>
    <a href="/user/user/user.html" class="pic">
        <i class="fa fa-user-o" aria-hidden="true"></i>
    </a>
</div>

<div class="banner">
    <div class="swiper-container">
        <div class="swiper-wrapper">
            <?php foreach (explode(',', $model->image) as $v): ?>
                <div class="swiper-slide"><img src="<?= $v ?>"></div>
            <?php endforeach; ?>
        </div>
    </div>
    <div class="pagination"></div>
</div>
<script>
    new Swiper('.swiper-container', {
        pagination: '.pagination',
        loop: true,
        grabCursor: true,
        paginationClickable: true
    });
</script>

<div class="info">
    <div class="title"><?= $model->title ?></div>
    <div class="priceTitle">
        <div><span><p><?= $model->budget ?>￥</p>总额</span></div>
        <div><span><p><?= $model->minimal ?>￥</p>起投</span></div>
        <div><span><p><?= $model->park ?></p>车位</span></div>
    </div>
    <div class="skill" style="background-size: <?= ((float)$model->financing_ratio) * 100 ?>% auto">
        当前进度: <?= ((float)$model->financing_ratio) * 100 ?>%
    </div>
    <div class="heart">
        <?php if (!\vendor\en\Follow::isFollow($model->no)): ?>
            <span class="jump" url="/user/follow/follow.html?no=<?= $model->no ?>">
                <i class="fa fa-heart-o" aria-hidden="true"></i>
            </span>
        <?php else: ?>
            <span class="jump" url="/user/follow/cancel.html?no=<?= $model->no ?>" style="color: #fa604c">
                <i class="fa fa-heart" aria-hidden="true"></i>
            </span>
        <?php endif; ?>
    </div>
</div>
<?php if ($list): ?>
    <div class="detail">
        <span>投资概况</span>
        <div class="box">
            <?php foreach ($list as $k => $v): ?>
                <div class="ratio">
                    <span><?= substr_replace($v['tel'], '*****', 3, 5) ?></span>
                    <div class="bar"
                         style="background-size: <?= round(($v['money'] / $model->budget) * 100, 2) ?>% auto">
                        <?= round(($v['money'] / $model->budget) * 100, 2) ?>%
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
    </div>
<?php endif; ?>

<div class="detail moreBefore">
    <span>场地介绍</span>
    <div class="intro">
        <?= $model->intro ?>
    </div>
</div>

<div class="detail moreData">
    <span>场地配置</span>
    <img class="images" src="<?= $model->configure_photo ?: '/resources/img/a1.png' ?>"/>
</div>

<div class="detail moreData">
    <span>预算报表</span>
    <?php if ($model->budget_photo): ?>
        <?php foreach (explode(',', $model->budget_photo) as $k => $v): ?>
            <img class="images" src="<?= $v ?>">
        <?php endforeach; ?>
    <?php else: ?>
        <img class="images" src="/resources/img/a1.png">
    <?php endif; ?>
</div>

<div class="detail moreData">
    <span>施工图纸</span>
    <?php if ($model->field_drawing): ?>
        <?php foreach (explode(',', $model->field_drawing) as $k => $v): ?>
            <img class="images" src="<?= $v ?>">
        <?php endforeach; ?>
    <?php else: ?>
        <img class="images" src="/resources/img/a1.png">
    <?php endif; ?>
</div>

<div class="detail moreData">
    <span>场地备案</span>
    <?php if ($model->record_photo): ?>
        <?php foreach (explode(',', $model->record_photo) as $k => $v): ?>
            <img class="images" src="<?= $v ?>">
        <?php endforeach; ?>
    <?php else: ?>
        <img class="images" src="/resources/img/a1.png">
    <?php endif; ?>
</div>

<div class="more">更多场地信息</div>
<script>
    $('.more').click(function () {
        if ($(this).text() === '更多场地信息') {
            $('.moreData').fadeIn();
            $(this).text('收起更多信息');
        } else {
            $(this).text('更多场地信息');
            $('body').animate({scrollTop: $('.moreBefore').position().top - $('.header').height() - $('.broker').height()}, 800);
            setTimeout(function () {
                $('.moreData').hide();
            }, 801);
        }
    });
</script>
<div id="map"></div>
<script>
    var map = new BMap.Map('map');
    var point = new BMap.Point('<?=$model->lng?>' || 116.404, '<?=$model->lat?>' || 39.915);
    map.centerAndZoom(point, 16);
    map.addOverlay(new BMap.Marker(point));
    map.addControl(new BMap.NavigationControl({
        anchor: BMAP_ANCHOR_TOP_RIGHT,
        type: BMAP_NAVIGATION_CONTROL_SMALL
    }));
</script>

<div class="detail">
    <span>更多推荐</span>
    <?php foreach ($recommends as $data): ?>
        <div class="one jump" url="/index/index/details.html?no=<?= $data['no'] ?>">
            <img src="<?= $data['image'][0] ?>">
            <div class="oneInfo">
                <div class="title"><?= $data['title'] ?></div>
                <div class="con"><?= $data['full_name'] ?></div>
                <div class="con"><?= $data['minimal'] ?>￥起投 / <?= $data['park'] ?>车位</div>
                <div class="price"><?= $data['budget'] ?>￥</div>
            </div>
        </div>
    <?php endforeach; ?>
</div>

<div class="help">
    <span>
        空闲场地如何处理？我们可以帮到你
        <div class="jump" url="/user/release/release-basis.html">发布我的场地</div>
    </span>
</div>

<div class="broker">
    <div class="head jump" url="/index/index/cobber-field.html?cobber_id=<?= $model->cobber_id ?>">
        <span><img src="/resources/img/agent_none.png"/><br><?= $model->cobber->ident->name ?></span>
    </div>
    <div class="btns">
        <span>
            <div class="btn have">有意向</div>
            &emsp;
            <div class="btn jump" url="tel:<?= $model->cobber->tel ?>" style="background: #3072F6">打电话</div>
        </span>
    </div>
</div>

<div class="intentModal">
    <form action="/user/intention/add.html?no=<?= $model->no ?>" method="post">
        <input type="hidden" name="_csrf" value="<?= Yii::$app->request->csrfToken ?>">
        <div class="intentModalTitle">请填写意向金额</div>
        <input class="intent" name="money" type="text">
        <div class="intentModalBtns">
            <button type="submit">确认</button>
            &emsp;&emsp;
            <button type="button" class="intentModalClose">取消</button>
        </div>
    </form>
</div>
<script>
    $('.have').click(function () {
        window.modal.open('.intentModal');
    });
    $('.intentModalClose').click(function () {
        window.modal.close('.intentModal');
    });
</script>

<div class="foot" style="margin-bottom: 2.01rem">
    <span>Copyright © 2018 en.ink, All Rights Reserved.<br>四川亿能天成科技有限公司</span>
</div>
</body>
</html>