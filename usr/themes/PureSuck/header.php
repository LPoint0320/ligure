<!DOCTYPE HTML>
<html lang="zh-CN">

<head>
    <meta charset="<?= $this->options->charset(); ?>">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="renderer" content="webkit">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <?php $this->header(); ?>
    <?php if ($this->is('post') || $this->is('page')): ?>
        <link rel="canonical" href="<?php $this->permalink(); ?>">
    <?php endif; ?>

    <title>
        <?php $this->archiveTitle([
            'category' => _t('鍒嗙被 %s 涓嬬殑鏂囩珷'),
            'search' => _t('鍖呭惈鍏抽敭瀛?%s 鐨勬枃绔?),
            'tag' => _t('鏍囩 %s 涓嬬殑鏂囩珷'),
            'author' => _t('%s 鍙戝竷鐨勬枃绔?)
        ], '', ' - '); ?>
        <?= $this->options->title(); ?>
    </title>

    <?php generateDynamicCSS(); ?>

    <script>
        window.THEME_URL = '<?= $this->options->themeUrl; ?>';
    </script>
    <?php outputPSRuntimeConfigScript($this); ?>

    <!-- 涓婚闃查棯鐑佽剼鏈?-->
    <script>
        (function () {
            const cookieMatch = document.cookie.match(/(?:^|;)\s*theme=([^;]+)/), cookieTheme = cookieMatch ? cookieMatch[1] : null;
            const localTheme = localStorage.getItem('theme');
            let initialTheme = cookieTheme || localTheme || 'auto';
            if (initialTheme === 'auto') {
                initialTheme = window.matchMedia('(prefers-color-scheme:dark)').matches ? 'dark' : 'light';
            }
            document.documentElement.setAttribute('data-theme', initialTheme);
            try {
                const canAnimateEnter = window.matchMedia && !window.matchMedia('(prefers-reduced-motion:reduce)').matches;

                if (canAnimateEnter) {
                    <?php if ($this->is('index') || $this->is('archive')): ?>
                        // 鍒楄〃棣栧睆娓愬叆灞炰簬鍩虹鍔ㄦ晥锛屼笉渚濊禆 Swup 鏄惁鍚敤
                        document.documentElement.classList.add('ps-preload-list-enter');
                    <?php elseif ($this->is('post')): ?>
                        document.documentElement.classList.add('ps-preload-post-enter');
                    <?php elseif ($this->is('page')): ?>
                        document.documentElement.classList.add('ps-preload-page-enter');
                    <?php endif; ?>
                }
            } catch (e) { }
        })();
    </script>

    <!-- 鎬ц兘浼樺寲锛欳SS 棰勫姞杞?-->
    <link rel="preload" href="<?= $this->options->themeUrl('css/PureSuck_Style.css'); ?>" as="style">
    <link rel="stylesheet" href="<?= $this->options->themeUrl('css/PureSuck_Style.css'); ?>">

    <link rel="preload" href="<?= $this->options->themeUrl('css/fontello.css'); ?>" as="style">
    <link rel="stylesheet" href="<?= $this->options->themeUrl('css/fontello.css'); ?>">

    <!-- ICON Setting -->
    <link rel="icon"
        href="<?= isset($this->options->logoUrl) && $this->options->logoUrl ? $this->options->logoUrl : $this->options->themeUrl . '/images/avatar.ico'; ?>"
        type="image/x-icon">

    <!-- 鍏抽敭CSS鍚屾鍔犺浇锛堥伩鍏岶OUC锛?-->
    <link rel="stylesheet" href="<?php $this->options->themeUrl('/css/PureSuck_Module.css'); ?>">
    <link rel="stylesheet" href="<?php $this->options->themeUrl('/css/MoxDesign.css'); ?>">
    <link rel="stylesheet" href="<?php $this->options->themeUrl('/css/animations/index.css'); ?>">
    <link rel="stylesheet" href="<?php $this->options->themeUrl('/css/OwO.min.css'); ?>">

    <!-- 鏍囬绾挎潯 -->
    <?php if ($this->options->postTitleAfter != 'off'): ?>
        <style>
            .post-title::after {
                content: "";
                bottom:
                    <?php echo $this->options->postTitleAfter == 'wavyLine' ? '-5px' : '5px'; ?>
                ;
                left:
                    <?php echo '0'; ?>
                ;
                <?php if ($this->options->postTitleAfter == 'boldLine'): ?>
                    width:
                        <?php echo '58px'; ?>
                    ;
                    height:
                        <?php echo '11px'; ?>
                    ;
                <?php elseif ($this->options->postTitleAfter == 'wavyLine'): ?>
                    width:
                        <?php echo '106px'; ?>
                    ;
                    height:
                        <?php echo '12px'; ?>
                    ;
                    mask:
                        <?php echo "url('data:image/svg+xml;utf8,<svg xmlns=\"http://www.w3.org/2000/svg\" width=\"40\" height=\"10\" viewBox=\"0 0 40 10\" preserveAspectRatio=\"none\"><path d=\"M0 5 Q 10 0, 20 5 T 40 5\" stroke=\"black\" stroke-width=\"2\" fill=\"transparent\"/></svg>') repeat-x"; ?>
                    ;
                    mask-size:
                        <?php echo '40px 12px'; ?>
                    ;
                <?php elseif ($this->options->postTitleAfter == 'handDrawn'): ?>
                    /* 娣诲姞鎵嬬粯椋庢牸鐨勬牱寮?*/
                    /* 杩欓噷鍙互娣诲姞鍏蜂綋鐨勬墜缁橀鏍肩殑鏍峰紡 */
                <?php endif; ?>
            }
        </style>
    <?php endif; ?>

    <!-- JS寮曞叆锛氭寜浼樺厛绾у垎缁勫姞杞斤紙鎬ц兘浼樺寲鐗堬級 -->

    <!-- 楂樹紭鍏堢骇锛氭牳蹇冩ā鍧楋紙棣栧睆蹇呴渶锛?-->
    <script defer src="<?php $this->options->themeUrl('/js/PureSuck_Core.js'); ?>"></script>
    <script defer src="<?php $this->options->themeUrl('/js/PureSuck_Global.js'); ?>"></script>
    <script defer src="<?php $this->options->themeUrl('/js/PureSuck_Module.js'); ?>"></script>
    <script defer src="<?php $this->options->themeUrl('/js/MoxDesign.js'); ?>"></script>

        <!-- Swup 4锛氶〉闈㈣繃娓″姩鐢?-->
        <script defer src="<?php getStaticURL('Swup.umd.min.js'); ?>"></script>
        <script defer src="<?php $this->options->themeUrl('/js/lib/Swup/scroll-plugin.js'); ?>"></script>
        <script defer src="<?php $this->options->themeUrl('/js/lib/Swup/preload-plugin.js'); ?>"></script>
        <script defer src="<?php $this->options->themeUrl('/js/lib/Swup/head-plugin.js'); ?>"></script>
    <script defer src="<?php $this->options->themeUrl('/js/PureSuck_Swup.js'); ?>"></script>

    <!-- 浣庝紭鍏堢骇锛氭寜闇€鍔犺浇锛堣瘎璁哄尯锛?-->
    <?php if ($this->options->PjaxScript): ?>
        <script defer>
            // 娉ㄥ唽鐢ㄦ埛鑷畾涔夊洖璋冿紙Swup page:view 鍚庢墽琛岋級
            window.pjaxCustomCallback = function () {
                <?= $this->options->PjaxScript; ?>
            };
        </script>
    <?php endif; ?>
</head>

<body>
    <div class="wrapper">
        <header class="header" data-js="header">
            <div class="wrapper header-wrapper header-title">
                <a href="<?= $this->options->logoIndexUrl ?: $this->options->siteUrl; ?>" class="avatar-link"
                    aria-label="鍗氫富鍚嶅瓧">
                    <span class="el-avatar el-avatar--circle avatar-hover-effect">
                        <img src="<?= $this->options->logoIndex; ?>" style="object-fit:cover;" alt="鍗氫富澶村儚" width="120"
                            height="120" data-name="鍗氫富鍚嶅瓧" draggable="false" fetchpriority="high" decoding="async"
                            loading="eager">
                    </span>
                </a>
                <div class="header-title">
                    <?= $this->options->titleIndex(); ?>
                </div>
                <p itemprop="description" class="header-item header-about">
                    <?= $this->options->customDescription ?: '銉偗銉偗'; ?>
                </p>
                <div class="nav header-item left-side-custom-code">
                    <?= $this->options->leftSideCustomCode ?: ''; ?>
                </div>
                <div class="nav header-item header-credit">
                    <a href="https://ligure.cc" target="_blank">ligure.cc</a>
                    <br>
                    Powered by Typecho
                    <br>
                    <a href="https://github.com/MoXiaoXi233/PureSuck-theme" target="_blank">Theme PureSuck</a>
                </div>
                <nav class="nav header-item header-nav">
                    <span class="nav-item<?= $this->is('index') ? ' nav-item-current' : ''; ?>">
                        <a href="<?= $this->options->siteUrl(); ?>" title="棣栭〉">
                            <span itemprop="name">棣栭〉</span>
                        </a>
                    </span>
                    <!--寰幆鏄剧ず椤甸潰-->
                    <?php $this->widget('Widget_Contents_Page_List')->to($pages); ?>
                    <?php while ($pages->next()): ?>
                        <span class="nav-item<?= $this->is('page', $pages->slug) ? ' nav-item-current' : ''; ?>">
                            <a href="<?= $pages->permalink(); ?>" title="<?= $pages->title(); ?>">
                                <span><?= $pages->title(); ?></span>
                            </a>
                        </span>
                    <?php endwhile; ?>
                    <!--缁撴潫鏄剧ず椤甸潰-->
                </nav>
                <div class="theme-toggle-container">
                    <button class="theme-toggle" onclick="toggleTheme()" aria-label="鏃ュ鍒囨崲">
                        <span id="theme-icon"></span>
                    </button>
                </div>
            </div>
        </header>
        <?php
        $psPageType = 'list';
        if ($this->is('post')) {
            $psPageType = 'post';
        } elseif ($this->is('page')) {
            $psPageType = 'page';
        } elseif ($this->is('index') || $this->is('archive')) {
            $psPageType = 'list';
        }
        ?>
        <div class="content-layout" data-layout="three-column">
            <div class="content-main">
                <div id="swup" data-ps-page-type="<?= $psPageType; ?>">
                    <main class="main">


