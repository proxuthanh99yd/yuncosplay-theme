<?php
$default_icon_id = 69;

$icon = $args['icon'] ?? $default_icon_id;
$text = $args['text'] ?? '';
$href = $args['href'] ?? '';
$target = $args['target'] ?? '_self';
$wrapper_tag = !empty($href) ? 'a' : 'div';

?>
<div class="animated-btn">
    <<?php echo esc_attr($wrapper_tag); ?> class="animated-btn-wrapper"<?php if (!empty($href)) : ?> href="<?php echo esc_url($href); ?>" target="<?php echo esc_attr($target); ?>"<?php endif; ?>>
        <div class="animated-btn__content-hidden">
            <div class="animated-btn__content-hidden-text"><?php echo esc_html($text); ?></div>
            <span class="animated-btn__content-hidden-icon">
                <?php echo wp_get_attachment_image($icon, 'full', false, array('class' => 'animated-btn__icon')) ?>
            </span>
        </div>
        <div class="animated-btn__content-visible">
            <div class="animated-btn__content-visible-text"><?php echo esc_html($text); ?></div>
            <span class="animated-btn__content-visible-icon">
                <?php echo wp_get_attachment_image($icon, 'full', false, array('class' => 'animated-btn__icon')) ?>
            </span>
        </div>
    </<?php echo esc_attr($wrapper_tag); ?>>
</div>
