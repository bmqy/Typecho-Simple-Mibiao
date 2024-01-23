<?php
/**
 * 小票风格地米表
 *
 * @package 小票米表
 * @author Sen.ge
 * @version 1.0
 * @link https://github.com/BitCodepot/xp_mb
 */

if (!defined('__TYPECHO_ROOT_DIR__')) exit;
?>
    <?php $this->need('header.php'); ?>
    <div class="content">
        <?php if($this->options->orderInfo): ?>
        <div class="order-info">
            <h2>订单信息</h2>
            <?php $this->options->orderInfo() ?>
        </div>
        <?php endif; ?>

        <?php if($this->options->storeInfo): ?>
        <div class="store-info">
            <h2>商店信息</h2>
            <?php $this->options->storeInfo() ?>
        </div>
        <?php endif; ?>

        <?php $sales = $this->widget('Widget_Archive@indexSales', 'status=sale&pageSize=50') ?>
        <?php if (!empty($sales)): ?>
            <h2>待售列表</h2>
            <table>
                <tr>
                    <th>域名</th>
                    <th>备注</th>
                    <th>平台</th>
                    <th>访问</th>
                    <th>价格</th>
                </tr>
                <?php while ($sales->next()): ?>
                    <tr class="item" title="心怡域名，请带价骚扰">
                        <td><?php echo $sales->title(); ?></td>
                        <td><?php echo $sales->description(); ?></td>
                        <td><?php echo $sales->platforms(',', false); ?></td>
                        <td><?php echo $sales->visitCount(); ?></td>
                        <?php if ($sales->link): ?>
                        <td> <a href="<?php echo $sales->link(); ?>">￥<?php echo number_format($sales->price); ?></a></td>
                        <?php else: ?>
                        <td> ￥<?php echo number_format($sales->price); ?></td>
                        <?php endif; ?>

                    </tr>
                <?php endwhile; ?>
                <tr>
                    <th colspan="5"></th>
                </tr>
                <tr class="item">
                    <td colspan="4" style="text-align: right;"><strong>总价：</strong></td>
                    <td><strong>￥<?php echo number_format($sales->totalPriceByStatus('sale')); ?></strong></td>
                </tr>
            </table>
        <?php endif; ?>

        <?php $solds = $this->widget('Widget_Archive@indexSolds', 'status=sold&pageSize=50') ?>
        <?php if (!empty($solds)): ?>
            <h2>已售域名</h2>
            <table>
                <tr>
                    <th>域名</th>
                    <th>备注</th>
                    <th>平台</th>
                    <th>价格</th>
                </tr>
                <?php while ($solds->next()): ?>
                    <tr class="item">
                        <td><?php echo $solds->title(); ?></td>
                        <td><?php echo $solds->description(); ?></td>
                        <td><?php echo $solds->platforms(',', false); ?></td>
                        <td> ￥<?php echo number_format($solds->price); ?></td>
                    </tr>
                <?php endwhile; ?>
                <tr>
                    <th colspan="4"></th>
                </tr>
                <tr class="item">
                    <td colspan="3" style="text-align: right;"><strong>总价：</strong></td>
                    <td><strong>￥<?php echo number_format($sales->totalPriceByStatus('sold')); ?></strong></td>
                </tr>
            </table>
        <?php endif; ?>

        <?php $nots = $this->widget('Widget_Archive@indexNots', 'status=not&pageSize=50') ?>
        <?php if (!empty($nots)): ?>
            <h2>保留域名</h2>
            <table>
                <tr>
                    <th>域名</th>
                    <th>备注</th>
                    <th>平台</th>
                    <th>价格</th>
                </tr>
                <?php while ($nots->next()): ?>
                    <tr class="item">
                        <td><?php echo $nots->title(); ?></td>
                        <td><?php echo $nots->description(); ?></td>
                        <td><?php echo $nots->platforms(',', false); ?></td>
                        <td> ￥<?php echo number_format($nots->price); ?></td>
                    </tr>
                <?php endwhile; ?>
                <tr>
                    <th colspan="4"></th>
                </tr>
                <tr class="item">
                    <td colspan="3" style="text-align: right;"><strong>总价：</strong></td>
                    <td><strong>￥<?php echo number_format($sales->totalPriceByStatus('not')); ?></strong></td>
                </tr>
            </table>
        <?php endif; ?>

        <?php if($this->options->shoppingTips): ?>
        <div class="shopping-tips">
            <h2>购物提示</h2>
            <?php echo $this->options->shoppingTips ?>
        </div>
        <?php endif; ?>

        <?php if($this->options->qrcodeUrl): ?>
        <div style="margin-top: 20px" id="qrcode-box">
            <div style="float: right;">
                <img src="<?php echo $this->options->qrcodeUrl ?>" alt="请扫码访问" width="128px" height="128px">
            </div>
        </div>
        <?php endif; ?>
        <div class="clear"></div>
    </div><!-- end #body -->
    <?php $this->need('footer.php'); ?>
</div>
