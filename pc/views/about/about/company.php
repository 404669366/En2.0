<!DOCTYPE html>
<html lang="zh">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width,initial-scale=1.0">
    <title>亿能科技</title>
    <link rel="shortcut icon" href="/favicon.ico">
    <link href="/css/about.css" rel="stylesheet">
    <script src="/js/common.js" type="text/javascript" charset='utf-8'></script>
</head>
<body>
<div class="head">
    <div class="headContent">
        <img data-url="/<?= Yii::$app->params['defaultRoute'] ?>.html" src="/img/logo.png" alt="四川亿能天成新能源logo">
        <ul>
            <li><a href="/index/index/index.html">首页<span></span></a></li>
            <li><a href="/field/field/list.html">项目<span></span></a></li>
            <li><a href="/news/news/list.html">新闻<span></span></a></li>
            <li class="nav active" onselectstart="return false">
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
<div class="center company">
    <div class="intro">
        <p>
            四川亿能天成科技有限公司（简称亿能科技），是全球首家专业的新能源充电站投资平台，响应国家号召，致力新能源产业建设，倡导清洁能源，保护生态环境。始终专注于新能源投资、新能源建设、新能源运营、新能源维护等领域，服务于新能源充电站项目融资及空闲场地建站需求，高效链接实体项目和用户的“投资及消费”需求。
            创立两年来，亿能科技已经融资建设多个充电站项目，融资总额超过1千万，拥有完善的建站体系，运维体系，售后体系和丰富的经验。
        </p>
    </div>
    <div class="modelBox">
        <div class="model">
            <h1>Advantage</h1>
            <h3>我们的优势</h3>
            <ul class="detail advantages">
                <li>
                    <img src="/img/about_icon_1.png" alt="">
                    <h4>知名风投</h4>
                    <p>
                        获得雷军领衔的顺为资本<br>
                        DCM中国、英诺天使、分享投资<br>
                        等顶级风险投资机构注资
                    </p>
                </li>
                <li>
                    <img src="/img/about_icon_2.png" alt="">
                    <h4>严选品牌</h4>
                    <p>
                        垂直行业优质品牌<br>
                        项目入驻多层把关
                    </p>
                </li>
                <li>
                    <img src="/img/about_icon_3.png" alt="">
                    <h4>严格风控</h4>
                    <p>
                        专业风控团队，PMS数据追踪<br>
                        知名律所支持，银行资金存管
                    </p>
                </li>
                <li>
                    <img src="/img/about_icon_4.png" alt="">
                    <h4>专享体验</h4>
                    <p>
                        用户参与感强，定期股东聚会<br>
                        积分兑换礼品，收益平台通用
                    </p>
                </li>
                <div class="clearBoth"></div>
            </ul>
        </div>
        <div class="model">
            <h1>Growth</h1>
            <h3>我们的成长</h3>
            <div class="detail growth">
                <img src="/img/growth_1.png" alt="">
                <img src="/img/growth_2.png" alt="">
                <p>
                    从2015年起，亿能科技致力于为用户提供优秀的投资机会和美好的生活体验<br>
                    取得了好评和快速的市场成长
                </p>
                <div class="clearBoth"></div>
            </div>
        </div>
        <div class="model">
            <h1>History</h1>
            <h3>我们的历程</h3>
            <div class="detail history">
                <div class="years">
                    <h5>2019年</h5>
                    <ul>
                        <li>
                            <div class="time">07月</div>
                            <div class="content">
                                <div class="line"></div>
                                <div class="o"></div>
                                多彩投连续获得多个突破，完成项目800个，平台参与用户超过10万人，成功退出项目金额累计超过3个亿
                            </div>
                            <div class="clearBoth"></div>
                        </li>
                        <li>
                            <div class="time">06月</div>
                            <div class="content">
                                <div class="line"></div>
                                <div class="o"></div>
                                多彩投连续获得多个突破，完成项目800个，平台参与用户超过10万人，成功退出项目金额累计超过3个亿
                            </div>
                            <div class="clearBoth"></div>
                        </li>
                        <li>
                            <div class="time">05月</div>
                            <div class="content">
                                <div class="line"></div>
                                <div class="o"></div>
                                多彩投连续获得多个突破，完成项目800个，平台参与用户超过10万人，成功退出项目金额累计超过3个亿
                            </div>
                            <div class="clearBoth"></div>
                        </li>
                        <li>
                            <div class="time">04月</div>
                            <div class="content">
                                <div class="line"></div>
                                <div class="o"></div>
                                多彩投连续获得多个突破，完成项目800个，平台参与用户超过10万人，成功退出项目金额累计超过3个亿
                            </div>
                            <div class="clearBoth"></div>
                        </li>
                    </ul>
                </div>
                <div class="years">
                    <h5>2018年</h5>
                    <ul>
                        <li>
                            <div class="time">11月</div>
                            <div class="content">
                                <div class="line"></div>
                                <div class="o"></div>
                                多彩投连续获得多个突破，完成项目800个，平台参与用户超过10万人，成功退出项目金额累计超过3个亿
                            </div>
                            <div class="clearBoth"></div>
                        </li>
                        <li>
                            <div class="time">10月</div>
                            <div class="content">
                                <div class="line"></div>
                                <div class="o"></div>
                                多彩投连续获得多个突破，完成项目800个，平台参与用户超过10万人，成功退出项目金额累计超过3个亿
                            </div>
                            <div class="clearBoth"></div>
                        </li>
                    </ul>
                </div>
            </div>
        </div>
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