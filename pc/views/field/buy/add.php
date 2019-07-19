<!DOCTYPE html>
<html lang="zh">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width,initial-scale=1.0">
    <title>亿能科技</title>
    <link rel="shortcut icon" href="/favicon.ico">
    <link href="/css/buy.css" rel="stylesheet">
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
    <div class="intention">
        <h1>Subscribe</h1>
        <h3>认购</h3>
        <form method="post">
            <div class="form">
                <div class="infoBox">
                    <img src="<?= explode(',', $detail->images)[0] ?>" alt="亿能天成新能源场站-<?= $detail->no ?>图片">
                    <div class="info">
                        <h5>场站 : <?= $detail->no ?></h5>
                        <p>
                            场站类型 :
                            <?= \vendor\project\helpers\Constant::businessType()[$detail->business_type] ?>
                            <span>|</span>
                            <?= \vendor\project\helpers\Constant::investType()[$detail->invest_type] ?>
                        </p>
                        <p></p>
                        <p></p>
                        <p>
                            项目总额/已投 :
                            <?= $detail->budget_amount ?>
                            <span>|</span>
                            <?= $detail->present_amount ?>
                        </p>
                    </div>
                    <div class="price">
                        起投 : <span><?= $detail->lowest_amount ?></span>
                    </div>
                </div>
                <div class="buyCount">
                    <div class="one">
                        <label>认购金额 :</label>
                        <input type="text" name="purchase_amount" autocomplete="off">
                    </div>
                    <div class="one">
                        <label>定金金额 :</label>
                        <?php
                        $now = $detail->lowest_amount * \vendor\project\helpers\Constant::orderRatio();
                        ?>
                        <div class="val order_amount"><?= $now ?></div>
                    </div>
                </div>
            </div>
            <div class="btnBox">
                <input type="hidden" name="_csrf" value="<?= Yii::$app->request->csrfToken ?>">
                <input type="hidden" name="order_amount" value="<?= $now ?>">
                <input type="hidden" name="part_ratio" value="<?= $detail->lowest_amount / $detail->budget_amount ?>">
                <input type="hidden" name="field_id" value="<?= $detail->id ?>">
                <input type="hidden" name="source" value="2">
                <input type="hidden" name="commissioner_id" value="<?= $detail->commissioner_id ?>">
                <input type="hidden" name="user_id" value="<?= Yii::$app->user->id ?>">
                <input type="hidden" name="created_at" value="<?= time() ?>">
                <button type="submit" class="btn">确认提交</button>
            </div>
            <script>
                var all = '<?= $detail->budget_amount ?>';
                var min = '<?= $detail->lowest_amount ?>';
                var max = '<?= $detail->budget_amount - $detail->present_amount?>';
                var ratio = '<?=\vendor\project\helpers\Constant::orderRatio()?>';
                $('[name="purchase_amount"]').focus().val(min).on('input propertychange', function () {
                    var purchase = parseInt($(this).val() || 0);
                    $(this).val(purchase);
                    var now = window.format(purchase * ratio, 1);
                    $('[name="order_amount"]').val(now);
                    $('.order_amount').text(now);
                    $('[name="part_ratio"]').val(purchase / all);
                });
                $('.btn').click(function () {
                    var purchase = parseInt($('[name="purchase_amount"]').val());
                    if (purchase > parseInt(max)) {
                        window.showMsg('最高认购金额' + max);
                        return false;
                    }
                    if (purchase < parseInt(min)) {
                        window.showMsg('最低认购金额' + min);
                        return false;
                    }
                    return true;
                })
            </script>
        </form>
    </div>
</div>
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
            <a href="/field/create/create.html">发起项目</a><br/>
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