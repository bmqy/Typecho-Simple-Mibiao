<?php
include 'common.php';
include 'header.php';
include 'menu.php';

$stat = \Widget\Stat::alloc();
$posts = \Widget\Contents\Domain\Admin::alloc();
$isAllPosts = ('on' == $request->get('__typecho_all_domains') || 'on' == \Typecho\Cookie::get('__typecho_all_domains'));
?>
<div class="main">
    <div class="body container">
        <?php include 'page-title.php'; ?>
        <div class="row typecho-page-main" role="main">
            <div class="col-mb-12 typecho-list">
                <div class="clearfix">
                    <ul class="typecho-option-tabs">
                        <li<?php if (!isset($request->status) || 'all' == $request->get('status')): ?> class="current"<?php endif; ?>>
                            <a href="<?php $options->adminUrl('manage-domains.php'
                                . (isset($request->uid) ? '?uid=' . $request->filter('encode')->uid : '')); ?>"><?php _e('所有'); ?></a>
                        </li>
                        <li<?php if ('sale' == $request->get('status')): ?> class="current"<?php endif; ?>><a
                                href="<?php $options->adminUrl('manage-domains.php?status=sale'
                                    . (isset($request->uid) ? '&uid=' . $request->filter('encode')->uid : '')); ?>"><?php _e('在售'); ?>
                                <?php if (!$isAllPosts && $stat->myWaitingPostsNum > 0 && !isset($request->uid)): ?>
                                    <span class="balloon"><?php $stat->myWaitingPostsNum(); ?></span>
                                <?php elseif ($isAllPosts && $stat->waitingPostsNum > 0 && !isset($request->uid)): ?>
                                    <span class="balloon"><?php $stat->waitingPostsNum(); ?></span>
                                <?php elseif (isset($request->uid) && $stat->currentWaitingPostsNum > 0): ?>
                                    <span class="balloon"><?php $stat->currentWaitingPostsNum(); ?></span>
                                <?php endif; ?>
                            </a></li>
                        <li<?php if ('sold' == $request->get('status')): ?> class="current"<?php endif; ?>><a
                                href="<?php $options->adminUrl('manage-domains.php?status=sold'
                                    . (isset($request->uid) ? '&uid=' . $request->filter('encode')->uid : '')); ?>"><?php _e('已售'); ?>
                                <?php if (!$isAllPosts && $stat->myWaitingPostsNum > 0 && !isset($request->uid)): ?>
                                    <span class="balloon"><?php $stat->myWaitingPostsNum(); ?></span>
                                <?php elseif ($isAllPosts && $stat->waitingPostsNum > 0 && !isset($request->uid)): ?>
                                    <span class="balloon"><?php $stat->waitingPostsNum(); ?></span>
                                <?php elseif (isset($request->uid) && $stat->currentWaitingPostsNum > 0): ?>
                                    <span class="balloon"><?php $stat->currentWaitingPostsNum(); ?></span>
                                <?php endif; ?>
                            </a></li>
                        <li<?php if ('not' == $request->get('status')): ?> class="current"<?php endif; ?>><a
                                href="<?php $options->adminUrl('manage-domains.php?status=not'
                                    . (isset($request->uid) ? '&uid=' . $request->filter('encode')->uid : '')); ?>"><?php _e('非卖品'); ?>
                                <?php if (!$isAllPosts && $stat->myDraftPostsNum > 0 && !isset($request->uid)): ?>
                                    <span class="balloon"><?php $stat->myDraftPostsNum(); ?></span>
                                <?php elseif ($isAllPosts && $stat->draftPostsNum > 0 && !isset($request->uid)): ?>
                                    <span class="balloon"><?php $stat->draftPostsNum(); ?></span>
                                <?php elseif (isset($request->uid) && $stat->currentDraftPostsNum > 0): ?>
                                    <span class="balloon"><?php $stat->currentDraftPostsNum(); ?></span>
                                <?php endif; ?>
                            </a></li>
                    </ul>
                </div>

                <div class="typecho-list-operate clearfix">
                    <form method="get">
                        <div class="operate">
                            <label><i class="sr-only"><?php _e('全选'); ?></i><input type="checkbox"
                                                                                   class="typecho-table-select-all"/></label>
                            <div class="btn-group btn-drop">
                                <button class="btn dropdown-toggle btn-s" type="button"><i
                                        class="sr-only"><?php _e('操作'); ?></i><?php _e('选中项'); ?> <i
                                        class="i-caret-down"></i></button>
                                <ul class="dropdown-menu">
                                    <li><a lang="<?php _e('你确认要删除这些域名吗?'); ?>"
                                           href="<?php $security->index('/action/contents-domain-edit?do=delete'); ?>"><?php _e('删除'); ?></a>
                                    </li>
                                    <?php if ($user->pass('editor', true)): ?>
                                        <li>
                                            <a href="<?php $security->index('/action/contents-domain-edit?do=mark&status=sale'); ?>"><?php _e('标记为<strong>%s</strong>', _t('在售')); ?></a>
                                        </li>
                                        <li>
                                            <a href="<?php $security->index('/action/contents-domain-edit?do=mark&status=sold'); ?>"><?php _e('标记为<strong>%s</strong>', _t('已售')); ?></a>
                                        </li>
                                        <li>
                                            <a href="<?php $security->index('/action/contents-domain-edit?do=mark&status=not'); ?>"><?php _e('标记为<strong>%s</strong>', _t('非卖品')); ?></a>
                                        </li>
                                    <?php endif; ?>
                                </ul>
                            </div>
                        </div>
                        <div class="search" role="search">
                            <?php if ('' != $request->keywords || '' != $request->category): ?>
                                <a href="<?php $options->adminUrl('manage-domains.php'
                                    . (isset($request->status) || isset($request->uid) ? '?' .
                                        (isset($request->status) ? 'status=' . $request->filter('encode')->status : '') .
                                        (isset($request->uid) ? (isset($request->status) ? '&' : '') . 'uid=' . $request->filter('encode')->uid : '') : '')); ?>"><?php _e('&laquo; 取消筛选'); ?></a>
                            <?php endif; ?>
                            <input type="text" class="text-s" placeholder="<?php _e('请输入关键字'); ?>"
                                   value="<?php echo $request->filter('html')->keywords; ?>" name="keywords"/>
                            <button type="submit" class="btn btn-s"><?php _e('筛选'); ?></button>
                            <?php if (isset($request->uid)): ?>
                                <input type="hidden" value="<?php echo $request->filter('html')->uid; ?>"
                                       name="uid"/>
                            <?php endif; ?>
                            <?php if (isset($request->status)): ?>
                                <input type="hidden" value="<?php echo $request->filter('html')->status; ?>"
                                       name="status"/>
                            <?php endif; ?>
                        </div>
                    </form>
                </div><!-- end .typecho-list-operate -->

                <form method="post" name="manage_posts" class="operate-form">
                    <div class="typecho-table-wrap">
                        <table class="typecho-list-table">
                            <colgroup>
                                <col width="20" class="kit-hidden-mb"/>
                                <col width="200"/>
                                <col width="45%" class="kit-hidden-mb"/>
                                <col width="16%" class="kit-hidden-mb"/>
                                <col width="80" class="kit-hidden-mb"/>
                                <col width="80" class="kit-hidden-mb"/>
                                <col width="80" class="kit-hidden-mb"/>
                                <col width="150"/>
                            </colgroup>
                            <thead>
                            <tr>
                                <th class="kit-hidden-mb"></th>
                                <th class=""><?php _e('标题'); ?></th>
                                <th><?php _e('简介'); ?></th>
                                <th class="text-center"><?php _e('价格'); ?></th>
                                <th class="kit-hidden-mb text-center"><?php _e('平台'); ?></th>
                                <th class="text-center"><?php _e('状态'); ?></th>
                                <th class="kit-hidden-mb text-center"><?php _e('排序'); ?></th>
                                <th class="text-center"><?php _e('日期'); ?></th>
                            </tr>
                            </thead>
                            <tbody>
                            <?php if ($posts->have()): ?>
                                <?php while ($posts->next()): ?>
                                    <tr id="<?php $posts->theId(); ?>">
                                        <td class="kit-hidden-mb"><input type="checkbox" value="<?php $posts->cid(); ?>"
                                                                         name="cid[]"/></td>
                                        <td>
                                            <a href="<?php $options->adminUrl('write-domain.php?cid=' . $posts->cid); ?>" title="<?php $posts->title(); ?>"><?php $posts->title(); ?></a>
                                            <a href="<?php $options->adminUrl('write-domain.php?cid=' . $posts->cid); ?>"
                                               title="<?php _e('编辑 %s', htmlspecialchars($posts->title)); ?>"><i
                                        </td>
                                        <td class="kit-hidden-mb"><?php $posts->description(); ?></td>
                                        <td class="kit-hidden-mb text-center">￥<?php echo number_format($posts->price); ?></td>
                                        <td class="kit-hidden-mb text-center">
                                            <?php if ($posts->link): ?>
                                                <a href="<?php $posts->link(); ?>" target="_blank"><?php $posts->platforms(',', false); ?></a>
                                            <?php else: ?>
                                                <?php $posts->platforms(',', false); ?>
                                            <?php endif; ?>
                                        </td>
                                        <td class="kit-hidden-mb text-center"><a
                                                href="<?php $options->adminUrl('manage-domains.php?__typecho_all_domains=on&status=' . $posts->status); ?>">
                                                <?php
                                                if ('sale' == $posts->status) {
                                                    echo _t('在售');
                                                } elseif ('sold' == $posts->status) {
                                                    echo _t('已售');
                                                } elseif ('not' == $posts->status) {
                                                    echo _t('非卖品');
                                                }
                                                ?>
                                            </a>
                                        </td>
                                        <td class="kit-hidden-mb text-center"><?php $posts->order(); ?></a>
                                        </td>
                                        <td class="kit-hidden-mb text-center">
                                            <?php $modifyDate = new \Typecho\Date($posts->modified); ?>
                                            <?php _e('保存于 %s', $modifyDate->word()); ?>
                                        </td>
                                    </tr>
                                <?php endwhile; ?>
                            <?php else: ?>
                                <tr>
                                    <td colspan="6"><h6 class="typecho-list-table-title"><?php _e('没有任何域名'); ?></h6>
                                    </td>
                                </tr>
                            <?php endif; ?>
                            </tbody>
                        </table>
                    </div>
                </form><!-- end .operate-form -->

                <div class="typecho-list-operate clearfix">
                    <form method="get">
                        <div class="operate">
                            <label><i class="sr-only"><?php _e('全选'); ?></i><input type="checkbox"
                                                                                   class="typecho-table-select-all"/></label>
                            <div class="btn-group btn-drop">
                                <button class="btn dropdown-toggle btn-s" type="button"><i
                                        class="sr-only"><?php _e('操作'); ?></i><?php _e('选中项'); ?> <i
                                        class="i-caret-down"></i></button>
                                <ul class="dropdown-menu">
                                    <li><a lang="<?php _e('你确认要删除这些域名吗?'); ?>"
                                           href="<?php $security->index('/action/contents-domain-edit?do=delete'); ?>"><?php _e('删除'); ?></a>
                                    </li>
                                    <?php if ($user->pass('editor', true)): ?>
                                        <li>
                                            <a href="<?php $security->index('/action/contents-domain-edit?do=mark&status=sale'); ?>"><?php _e('标记为<strong>%s</strong>', _t('在售')); ?></a>
                                        </li>
                                        <li>
                                            <a href="<?php $security->index('/action/contents-domain-edit?do=mark&status=sold'); ?>"><?php _e('标记为<strong>%s</strong>', _t('已售')); ?></a>
                                        </li>
                                        <li>
                                            <a href="<?php $security->index('/action/contents-domain-edit?do=mark&status=not'); ?>"><?php _e('标记为<strong>%s</strong>', _t('非卖品')); ?></a>
                                        </li>
                                    <?php endif; ?>
                                </ul>
                            </div>
                        </div>

                        <?php if ($posts->have()): ?>
                            <ul class="typecho-pager">
                                <?php $posts->pageNav(); ?>
                            </ul>
                        <?php endif; ?>
                    </form>
                </div><!-- end .typecho-list-operate -->
            </div><!-- end .typecho-list -->
        </div><!-- end .typecho-page-main -->
    </div>
</div>

<?php
include 'copyright.php';
include 'common-js.php';
include 'table-js.php';
include 'footer.php';
?>
