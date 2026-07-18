<?php

/**
 * 骞插噣锛岀函娲侊紝娣￠泤鏈寸礌銆?
 * Clean, pure & elegant. Theme Ligure for ligure.cc.
 * 
 * @package Ligure
 * @author ligure.cc
 * @version 1.0.0
 * @link https://ligure.cc
 */
if (!defined('__TYPECHO_ROOT_DIR__'))
    exit;
$this->need('header.php');
?>

<div class="wrapper">

    <?php
    $showCardCategory = isset($this->options->showCardCategory)
        && $this->options->showCardCategory === '1';
    ?>
    <?php while ($this->next()): ?>
        <?php
        $hasImg = $this->fields->img ? true : false;
        ?>
        <article class="post <?= $hasImg ? 'post--photo post--cover' : 'post--text'; ?> post--index main-item <?= $this->hidden ? 'post-protected' : ''; ?>" data-protected="<?= $this->hidden ? 'true' : 'false'; ?>" data-ps-post-key="<?= $this->cid; ?>">
            <div class="post-inner">
                <?php
                $cat = null;

                // 浠呭湪寮€鍚椂鎵嶅彇鍒嗙被
                if ($showCardCategory) {
                    $categories = $this->categories;
                    if (!empty($categories)) {
                        $cat = $categories[0];
                    }
                }
                ?>

                <?php if ($showCardCategory && !empty($cat)): ?>
                    <span class="post-cat-vertical">
                        <?= htmlspecialchars($cat['name']); ?>
                    </span>
                <?php endif; ?>


                <header class="post-item post-header  <?= $hasImg ? 'no-bg' : ''; ?>">
                    <div class="wrapper post-wrapper">
                        <div class="avatar post-author">
                            <img src="<?= $this->options->authorAvatar ?: $this->options->themeUrl('images/avatar.webp'); ?>"
                                alt="浣滆€呭ご鍍? class="avatar-item avatar-img"
                                loading="lazy" decoding="async" fetchpriority="low">
                            <span class="avatar-item"><?php $this->author(); ?></span>
                        </div>
                    </div>
                </header>

                <!-- 澶у浘鏍峰紡 -->
                <?php if ($hasImg): ?>
                    <figure class="post-media <?= $this->is('post') ? 'single' : ''; ?>">
                        <img itemprop="image" src="<?php $this->fields->img(); ?>" alt="澶村浘"
                            decoding="async" fetchpriority="auto">
                    </figure>
                <?php endif; ?>

                <!-- 鏂囩珷浣滆€?-->
                <section class="post-item post-body">
                    <div class="wrapper post-wrapper">
                        <h1 class="post-title">
                            <a href="<?php $this->permalink() ?>">
                                <?php $this->title() ?>
                            </a>
                        </h1>

                        <!-- 鎽樿 -->
                        <?php if ($this->hidden): ?>
                            <p class="post-excerpt">璇ユ枃绔犲凡鍔犲瘑锛岃杈撳叆瀵嗙爜鍚庢煡鐪嬨€?/p>
                        <?php else: ?>
                            <p class="post-excerpt">
                                <?php if ($this->fields->desc): ?>
                                    <?= $this->fields->desc; ?>
                                <?php else: ?>
                                    <?php $this->excerpt(200, ''); ?>
                                <?php endif; ?>
                            </p>
                        <?php endif; ?>

                    </div>
                </section>

                <footer class="post-item post-footer">
                    <div class="wrapper post-wrapper">
                        <div class="meta post-meta">
                            <a itemprop="datePublished" href="<?php $this->permalink() ?>"
                                class="icon-ui icon-ui-date meta-item meta-date">
                                <span class="meta-count">
                                    <?php $this->date(); ?>
                                </span>
                            </a>
                            <a href="<?php $this->permalink() ?>#comments"
                                class="icon-ui icon-ui-comment meta-item meta-comment">
                                <?php $this->commentsNum('鏆傛棤璇勮', '1 鏉¤瘎璁?, '%d 鏉¤瘎璁?); ?>
                            </a>
                        </div>
                    </div>
                </footer>
            </div>
        </article>
    <?php endwhile; ?>
</div>

<nav class="nav main-pager" data-js="pager">
    <span class="nav-item-alt">
        绗?<?= $this->_currentPage > 1 ? $this->_currentPage : 1; ?> 椤?/ 鍏?
        <?= ceil($this->getTotal() / $this->parameter->pageSize); ?> 椤?
    </span>
    <div class="nav nav--pager">
        <?php $this->pageLink('涓婁竴椤?); ?>
        <i class="icon-record-outline"></i>
        <?php $this->pageLink('涓嬩竴椤?, 'next'); ?>
    </div>
</nav>

<div class="nav main-lastinfo">
    <span class="nav-item-alt">
        <?php echo $this->options->footerInfo; ?>
    </span>
</div>
</main>
<?php $this->need('footer.php'); ?>
