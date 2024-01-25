<?php if (!defined('__TYPECHO_ROOT_DIR__')) exit; ?>

<footer id="footer" class="py-10 px-3.5" role="contentinfo">
    &copy; <?php echo date('Y'); ?> <a href="<?php $this->options->siteUrl(); ?>"><?php $this->options->title(); ?></a>.<br/>
    <?php _e('由 <a href="https://typecho.org">Typecho</a> 强力驱动，<a href="https://github.com/bmqy/Typecho-Simple-Mibiao">Typecho-Simple-Mibiao</a> 提供支持'); ?>.
    <?php if($this->options->cpsInfo !=''): ?>
        <div class="cps-info"><?php $this->options->cpsInfo(); ?></div>
    <?php endif;?>
</footer><!-- end #footer -->

<?php $this->footer(); ?>
</body>
</html>
