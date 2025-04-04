<?php
$_ARROW_LEFT_ICON = 66;
$_CLOUD_BACKGROUND = wp_is_mobile() ? 71 : 70;
?>

<section class="discover">
    <div class="discover__header">
        <div class="discover__header__content">
            <span class="pc-tag-14-m discover__subtitle">DÉCOUVRIR</span>
            <div class="heading discover__title">
                <h2>
                    Nos Inspirations de <strong>voyage</strong>
                </h2>
            </div>
        </div>
        <a class="pc-button-16-b discover__header-link" href="">Explorer davantage</a>
    </div>
    <div class="discover__content">
        <div class="discover__swiper-container">
            <div class="swiper discover__swiper"
                style="--swiper-wrapper-transition-timing-function: cubic-bezier(0.77, 0, 0.22, 0.99);">
                <div class="swiper-wrapper">
                    <?php for ($i = 0; $i < 6; $i++): ?>
                        <div class="swiper-slide">
                            <div class="discover__slide">
                                <img class="discover__slide-image"
                                    src="https://swiperjs.com/demos/images/nature-<?= $i + 1 ?>.jpg" alt="">
                                <div class="discover__slide-overlay"></div>
                                <h3 class="discover__slide-title">Admirez la beauté naturelle. <?= $i ?></h3>
                            </div>
                        </div>
                    <?php endfor; ?>
                </div>
                <div class="discover__swiper-nav">
                    <button class="discover__swiper-prev">
                        <?= wp_get_attachment_image($_ARROW_LEFT_ICON, 'full') ?>
                    </button>
                    <button class="discover__swiper-next">
                        <?= wp_get_attachment_image($_ARROW_LEFT_ICON, 'full') ?>
                    </button>
                </div>
            </div>

            <div class="discover__thumb-swiper-container">
                <div class="swiper discover__thumb-swiper">
                    <div class="swiper-wrapper">
                        <?php for ($i = 0; $i < 6; $i++): ?>
                            <div class="swiper-slide">
                                <div class="discover__thumb-slide">
                                    <img class="discover__thumb-slide-image"
                                        src="/wp-content/uploads/2025/03/discover-image.png" alt="">
                                </div>
                            </div>
                        <?php endfor; ?>
                    </div>
                </div>
            </div>
        </div>
        <div class="discover__categories">
            <?php for ($i = 0; $i < 4; $i++): ?>
                <div class="discover__category">
                    <img class="discover__category-image" src="/wp-content/uploads/2025/03/Rectangle-34624892.png" alt="">
                    <div class="discover__category-overlay"></div>
                    <div class="discover__category-content">
                        <h3 class="pc-h5-22b mb-14-b discover__category-title">Aventure</h3>
                        <p class="pc-body-body2-14-r discover__category-description">
                            <span
                                class="pc-body-body2-14-r mb-14-b discover__category-description discover__category-description--inner">
                                Endroit incroyable pour le tourisme à
                                moto dans le nord du
                                Vietnam
                            </span>
                        </p>
                    </div>
                </div>
            <?php endfor; ?>
        </div>
    </div>
    <div class="discover__footer">
        <img class="discover__footer-bg" src="/wp-content/uploads/2025/03/discover-footer.webp" alt="">
        <div class="discover__clouds">
            <?= wp_get_attachment_image($_CLOUD_BACKGROUND, 'full') ?>
        </div>
    </div>
</section>