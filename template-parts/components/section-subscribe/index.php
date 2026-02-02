<?php
$subscribe = get_field('subscribe', 'option');
$title_pc = $subscribe['title_subcscribe_pc'] ?? '';
$title_mb = $subscribe['title_subcscribe_mobile'] ?? '';
$desc     = $subscribe['subcscribe_description'] ?? '';
$background_mobile_subscribe_id = 2050;
?>

<section class='subscribe-section'>
    <?= wp_get_attachment_image($background_mobile_subscribe_id, 'full', false, ['class' => 'subscribe-background--mobile']) ?>
    <div class='subscribe-container'>
        <div class='subscribe-left'>
            <div class='subscribe-left__title'>
                <?php if (!empty($title_pc)) : ?>
                    <h3 class='subscribe-left__title--desktop'><?= $title_pc ?></h3>
                <?php endif; ?>
                <?php if (!empty($title_mb)) : ?>
                    <h3 class='subscribe-left__title--mobile'><?= $title_mb ?></h3>
                <?php endif; ?>
            </div>
            <div class='subscribe-left__desc'>
                <?php if (!empty($desc)) : ?>
                    <p>
                        <?= $desc ?>
                    </p>
                <?php endif; ?>
            </div>
        </div>
        <div class="subscribe-right">
            <?= do_shortcode('[contact-form-7 id="4385784" title="Subscribe"]') ?>
        </div>
    </div>
</section>
