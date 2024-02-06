<?php
/**
 * 米表网模版
 *
 * @package 米表网模版
 * @author 米表网
 * @version 1.0.0
 * @link http://www.mb.cn/
 */

if (!defined('__TYPECHO_ROOT_DIR__')) exit;
$this->need('header.php');
?>
<!--content开始-->
<div class="layout clearfix">
    <div class="container clearfix">
        <!--banner-->
        <div class="banner">
            <a target="_blank" href="">
                <img src="<?php $this->options->banner1() ?>" alt="banner" class="img-responsive" style="width: 100%;">
            </a>
        </div>
        <!--banner-->

        <!--     推荐域名-->
        <div class="yzym_left" id="sale">
            <div class="column">
                <i class="iconfont icon-dianzan fl"></i>
                <h2 class="hd  fl">在售域名</h2>
                <div class="clear"></div>
            </div>

            <div class="yzym-item">
                <ul class="yzym-list">
                    <?php $sales = $this->widget('Widget_Archive@indexSales', 'status=sale&pageSize=50&orderby=order') ?>
                    <?php while ($sales->next()): ?>
                    <li>
                        <?php if ($sales->link): ?>
                            <a href="<?php $sales->link(); ?>" target="_blank">
                        <?php else: ?>
                            <a>
                        <?php endif; ?>
                            <p class="u-yuming jm-1b"><?php $sales->title() ?></p>
                            <p class="t-yuming jm-33"><?php $sales->description(); ?></p>
                            <p class="index-price">￥<?php echo number_format($sales->price); ?></p>
                        </a>
                        <?php if ($sales->link): ?>
                            <a href="<?php $sales->link(); ?>" target="_blank" class="buy-btn">点击购买</a>
                        <?php else: ?>
                            <a class="buy-btn"><?php $sales->platforms(',', false); ?></a>
                        <?php endif; ?>
                    </li>
                    <?php endwhile; ?>
                    <div class="clear"></div>
                </ul>
            </div>
        </div>

        <!--交易流程-->
        <div class="yzym_left" id="jylc">
            <div class="column">
                <i class="iconfont icon-liuchengshezhi fl"></i>
                <h2 class="hd  fl">交易流程</h2>
                <div class="clear"></div>
            </div>

            <div class="process-item">
                <ul class="process-list">
                    <li class="on">
                        <p class="pro-num">1</p>
                        <p class="pro-tit">挑选域名</p>
                        <i class="iconfont icon-shaixuan" style="font-size: 32px;margin-top: -6px;display: block"></i>
                    </li>
                    <li>
                        <p class="pro-num">2</p>
                        <p class="pro-tit">联系卖家</p>
                        <i class="iconfont icon-kefu"></i>
                    </li>
                    <li>
                        <p class="pro-num">3</p>
                        <p class="pro-tit">发起担保</p>
                        <i class="iconfont icon-anquanshezhi"></i>
                    </li>
                    <li>
                        <p class="pro-num">4</p>
                        <p class="pro-tit">支付费用</p>
                        <i class="iconfont icon-ziyuan"></i>
                    </li>
                    <li>
                        <p class="pro-num">5</p>
                        <p class="pro-tit">过户域名</p>
                        <i class="iconfont icon-iconfontzhizuobiaozhun02969696"></i>
                    </li>
                    <li>
                        <p class="pro-num">6</p>
                        <p class="pro-tit">完成交易</p>
                        <i class="iconfont icon-wancheng1"></i>
                    </li>

                    <div class="clear"></div>
                </ul>
            </div>
        </div>

        <!--最近成交-->
        <div class="yzym_left" id="sold">
            <div class="column">
                <i class="iconfont icon-farenxinxi fl" style="font-size: 18px;"></i>
                <h2 class="hd  fl">最近成交</h2>
                <div class="clear"></div>
            </div>

            <div class="deal-item">
                <ul class="deal-list">
                    <?php $solds = $this->widget('Widget_Archive@indexSolds', 'status=sold&pageSize=50&orderby=order') ?>
                    <?php while ($solds->next()): ?>
                    <li><a href="javascript:;">
                            <p class="deal-yuming jm-1b"><?php $solds->title() ?></p>
                            <p class="deal-btn">￥<?php echo number_format($solds->price); ?></p>
                        </a>
                    </li>
                    <?php endwhile; ?>
                    <div class="clear"></div>
                </ul>
            </div>
        </div>

        <!--banner-->
        <div class="banner">
            <a target="_blank" href="">
                <img src="<?php $this->options->banner2() ?>" alt="banner" class="img-responsive" style="width: 100%;">
            </a>
        </div>
        <!--banner-->

        <!--非卖品-->
        <div class="yzym_left" id="not">
            <div class="column">
                <i class="iconfont icon-xingxing fl"></i>
                <h2 class="hd  fl">非卖品</h2>
                <div class="clear"></div>
            </div>

            <div class="tab-item" style="display: block;" >
                <ul class="selected-list">
                    <?php $nots = $this->widget('Widget_Archive@indexNots', 'status=not&pageSize=50&orderby=order') ?>
                    <?php while ($nots->next()): ?>
                    <li>
                        <p class="u-yuming"><?php $nots->title() ?></p>
                        <p class="t-yuming"><?php $nots->description(); ?></p>
                        <p class="selected-price"><?php echo number_format($nots->price); ?></p>
                        <a class="buy-btn-selected"><?php $nots->platforms(',', false); ?></a>
                    </li>
                    <?php endwhile; ?>
                    <div class="clear"></div>
                </ul>
            </div>
        </div>
    </div>
    <!--content结束-->
<?php $this->need('footer.php'); ?>
