<?php
$icon_arrow_right_id = 69;
$text = $args['text'] ?? '';

?>
<div class="animated-btn">
    <div class="animated-btn-wrapper">
        <div class="animated-btn__content-hidden">
            <div class="animated-btn__content-hidden-text"><?php echo esc_html($text); ?></div>
            <span class="animated-btn__content-hidden-icon">
                <?php echo wp_get_attachment_image($icon_arrow_right_id, 'full', false, array('class' => 'animated-btn__icon')) ?>
            </span>
        </div>
        <div class="animated-btn__content-visible">
            <div class="animated-btn__content-visible-text"><?php echo esc_html($text); ?></div>
            <span class="animated-btn__content-visible-icon">
                <?php echo wp_get_attachment_image($icon_arrow_right_id, 'full', false, array('class' => 'animated-btn__icon')) ?>
            </span>
        </div>
    </div>
</div>