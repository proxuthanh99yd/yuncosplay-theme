<?php
$icon_key = $args['icon_key'] ?? '';       // key ảnh theme (okhub_img)
$icon     = $args['icon'] ?? 0;            // hoặc attachment ID (nội dung/legacy)
$text = $args['text'] ?? '';
$href = $args['href'] ?? '';
$target = $args['target'] ?? '_self';
$wrapper_tag = !empty($href) ? 'a' : 'div';

// Icon: ưu tiên key theme, rồi attachment ID, mặc định mũi tên theme.
if ($icon_key !== '') {
	$icon_html = okhub_img($icon_key, array('class' => 'animated-btn__icon'));
} elseif ($icon) {
	$icon_html = wp_get_attachment_image($icon, 'full', false, array('class' => 'animated-btn__icon'));
} else {
	$icon_html = okhub_img('icons/arrow', array('class' => 'animated-btn__icon'));
}

?>
<div class="animated-btn">
    <<?php echo esc_attr($wrapper_tag); ?> class="animated-btn-wrapper"<?php if (!empty($href)) : ?> href="<?php echo esc_url($href); ?>" target="<?php echo esc_attr($target); ?>"<?php endif; ?>>
        <div class="animated-btn__content-hidden">
            <div class="animated-btn__content-hidden-text"><?php echo esc_html($text); ?></div>
            <span class="animated-btn__content-hidden-icon">
                <?php echo $icon_html; ?>
            </span>
        </div>
        <div class="animated-btn__content-visible">
            <div class="animated-btn__content-visible-text"><?php echo esc_html($text); ?></div>
            <span class="animated-btn__content-visible-icon">
                <?php echo $icon_html; ?>
            </span>
        </div>
    </<?php echo esc_attr($wrapper_tag); ?>>
</div>
