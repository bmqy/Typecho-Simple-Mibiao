<?php if (!defined('__TYPECHO_ROOT_DIR__')) exit; ?>

    <!--    底部开始-->
    <footer>
        <p>
            <?php if($this->options->qq): ?>
            QQ：<?php $this->options->qq() ?>
            <?php endif; ?>
            <?php if($this->options->email): ?>
            邮箱：<?php $this->options->email() ?>
            <?php endif; ?>
        </p>
        <p>
            <?php if($this->options->beiAnHao): ?>
            <a href="http://beian.miit.gov.cn/" target="_blank" style="color:#999">备案号：<?php $this->options->beiAnHao() ?></a>
            <?php endif; ?>
            Copyright © <?php echo date('Y'); ?> <?php $this->options->title() ?>, 主题来源
            <a href="http://www.mb.cn/" target="_blank">米表网</a>
        </p>
        <p>Powered by<a href="https://typecho.org">Typecho</a>，<a href="https://github.com/bmqy/Typecho-Simple-Mibiao">Typecho-Simple-Mibiao</a> 提供支持</p>
        <div style="text-align:center;">
        </div>
    </footer>
</div>

<!--右侧悬浮框-->
<div id="toolbar" class="toolbar">
    <?php if($this->options->qq): ?>
    <div class="to-comm comm1" title="">
        <i class="iconfont icon-QQ"></i>
        <a class="kefu1" href="http://wpa.qq.com/msgrd?v=3&amp;uin=<?php $this->options->qq() ?>&amp;site=<?php $this->options->siteUrl(); ?>&amp;menu=yes"
           target="_blank">在线QQ：<?php $this->options->qq() ?></a>
    </div>
    <?php endif; ?>

    <?php if($this->options->tgUsername): ?>
    <div class="to-comm comm1" title="">
        <i class="iconfont icon-lianximaijia"></i>
        <a class="kefu1" href="https://t.me/<?php $this->options->qq() ?>"
           target="_blank">联系TG：<?php $this->options->tgUsername() ?></a>
    </div>
    <?php endif; ?>

    <?php if($this->options->qrcodeUrl): ?>
    <div class="to-comm comm2" title="">
        <i class="iconfont icon-weixin-copy"></i>
        <span class="tooltiptext">
          <img width="100" src="<?php echo $this->options->qrcodeUrl ?>" alt="">
          <p class="cl-theme">扫一扫 了解更多</p>
        </span>
    </div>
    <?php endif; ?>

    <?php if($this->options->email): ?>
    <div class="to-comm comm4" title="">
        <i class="iconfont icon-youxiang1"></i>
        <a class="kefu2" href="mailto:<?php $this->options->email() ?>" target="_blank">邮箱：<?php $this->options->email() ?></a>
    </div>
    <?php endif; ?>

    <div id="to-top" title="返回顶部" style="display: none;">
        <i class="iconfont icon-icon3"></i>
    </div>
</div>

<script>
    $(window).scroll(function () {
        if ($(this).scrollTop() >= 200) {
            $("#to-top").fadeIn();
        }
        if ($(this).scrollTop() < 200) {
            $("#to-top").fadeOut();
        }
    });

    $("#to-top").click(function () {
        $("body,html").animate({
            scrollTop: 0
        }, 500)
    });

    $('.toolbar .comm2').mousemove(function () {
        $('.tooltiptext').addClass('active');
    });
    $('.toolbar .comm2').mouseout(function () {
        $('.tooltiptext').removeClass('active');
    });

</script>
<style>
    #toolbar .tooltiptext.active{
        display: block;
    }
</style>
<?php $this->footer(); ?>
</body>
</html>
