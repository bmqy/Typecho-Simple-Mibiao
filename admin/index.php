<?php
include 'common.php';
include 'header.php';
include 'menu.php';

$stat = \Widget\Stat::alloc();
?>
<div class="main">
    <div class="container typecho-dashboard">
        <?php include 'page-title.php'; ?>
        <div class="row typecho-page-main">
            <div class="col-mb-12 welcome-board" role="main">
                <p><?php _e('目前有 <em>%s</em> 在售, <em>%s</em> 已售，<em>%s</em> 个非卖品.',
                        $stat->salePostsNum, $stat->soldPostsNum, $stat->notPostsNum); ?>
                    <br><?php _e('点击下面的链接快速开始:'); ?></p>

                <ul id="start-link" class="clearfix">
                    <?php if ($user->pass('contributor', true)): ?>
                        <li><a href="<?php $options->adminUrl('write-domain.php'); ?>"><?php _e('添加新域名'); ?></a></li>
                        <?php if ($user->pass('editor', true) && 'on' == $request->get('__typecho_all_comments') && $stat->waitingCommentsNum > 0): ?>
                            <li>
                                <a href="<?php $options->adminUrl('manage-comments.php?status=waiting'); ?>"><?php _e('待审核的评论'); ?></a>
                                <span class="balloon"><?php $stat->waitingCommentsNum(); ?></span>
                            </li>
                        <?php elseif ($stat->myWaitingCommentsNum > 0): ?>
                            <li>
                                <a href="<?php $options->adminUrl('manage-comments.php?status=waiting'); ?>"><?php _e('待审核评论'); ?></a>
                                <span class="balloon"><?php $stat->myWaitingCommentsNum(); ?></span>
                            </li>
                        <?php endif; ?>
                        <?php if ($user->pass('editor', true) && 'on' == $request->get('__typecho_all_comments') && $stat->spamCommentsNum > 0): ?>
                            <li>
                                <a href="<?php $options->adminUrl('manage-comments.php?status=spam'); ?>"><?php _e('垃圾评论'); ?></a>
                                <span class="balloon"><?php $stat->spamCommentsNum(); ?></span>
                            </li>
                        <?php elseif ($stat->mySpamCommentsNum > 0): ?>
                            <li>
                                <a href="<?php $options->adminUrl('manage-comments.php?status=spam'); ?>"><?php _e('垃圾评论'); ?></a>
                                <span class="balloon"><?php $stat->mySpamCommentsNum(); ?></span>
                            </li>
                        <?php endif; ?>
                        <?php if ($user->pass('administrator', true)): ?>
                            <li><a href="<?php $options->adminUrl('themes.php'); ?>"><?php _e('更换外观'); ?></a></li>
                            <!--<li><a href="<?php /*$options->adminUrl('plugins.php'); */?>"><?php /*_e('插件管理'); */?></a></li>-->
                            <li><a href="<?php $options->adminUrl('options-general.php'); ?>"><?php _e('系统设置'); ?></a>
                            </li>
                        <?php endif; ?>
                    <?php endif; ?>
                    <!--<li><a href="<?php $options->adminUrl('profile.php'); ?>"><?php _e('更新我的资料'); ?></a></li>-->
                </ul>
            </div>

            <div class="col-mb-12 col-tb-4" role="complementary">
                <section class="latest-link">
                    <h3><?php _e('最近在售的域名'); ?></h3>
                    <?php \Widget\Contents\Domain\RecentSale::alloc('pageSize=10')->to($salePosts); ?>
                    <ul>
                        <?php if ($salePosts->have()): ?>
                            <?php while ($salePosts->next()): ?>
                                <li>
                                    <span><?php $salePosts->date('n.j'); ?></span>
                                    <a href="<?php $salePosts->permalink(); ?>" class="title"><?php $salePosts->title(); ?></a>
                                </li>
                            <?php endwhile; ?>
                        <?php else: ?>
                            <li><em><?php _e('暂时没有域名'); ?></em></li>
                        <?php endif; ?>
                    </ul>
                </section>
            </div>

            <div class="col-mb-12 col-tb-4" role="complementary">
                <section class="latest-link">
                    <h3><?php _e('最近已售的域名'); ?></h3>
                    <?php \Widget\Contents\Domain\RecentSold::alloc('pageSize=10')->to($soldPosts); ?>
                    <ul>
                        <?php if ($soldPosts->have()): ?>
                            <?php while ($soldPosts->next()): ?>
                                <li>
                                    <span><?php $soldPosts->date('n.j'); ?></span>
                                    <a href="<?php $soldPosts->permalink(); ?>" class="title"><?php $soldPosts->title(); ?></a>
                                </li>
                            <?php endwhile; ?>
                        <?php else: ?>
                            <li><em><?php _e('暂时没有域名'); ?></em></li>
                        <?php endif; ?>
                    </ul>
                </section>
            </div>

            <div class="col-mb-12 col-tb-4" role="complementary">
                <section class="latest-link">
                    <h3><?php _e('官方最新日志'); ?></h3>
                    <div id="typecho-message">
                        <ul>
                            <li><?php _e('读取中...'); ?></li>
                        </ul>
                    </div>
                </section>
            </div>
        </div>
    </div>
</div>

<?php
include 'copyright.php';
include 'common-js.php';
?>

<script>
    $(document).ready(function () {
        var ul = $('#typecho-message ul'), cache = window.sessionStorage,
            html = cache ? cache.getItem('feed') : '',
            update = cache ? cache.getItem('update') : '';

        if (!!html) {
            ul.html(html);
        } else {
            html = '';
            $.get('<?php $options->index('/action/ajax?do=feed'); ?>', function (o) {
                for (var i = 0; i < o.length; i++) {
                    var item = o[i];
                    html += '<li><span>' + item.date + '</span> <a href="' + item.link + '" target="_blank">' + item.title
                        + '</a></li>';
                }

                ul.html(html);
                cache.setItem('feed', html);
            }, 'json');
        }

        function applyUpdate(update) {
            if (update.available) {
                $('<div class="update-check message error"><p>'
                    + '<?php _e('您当前使用的版本是 %s'); ?>'.replace('%s', update.current) + '<br />'
                    + '<strong><a href="' + update.link + '" target="_blank">'
                    + '<?php _e('官方最新版本是 %s'); ?>'.replace('%s', update.latest) + '</a></strong></p></div>')
                    .insertAfter('.typecho-page-title').effect('highlight');
            }
        }

        if (!!update) {
            applyUpdate($.parseJSON(update));
        } else {
            $.get('<?php $options->index('/action/ajax?do=checkVersion'); ?>', function (o, status, resp) {
                applyUpdate(o);
                cache.setItem('update', resp.responseText);
            }, 'json');
        }
    });

</script>
<?php include 'footer.php'; ?>
