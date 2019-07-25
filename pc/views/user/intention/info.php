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
                        <div class="val">
                            <button class="cut doAmount" type="button"><i class="fa fa-minus"></i></button>
                            <input type="text" name="purchase_amount" readonly autocomplete="off">
                            <button class="add doAmount" type="button"><i class="fa fa-plus" aria-hidden="true"></i></button>
                        </div>
                    </div>
                    <div class="one">
                        <label>定金金额 :</label>
                        <div class="val order_amount"></div>
                    </div>
                </div>
            </div>
            <div class="btnBox">
                <input type="hidden" name="_csrf" value="<?= Yii::$app->request->csrfToken ?>">
                <button type="submit" class="btn">确认提交</button>
            </div>
            <script>
                var min = '<?= $detail->lowest_amount ?>';
                var max = '<?= $detail->budget_amount - $detail->present_amount?>';
                var old = '<?= $model->purchase_amount ?>';
                var ratio = '<?=\vendor\project\helpers\Constant::orderRatio()?>';
                if (parseInt(old) > parseInt(max)) {
                    $('[name="purchase_amount"]').val(max);
                    $('.order_amount').text(window.format(max * ratio, 1));
                } else {
                    $('[name="purchase_amount"]').val(old);
                    $('.order_amount').text(window.format(old * ratio, 1));
                }
                $('.doAmount').click(function () {
                    if ($(this).hasClass('add')) {
                        $('[name="purchase_amount"]').val(function (i, v) {
                            if (v >= parseInt(max)) {
                                window.showMsg('最高认购金额' + max);
                                return v;
                            }
                            var purchase_amount = parseInt(v) + parseInt(min);
                            $('.order_amount').text(window.format(purchase_amount * ratio, 1));
                            return purchase_amount;
                        });
                    }
                    if ($(this).hasClass('cut')) {
                        $('[name="purchase_amount"]').val(function (i, v) {
                            if (v <= parseInt(min)) {
                                window.showMsg('最低认购金额' + min);
                                return v;
                            }
                            var purchase_amount = parseInt(v) - parseInt(min);
                            $('.order_amount').text(window.format(purchase_amount * ratio, 1));
                            return purchase_amount;
                        });
                    }
                });
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