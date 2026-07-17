<?php
$are_you_ready = get_field('areyouready', get_the_ID());

$ready_title = $are_you_ready['title'] ?? '';
$ready_desc  = $are_you_ready['desc'] ?? '';
$ready_link  = $are_you_ready['link'] ?? null;

$image_desktop = $are_you_ready['image_desktop'] ?? null;
$image_mobile  = $are_you_ready['image_mobile'] ?? null;

$image_desktop_id = is_array($image_desktop) ? ($image_desktop['ID'] ?? null) : $image_desktop;
$image_mobile_id  = is_array($image_mobile) ? ($image_mobile['ID'] ?? null) : $image_mobile;

$image_desktop_alt = is_array($image_desktop)
    ? ($image_desktop['alt'] ?: ($image_desktop['title'] ?? 'Yun Cosplay CTA Desktop'))
    : 'Yun Cosplay CTA Desktop';

$image_mobile_alt = is_array($image_mobile)
    ? ($image_mobile['alt'] ?: ($image_mobile['title'] ?? 'Yun Cosplay CTA Mobile'))
    : 'Yun Cosplay CTA Mobile';

$link_url = is_array($ready_link) ? ($ready_link['url'] ?? '/') : '/';
$link_title = is_array($ready_link) ? ($ready_link['title'] ?? 'Chat ngay với Yun Cosplay') : 'Chat ngay với Yun Cosplay';
$link_target = is_array($ready_link) ? ($ready_link['target'] ?? '_self') : '_self';
?>

<section class="faq-cosplay-cta">
    <?= wp_get_attachment_image(10266, 'full', false, [
        'class' => 'faq-cosplay-cta__page-bg',
        'alt' => '',
        'aria-hidden' => 'true',
        'loading' => 'lazy',
    ]) ?>

    <div class="faq-cosplay-cta__container">
        <div class="faq-cosplay-cta__banner">
            <?php if (!empty($image_desktop_id)) : ?>
                <?= wp_get_attachment_image($image_desktop_id, 'full', false, [
                    'class' => 'faq-cosplay-cta__image faq-cosplay-cta__image--desktop',
                    'alt' => esc_attr($image_desktop_alt),
                    'loading' => 'lazy',
                ]) ?>
            <?php endif; ?>

            <?php if (!empty($image_mobile_id)) : ?>
                <?= wp_get_attachment_image($image_mobile_id, 'full', false, [
                    'class' => 'faq-cosplay-cta__image faq-cosplay-cta__image--mobile',
                    'alt' => esc_attr($image_mobile_alt),
                    'loading' => 'lazy',
                ]) ?>
            <?php endif; ?>

            <div class="faq-cosplay-cta__overlay"></div>

            <div class="faq-cosplay-cta__content">
                <?php if (!empty($ready_title)) : ?>
                    <div class="faq-cosplay-cta__title">
                        <?= wp_kses_post($ready_title); ?>
                    </div>
                <?php endif; ?>

                <?php if (!empty($ready_desc)) : ?>
                    <p class="faq-cosplay-cta__desc">
                        <?= esc_html($ready_desc); ?>
                    </p>
                <?php endif; ?>

                 <a 
    href="<?= esc_url($link_url); ?>" 
    target="<?= esc_attr($link_target); ?>" 
    class="animated-btn header__mega-menu-product__show-all-btn animated-btn--auto-wrap"
>
    <div class="animated-btn-wrapper">
        <div class="animated-btn__content-hidden">
            <div class="animated-btn__content-hidden-text">
                <?= esc_html($link_title); ?>
            </div>

            <span class="animated-btn__content-hidden-icon">
                <?php echo wp_get_attachment_image($icon_arrow_right_id, 'full', false, ['class' => 'animated-btn__icon']) ?>
            </span>
        </div>

        <div class="animated-btn__content-visible">
            <div class="animated-btn__content-visible-text">
                <?= esc_html($link_title); ?>
            </div>

            <span class="animated-btn__content-visible-icon">
                <svg class="animated-btn__icon" xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 18 18" fill="none">
                    <path d="M14.7354 9L9.95312 13.7812L8.83789 13.7471L10.7012 11.8848C11.3302 11.2566 11.9055 10.6936 12.4277 10.1973L13.2285 9.43652L12.123 9.43066L4.17188 9.39355L4.21094 8.52734L12.1973 8.56543L13.3203 8.57129L12.5049 7.79883C11.9946 7.31538 11.4294 6.76241 10.8086 6.1416L8.87598 4.20898L9.9082 4.17578L14.7354 9Z" fill="#680103" stroke="#680103" stroke-width="0.8888" />
                </svg>
            </span>
        </div>
    </div>
</a>

            </div>
        </div>
    </div>
</section>