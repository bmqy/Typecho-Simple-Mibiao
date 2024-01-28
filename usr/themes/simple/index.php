<?php
/**
 * Typecho米表的默认主题
 *
 * @package 简单米表
 * @author bmqy
 * @version 1.0
 * @link https://www.bmqy.net
 */

if (!defined('__TYPECHO_ROOT_DIR__')) exit;
$this->need('header.php');
?>
<div class="container p-3.5 mx-auto">
    <div class="h-12 p-3.5 shadow drop-shadow-md border-l-4 border-fuchsia-500 text-white bg-gradient-to-r from-violet-500 to-fuchsia-500">在售</div>
    <div class="grid grid-cols-1 lg:grid-cols-3 md:grid-cols-2 gap-3 drop-shadow-md">
        <?php $sales = $this->widget('Widget_Archive@indexSales', 'status=sale&pageSize=50&orderby=order') ?>
        <?php while ($sales->next()): ?>
            <article class="post shadow p-3.5" itemscope itemtype="http://schema.org/BlogPosting">
                <h2 class="post-title" itemprop="name headline">
                    <?php $sales->title() ?>
                </h2>
                <ul class="post-meta">
                    <li itemprop="price" itemscope itemtype="http://schema.org/Person">
                        <?php _e('价格: '); ?><span class="font-bold text-rose-500">￥<?php echo number_format($sales->price); ?></span>
                    </li>
                    <li itemprop="platform" itemscope itemtype="http://schema.org/Person">
                        <?php _e('平台: '); ?>
                        <?php if ($sales->link): ?>
                            <a itemprop="platform" href="<?php $sales->link(); ?>" target="_blank" rel="platform"><?php $sales->platforms(',', false); ?></a>
                        <?php else: ?>
                            <?php $sales->platforms(',', false); ?>
                        <?php endif; ?>
                    </li>
                    <li itemprop="platform" itemscope itemtype="http://schema.org/Person">
                        <?php _e('访问: '); ?>
                        <?php $sales->visitCount(); ?>
                    </li>
                </ul>
                <div class="post-content text-gray-400 mt-2 pt-2 border-t" itemprop="articleBody">
                    <?php $sales->description(); ?>
                </div>
            </article>
        <?php endwhile; ?>
    </div>
</div>

<div class="container p-3.5 mx-auto mt-8">
    <div class="h-12 p-3.5 shadow drop-shadow-md border-l-4 border-blue-500 text-white bg-gradient-to-r from-cyan-500 to-blue-500">已售</div>
    <div class="grid grid-cols-1 lg:grid-cols-3 md:grid-cols-2 gap-3 drop-shadow-md">
        <?php $solds = $this->widget('Widget_Archive@indexSolds', 'status=sold&pageSize=50&orderby=order') ?>
        <?php while ($solds->next()): ?>
            <article class="post shadow p-3.5" itemscope itemtype="http://schema.org/BlogPosting">
                <h2 class="post-title" itemprop="name headline">
                    <?php $solds->title() ?>
                </h2>
                <ul class="post-meta">
                    <li itemprop="price" itemscope itemtype="http://schema.org/Person">
                        <?php _e('价格: '); ?><span class="font-bold text-rose-500">￥<?php echo number_format($solds->price); ?></span>
                    </li>
                    <li itemprop="platform" itemscope itemtype="http://schema.org/Person">
                        <?php _e('平台: '); ?>
                        <?php if ($solds->link): ?>
                            <a itemprop="platform" href="<?php $solds->link(); ?>" target="_blank" rel="platform"><?php $solds->platforms(',', false); ?></a>
                        <?php else: ?>
                            <?php $solds->platforms(',', false); ?>
                        <?php endif; ?>
                    </li>
                    <li itemprop="platform" itemscope itemtype="http://schema.org/Person">
                        <?php _e('访问: '); ?>
                        <?php $solds->visitCount(); ?>
                    </li>
                </ul>
                <div class="post-content text-gray-400 mt-2 pt-2 border-t" itemprop="articleBody">
                    <?php $solds->description(); ?>
                </div>
            </article>
        <?php endwhile; ?>
    </div>
</div>

<div class="container p-3.5 mx-auto mt-8">
    <div class="h-12 p-3.5 shadow drop-shadow-md border-l-4 border-purple-500 text-white bg-gradient-to-r from-red-400 to-purple-500">非卖品</div>
    <div class="grid grid-cols-1 lg:grid-cols-3 md:grid-cols-2 gap-3 drop-shadow-md">
        <?php $nots = $this->widget('Widget_Archive@indexNots', 'status=not&pageSize=50&orderby=order') ?>
        <?php while ($nots->next()): ?>
            <article class="post shadow p-3.5" itemscope itemtype="http://schema.org/BlogPosting">
                <h2 class="post-title" itemprop="name headline">
                    <?php $nots->title() ?>
                </h2>
                <ul class="post-meta">
                    <li itemprop="price" itemscope itemtype="http://schema.org/Person">
                        <?php _e('价格: '); ?><span class="font-bold text-rose-500">￥<?php echo number_format($nots->price); ?></span>
                    </li>
                    <li itemprop="platform" itemscope itemtype="http://schema.org/Person">
                        <?php _e('平台: '); ?>
                        <?php if ($nots->link): ?>
                            <a itemprop="platform" href="<?php $nots->link(); ?>" target="_blank" rel="platform"><?php $nots->platforms(',', false); ?></a>
                        <?php else: ?>
                            <?php $nots->platforms(',', false); ?>
                        <?php endif; ?>
                    </li>
                    <li itemprop="platform" itemscope itemtype="http://schema.org/Person">
                        <?php _e('访问: '); ?>
                        <?php $nots->visitCount(); ?>
                    </li>
                </ul>
                <div class="post-content text-gray-400 mt-2 pt-2 border-t" itemprop="articleBody">
                    <?php $nots->description(); ?>
                </div>
            </article>
        <?php endwhile; ?>
    </div>
</div><!-- end #body -->
<?php $this->need('footer.php'); ?>
