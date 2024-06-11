<?php
include 'common.php';
include 'header.php';
include 'menu.php';
\Widget\Contents\Domain\Edit::alloc()->to($post);
?>
<div class="main">
    <div class="body container">
        <?php include 'page-title.php'; ?>
        <div class="row typecho-page-main typecho-post-area" role="form">
            <form action="<?php $security->index('/action/contents-domain-edit'); ?>" method="post" name="write_post">
                <div class="col-mb-12 col-tb-9" role="main">
                    <?php if ($post->draft): ?>
                        <?php if ($post->draft['cid'] != $post->cid): ?>
                            <?php $postModifyDate = new \Typecho\Date($post->draft['modified']); ?>
                            <cite
                                    class="edit-draft-notice"><?php _e('你正在编辑的是保存于 %s 的草稿, 你也可以 <a href="%s">删除它</a>', $postModifyDate->word(),
                                    $security->getIndex('/action/contents-domain-edit?do=deleteDraft&cid=' . $post->cid)); ?></cite>
                        <?php else: ?>
                            <cite class="edit-draft-notice"><?php _e('当前正在编辑的是未发布的草稿'); ?></cite>
                        <?php endif; ?>
                        <input name="draft" type="hidden" value="<?php echo $post->draft['cid'] ?>"/>
                    <?php endif; ?>

                    <p class="title">
                        <label for="title" class="sr-only"><?php _e('域名'); ?></label>
                        <input type="text" id="title" name="title" autocomplete="off" value="<?php $post->title(); ?>"
                               placeholder="<?php _e('域名'); ?>" class="w-100 text title"/>
                    </p>
                    <p class="text">
                        <label for="description" class="sr-only"><?php _e('简介'); ?></label>
                        <input type="text" id="description" name="description" autocomplete="off" value="<?php echo $post->description; ?>"
                               placeholder="<?php _e('简介'); ?>" class="w-100 text mono"/>
                    </p>
                    <p class="price">
                        <label for="price" class="sr-only"><?php _e('价格'); ?></label>
                        <input type="number" id="price" name="price" autocomplete="off" value="<?php $post->price(); ?>"
                               placeholder="<?php _e('价格'); ?>" class="w-100 text price"/>
                    </p>
                    <p class="platforms">
                        <label for="token-input-platforms" class="sr-only"><?php _e('平台'); ?></label>
                        <input type="text" id="platforms" name="platforms" value="<?php $post->platforms(',', false); ?>" class="w-100 text"/>
                    </p>
                    <p class="link">
                        <label for="link" class="sr-only"><?php _e('出售地址'); ?></label>
                        <input type="text" id="link" name="link" autocomplete="off" value="<?php $post->link(); ?>"
                               placeholder="<?php _e('出售地址'); ?>" class="w-100 text link"/>
                    </p>
                    <p class="orderNumber">
                        <label for="orderNumber" class="sr-only"><?php _e('排序'); ?></label>
                        <input type="text" id="orderNumber" name="orderNumber" autocomplete="off" value="<?php echo $post->order; ?>"
                               placeholder="<?php _e('排序'); ?>" class="w-100 text orderNumber"/>
                    </p>

                    <p class="submit clearfix">
                        <span class="right">
                            <input type="hidden" name="cid" value="<?php $post->cid(); ?>"/>
                            <button type="submit" name="do" value="publish" class="btn primary"
                                    id="btn-submit"><?php _e('确认提交'); ?></button>
                            <?php if ($options->markdown && (!$post->have() || $post->isMarkdown)): ?>
                                <input type="hidden" name="markdown" value="1"/>
                            <?php endif; ?>
                        </span>
                    </p>

                    <?php \Typecho\Plugin::factory('admin/write-domain.php')->content($post); ?>
                </div>

                <div id="edit-secondary" class="col-mb-12 col-tb-3" role="complementary">
                    <ul class="typecho-option-tabs clearfix">
                        <li class="active w-100"><a href="#tab-advance"><?php _e('选项'); ?></a></li>
                    </ul>


                    <div id="tab-advance" class="tab-content">
                        <section class="typecho-post-option" role="application">
                            <label for="date" class="typecho-label"><?php _e('发布日期'); ?></label>
                            <p><input class="typecho-date w-100" type="text" name="date" id="date" autocomplete="off"
                                      value="<?php $post->have() && $post->created > 0 ? $post->date('Y-m-d H:i') : ''; ?>"/>
                            </p>
                        </section>

                        <section class="typecho-post-option">
                            <label for="token-input-tags" class="typecho-label"><?php _e('标签'); ?></label>
                            <p><input id="tags" name="tags" type="text" value="<?php $post->tags(',', false); ?>"
                                      class="w-100 text"/></p>
                        </section>

                        <section class="typecho-post-option visibility-option">
                            <label for="status" class="typecho-label"><?php _e('状态'); ?></label>
                            <p>
                                <select id="status" name="status">
                                    <?php if ($user->pass('editor', true)): ?>
                                        <option
                                                value="sale"<?php if (($post->status == 'sale' && !$post->password) || !$post->status): ?> selected<?php endif; ?>><?php _e('在售'); ?></option>
                                        <option
                                                value="sold"<?php if ($post->status == 'sold'): ?> selected<?php endif; ?>><?php _e('已售'); ?></option>
                                        <option
                                                value="not"<?php if ($post->status == 'not'): ?> selected<?php endif; ?>><?php _e('非卖品'); ?></option>
                                    <?php endif; ?>
                                </select>
                            </p>
                            <input type="hidden" name="visibility" value="publish">
                        </section>

                        <?php \Typecho\Plugin::factory('admin/write-domain.php')->option($post); ?>

                        <?php if ($post->have()): ?>
                            <?php $modified = new \Typecho\Date($post->modified); ?>
                            <section class="typecho-post-option">
                                <p class="description">
                                    <br>&mdash;<br>
                                    <?php _e('最后更新于 %s', $modified->word()); ?>
                                </p>
                            </section>
                        <?php endif; ?>
                    </div><!-- end #tab-advance -->
                </div>
            </form>
        </div>
    </div>
</div>

<?php
include 'copyright.php';
include 'common-js.php';
include 'form-js.php';
include 'write-js.php';

\Typecho\Plugin::factory('admin/write-domain.php')->bottom($post);
include 'footer.php';
?>
