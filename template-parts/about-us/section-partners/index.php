<?php
$partner_section = get_field('partners');
$partner_section = is_array($partner_section) ? $partner_section : [];

$subtitle = $partner_section['subtitle'] ?? '';
$title = $partner_section['title'] ?? '';
$description = $partner_section['description'] ?? '';

$logos = $partner_section['logos'] ?? [];
$logos = is_array($logos) ? array_filter($logos) : [];

$items_per_slide = 9;
$logo_chunks = !empty($logos) ? array_chunk($logos, $items_per_slide) : [];
?>

<section class="partner-section" aria-labelledby="partner-section-title">
    <div class="partner-section__container">

        <div class="partner-section__intro">
            <div class="partner-section__heading-group">
                <div class="partner-section__eyebrow-wrap">
                    <p class="partner-section__eyebrow"><?= esc_html($subtitle); ?></p>
                </div>
                <h2 class="partner-section__title" id="partner-section-title"><?= esc_html($title); ?></h2>
            </div>
            <p class="partner-section__description"><?= esc_html($description); ?></p>
        </div>

        <?php if (!empty($logo_chunks)) : ?>
            <div class="partner-section__slider-wrap">
                <div class="swiper partner-section__slider" aria-label="Partner logos carousel">
                    <div class="swiper-wrapper">
                        <?php foreach ($logo_chunks as $chunk) : ?>
                            <div class="swiper-slide partner-section__slide">
                                <ul class="partner-section__logo-grid">
                                    <?php foreach ($chunk as $logo_id) :
                                        if (!$logo_id) continue;
                                    ?>
                                        <li class="partner-section__logo-item">
                                            <div class="partner-section__logo-frame">
                                                <?= wp_get_attachment_image($logo_id, 'medium', false, ['class' => 'partner-section__logo-img', 'loading' => 'lazy']); ?>
                                            </div>
                                        </li>
                                    <?php endforeach; ?>
                                </ul>
                            </div>
                        <?php endforeach; ?>
                    </div>
                </div>
                <div class="partner-section__pagination" aria-hidden="true"></div>
            </div>
        <?php endif; ?>

    </div>
</section>