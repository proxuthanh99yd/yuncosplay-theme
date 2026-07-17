<?php

/**
 * Contact Page - Section Contact Info (Left Column)
 * Hiển thị tiêu đề, thông tin liên hệ, và social media cards
 */

$contact_title  = get_field('contact_title') ?: 'Liên hệ với chúng tôi';
$working_hours  = get_field('contact_working_hours') ?: '6h → 20h (Thứ 2 → Thứ 7)';
$address        = get_field('contact_address') ?: '31 Trần Kim Xuyến, Yên Hoà, Cầu Giấy, Hà Nội, Việt Nam';
$social_media   = get_field('contact_social_media') ?: [];
$overlay_item_id = 10101;

// Đường dẫn tới icons
$ic_clock    = get_theme_file_uri('/template-parts/contact-page/assets/images/ic-clock.svg');
$ic_location = get_theme_file_uri('/template-parts/contact-page/assets/images/ic-location.svg');

// Default social media data nếu ACF trống
if (empty($social_media)) {
    $social_media = [
        [
            'platform' => 'facebook',
            'name'     => 'Facebook',
            'url'      => '#',
            'image'    => null,
        ],
        [
            'platform' => 'tiktok',
            'name'     => 'TikTok',
            'url'      => '#',
            'image'    => null,
        ],
        [
            'platform' => 'instagram',
            'name'     => 'Instagram',
            'url'      => '#',
            'image'    => null,
        ],
    ];
}
?>

<div class="contact-info">
    <!-- Tiêu đề chính -->
    <h1 class="contact-info__title"><?= esc_html($contact_title); ?></h1>

    <!-- Thông tin liên hệ -->
    <div class="contact-info__details">
        <div class="contact-info__detail-item">
            <img src="<?= esc_url($ic_clock); ?>" alt="" width="20" height="20" class="contact-info__detail-icon" loading="lazy" decoding="async">
            <span class="contact-info__detail-text">Giờ làm việc: <?= esc_html($working_hours); ?></span>
        </div>
        <div class="contact-info__detail-item">
            <img src="<?= esc_url($ic_location); ?>" alt="" width="20" height="20" class="contact-info__detail-icon" loading="lazy" decoding="async">
            <span class="contact-info__detail-text"><?= esc_html($address); ?></span>
        </div>
    </div>

    <!-- Heading social -->
    <h2 class="contact-info__social-heading">Theo dõi chúng tôi</h2>

    <!-- Social media cards slider -->
    <div class="contact-info__social-wrapper">
        <div class="swiper contact-info__social-swiper">
            <div class="swiper-wrapper">
                <?php foreach ($social_media as $social) :
                    $platform = $social['platform'] ?? 'facebook';
                    $name     = $social['name'] ?? ucfirst($platform);
                    $url      = $social['url'] ?? '#';
                    $image_id = $social['image'] ?? null;

                    // Icon cho mỗi platform
                    $ic_platform = get_theme_file_uri('/template-parts/contact-page/assets/images/ic-' . $platform . '.svg');

                    // Ảnh nền: ưu tiên ACF, fallback placeholder (PC/MB)
                    $has_acf_image = !empty($image_id);
                ?>
                    <div class="swiper-slide contact-info__social-slide">
                        <a href="<?= esc_url($url); ?>" target="_blank" rel="noopener noreferrer" class="contact-info__social-card" data-platform="<?= esc_attr($platform); ?>">
                            <div class="contact-info__social-card-img-wrap">
                                <?= wp_get_attachment_image($overlay_item_id, 'full', false, ['class'    => 'contact-info__social-card-img-overlay']); ?>

                                <?php if ($has_acf_image) : ?>
                                    <?= wp_get_attachment_image($image_id, 'medium', false, [
                                        'class'    => 'contact-info__social-card-img',
                                        'loading'  => 'lazy',
                                        'decoding' => 'async',
                                    ]); ?>
                                <?php else : ?>
                                    <!-- PC placeholder -->
                                    <img
                                        src="<?= esc_url(get_theme_file_uri('/template-parts/contact-page/assets/images/d-social-' . $platform . '.png')); ?>"
                                        alt="<?= esc_attr($name); ?>"
                                        class="contact-info__social-card-img contact-info__social-card-img--pc"
                                        loading="lazy"
                                        decoding="async">
                                    <!-- MB placeholder -->
                                    <img
                                        src="<?= esc_url(get_theme_file_uri('/template-parts/contact-page/assets/images/m-social-' . $platform . '.png')); ?>"
                                        alt="<?= esc_attr($name); ?>"
                                        class="contact-info__social-card-img contact-info__social-card-img--mb"
                                        loading="lazy"
                                        decoding="async">
                                <?php endif; ?>
                            </div>

                            <!-- Gradient overlay -->
                            <div class="contact-info__social-card-overlay"></div>

                            <!-- Platform info -->
                            <div class="contact-info__social-card-info">
                                <img src="<?= esc_url($ic_platform); ?>" alt="" class="contact-info__social-card-icon" loading="lazy" decoding="async">
                                <span class="contact-info__social-card-name"><?= esc_html($name); ?></span>
                            </div>
                        </a>
                    </div>
                <?php endforeach; ?>
            </div>
        </div>

        <!-- Navigation arrows (PC only) -->
        <button type="button" class="contact-info__social-arrow contact-info__social-arrow--prev" aria-label="Previous">
            <img src="<?= esc_url(get_theme_file_uri('/template-parts/contact-page/assets/images/ic-arrow-left.svg')); ?>" alt="" width="32" height="32" loading="lazy" decoding="async">
        </button>
        <button type="button" class="contact-info__social-arrow contact-info__social-arrow--next" aria-label="Next">
            <img src="<?= esc_url(get_theme_file_uri('/template-parts/contact-page/assets/images/ic-arrow-right.svg')); ?>" alt="" width="32" height="32" loading="lazy" decoding="async">
        </button>
    </div>
</div>