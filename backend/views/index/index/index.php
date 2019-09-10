<?php
\app\assets\FrameAsset::register($this);
$this->beginPage();
?>
<!DOCTYPE html>
<html lang="zh">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="renderer" content="webkit">
    <title>亿能科技</title>
    <!--[if lt IE 9]>
    <meta http-equiv="refresh" content="0;ie.html"/>
    <![endif]-->
    <link rel="shortcut icon" href="/favicon.ico">
    <?php $this->head() ?>
</head>
<body class="fixed-sidebar full-height-layout gray-bg">
<?php $this->beginBody(); ?>
<?php $this->endBody(); ?>
<div id="wrapper">
    <!--左侧导航开始-->
    <nav class="navbar-default navbar-static-side" role="navigation">
        <div class="nav-close"><i class="fa fa-times-circle"></i>
        </div>
        <div class="sidebar-collapse">
            <ul class="nav" id="side-menu">
                <li class="nav-header">
                    <div class="dropdown" style="margin: 0.5rem 0">
                        <img alt="image" class="img-circle imgPre" src="<?= $data['logo'] ?>"/>
                        <span class="text-muted text-xs block" style="margin-top: 0.4rem">
                            <?= $data['company'] ?>
                        </span>
                        <span class="text-muted text-xs block">
                            <?= $data['job'] ?>
                        </span>
                        <span class="text-muted text-xs block dropdown-toggle" data-toggle="dropdown">
                            <strong class="font-bold"><?= $data['tel'] ?></strong>
                            <b class="caret"></b>
                        </span>
                        <ul class="dropdown-menu animated fadeInRight m-t-xs updatePasswordBox">
                            <li>
                                <a class="J_menuItem updatePassword" href="/<?= Yii::$app->params['updateRoute'] ?>">修改密码</a>
                            </li>
                        </ul>
                        <script>
                            $('.updatePassword').click(function () {
                                $('.dropdown').removeClass('open');
                                $('.dropdown-toggle').attr('aria-expanded', false);
                            });
                        </script>
                    </div>
                </li>
                <?= $data['menus'] ?>
            </ul>
        </div>
    </nav>
    <!--左侧导航结束-->
    <!--右侧部分开始-->
    <div id="page-wrapper" class="gray-bg dashbard-1">
        <div class="row border-bottom">
            <nav class="navbar navbar-static-top" role="navigation" style="margin-bottom: 0">
                <div class="navbar-header">
                    <a class="navbar-minimalize minimalize-styl-2 btn btn-primary">
                        <i class="fa fa-bars"></i>
                    </a>
                </div>
                <ul class="nav navbar-top-links navbar-right">
                    <li class="dropdown">
                        <a class="right-sidebar-toggle" aria-expanded="false">
                            <i class="fa fa-tasks"></i> 主题
                        </a>
                    </li>
                </ul>
            </nav>
        </div>
        <div class="row content-tabs">
            <button class="roll-nav roll-left J_tabLeft"><i class="fa fa-backward"></i></button>
            <nav class="page-tabs J_menuTabs">
                <div class="page-tabs-content">
                    <a href="javascript:;" class="active J_menuTab"
                       data-id="/<?= Yii::$app->params['firstRoute'] ?>">首页</a>
                </div>
            </nav>
            <button class="roll-nav roll-right J_tabRight"><i class="fa fa-forward"></i></button>
            <div class="btn-group roll-nav roll-right">
                <button class="dropdown J_tabClose" data-toggle="dropdown">
                    关闭操作<span class="caret"></span>
                </button>
                <ul role="menu" class="dropdown-menu dropdown-menu-right">
                    <li class="J_tabShowActive"><a>定位当前选项卡</a></li>
                    <li class="divider"></li>
                    <li class="J_tabCloseAll"><a>关闭全部选项卡</a></li>
                    <li class="J_tabCloseOther"><a>关闭其他选项卡</a></li>
                </ul>
            </div>
            <a href="/<?= Yii::$app->params['logoutRoute'] ?>" class="roll-nav roll-right J_tabExit"><i
                        class="fa fa fa-sign-out"></i> 退出</a>
        </div>
        <div class="row J_mainContent" id="content-main">
            <iframe class="J_iframe" name="iframe0" width="100%" height="100%"
                    src="/<?= Yii::$app->params['firstRoute'] ?>" frameborder="0"
                    data-id="/<?= Yii::$app->params['firstRoute'] ?>" seamless></iframe>
        </div>
        <script>
            $('.page-tabs-content').on('dblclick', '.J_menuTab', function () {
                var id = $(this).attr('data-id');
                $('#content-main').find('.J_iframe[data-id="' + id + '"]').attr('src', id);
            });
        </script>
        <div class="footer">
            <div class="pull-right">&copy; 2018 - 2099</div>
        </div>
    </div>
    <!--右侧部分结束-->
    <!--右侧边栏开始-->
    <div id="right-sidebar">
        <div class="sidebar-container">
            <div class="skin-setttings">
                <div class="title">主题设置</div>
                <div class="setings-item">
                    <span>收起左侧菜单</span>
                    <div class="switch">
                        <div class="onoffswitch">
                            <input type="checkbox" name="collapsemenu" class="onoffswitch-checkbox" id="collapsemenu">
                            <label class="onoffswitch-label" for="collapsemenu">
                                <span class="onoffswitch-inner"></span>
                                <span class="onoffswitch-switch"></span>
                            </label>
                        </div>
                    </div>
                </div>
                <div class="setings-item">
                    <span>固定顶部</span>
                    <div class="switch">
                        <div class="onoffswitch">
                            <input type="checkbox" name="fixednavbar" class="onoffswitch-checkbox" id="fixednavbar">
                            <label class="onoffswitch-label" for="fixednavbar">
                                <span class="onoffswitch-inner"></span>
                                <span class="onoffswitch-switch"></span>
                            </label>
                        </div>
                    </div>
                </div>
                <div class="setings-item">
                    <span>固定宽度</span>
                    <div class="switch">
                        <div class="onoffswitch">
                            <input type="checkbox" name="boxedlayout" class="onoffswitch-checkbox" id="boxedlayout">
                            <label class="onoffswitch-label" for="boxedlayout">
                                <span class="onoffswitch-inner"></span>
                                <span class="onoffswitch-switch"></span>
                            </label>
                        </div>
                    </div>
                </div>
                <div class="title">皮肤选择</div>
                <div class="setings-item default-skin nb">
                    <span class="skin-name ">
                        <a href="#" class="s-skin-0">默认皮肤</a>
                    </span>
                </div>
                <div class="setings-item blue-skin nb">
                    <span class="skin-name ">
                        <a href="#" class="s-skin-1">蓝色主题</a>
                    </span>
                </div>
                <div class="setings-item yellow-skin nb">
                    <span class="skin-name ">
                        <a href="#" class="s-skin-3">黄色/紫色主题</a>
                    </span>
                </div>
            </div>
        </div>
    </div>
    <!--右侧边栏结束-->
</div>
</body>
</html>
<?php $this->endPage() ?>
