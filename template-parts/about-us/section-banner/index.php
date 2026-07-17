<?php
$banner_acf = get_field('banner') ?: [];
?>

<?php if (!empty($banner_acf)) : ?>
    <section id="about-us-banner" class="about-us-banner">
        <div class="about-us-banner__container">
            <div class="about-us-banner__overlay"></div>
            <div class="about-us-banner__swiper swiper">
                <div class="swiper-wrapper">
                    <?php foreach ($banner_acf as $i => $banner) :
                        $image_desktop = $banner['image_desktop'] ?? '';
                        $image_mobile  = $banner['image_mobile'] ?? '';
                    ?>
                        <div class="swiper-slide" data-banner-index="<?= esc_attr($i) ?>">
                            <div class="about-us-banner__slide-inner" data-swiper-parallax="70%">
                                <?php if ($image_desktop) : ?>
                                    <?= wp_get_attachment_image($image_desktop, 'full', false, okhub_image_attrs([
                                        'class'       => 'about-us-banner__image',
                                    ], $i === 0 && !IS_MOBILE ? 'lcp' : 'lazy')) ?>
                                <?php endif; ?>
                                <?php if ($image_mobile) : ?>
                                    <?= wp_get_attachment_image($image_mobile, 'full', false, okhub_image_attrs([
                                        'class'       => 'about-us-banner__image-mobile',
                                    ], $i === 0 && IS_MOBILE ? 'lcp' : 'lazy')) ?>
                                <?php endif; ?>
                            </div>
                        </div>
                    <?php endforeach; ?>
                </div>
            </div>
        </div>

        <div class="about-us-banner__content">
            <div class="about-us-banner__content-first">
                <?php foreach ($banner_acf as $i => $banner) :
                    $title       = $banner['title'] ?? '';
                    $description = $banner['description'] ?? '';
                ?>
                    <div
                        class="about-us-banner__content-item<?= $i === 0 ? ' is-active' : '' ?>"
                        data-banner-index="<?= esc_attr($i) ?>"
                        aria-hidden="<?= $i === 0 ? 'false' : 'true' ?>">
                        <?php if ($title) : ?>
                            <h2 class="about-us-banner__title"><?= esc_html($title) ?></h2>
                        <?php endif; ?>
                        <?php if ($description) : ?>
                            <div class="about-us-banner__description"><?= wp_kses_post($description) ?></div>
                        <?php endif; ?>
                    </div>
                <?php endforeach; ?>
            </div>
            <div class="about-us-banner__content-second">
                <div class="about-us-banner__pagination"></div>
            </div>
        </div>

        <div class="about-us-banner__nav-container">
            <button class="about-us-banner__nav about-us-banner__nav-prev" type="button" aria-label="Previous banner"></button>
            <button class="about-us-banner__nav about-us-banner__nav-next" type="button" aria-label="Next banner"></button>
        </div>
    </section>
<?php endif; ?>