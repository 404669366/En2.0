<!DOCTYPE html>
<html lang="zh">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width,initial-scale=1.0">
    <title>亿能科技</title>
    <link rel="shortcut icon" href="/favicon.ico">
    <link href="/css/form.css" rel="stylesheet">
    <script src="/js/common.js" type="text/javascript" charset='utf-8'></script>
    <script src="/js/upload.min.js" type="text/javascript" charset='utf-8'></script>
</head>
<body>
<div class="head">
    <div class="headContent">
        <img data-url="/<?= Yii::$app->params['defaultRoute'] ?>.html" src="/img/logo.png" alt="四川亿能天成新能源logo">
        <ul>
            <li><a href="/index/index/index.html">首页<span></span></a></li>
            <li><a href="/field/field/list.html">项目<span></span></a></li>
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
                <li class="active" title="个人中心">
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
    <form method="post">
        <input type="hidden" name="_csrf" value="<?= Yii::$app->request->csrfToken ?>">
        <h1>Upload Prove</h1>
        <h3>上传凭证</h3>
        <div class="line">
            <div class="line1">
                <label>打款凭条(必填)</label>
                <div class="voucher" data-max="1" data-name="voucher" deta-read="0"></div>
            </div>
            <div class="line1">
                <label>合同照片(必填)</label>
                <div class="contract" data-max="4" data-name="contract" deta-read="0"></div>
            </div>
            <div class="line1">
                <label>备注(选填)</label>
                <textarea name="remark" placeholder="您是否还有其他补充说明，或对我们的期望"><?= $model->remark ?></textarea>
            </div>
        </div>
        <button type="submit">确认提交</button>
    </form>
    <script>
        uploadImg('.voucher', '<?=$model->voucher?>');
        uploadImg('.contract', '<?=$model->contract?>');
        $('[type="submit"]').click(function () {
            if (!$('[name="voucher"]').val()) {
                window.showMsg('请上传打款凭条照片');
                return false;
            }
            if (!$('[name="contract"]').val()) {
                window.showMsg('请上传合同照片');
                return false;
            }
            return true;
        });
    </script>
</div>
<div class="footer">
    <div class="info">
        <div>
            <h4>联系我们</h4>
            <p>客服电话：028-67874259</p>
            <p>商务沟通：xl@en.ink</p>
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