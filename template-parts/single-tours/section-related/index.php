<?php
$_COMPASS_ICON = 57;
$_LOCATION_ICON = 63;
$_ARROW_LEFT_ICON = 56;
$_ARROW_SPECIAL_ICON = 43;
?>

<section class="related" id="related">
    <div class="related__container">
        <div class="related__header">
            <h2 class="related__title">Dernière visite de la visite</h2>
        </div>
        <div class="related__body">
            <div class="customized-trip__content">
                <div class="swiper customized-trip__swiper">
                    <div class="swiper-wrapper">
                        <?php for ($j = 0; $j < 5; $j++): ?>
                            <div class="swiper-slide">
                                <div class="customized-trip__card">
                                    <div class="customized-trip__card-image">
                                        <img class="customized-trip__card-image-main"
                                            src="/wp-content/uploads/2025/03/customize-trip-item.webp" alt="">
                                        <span class="customized-trip__card-image-icon">
                                            <?= wp_get_attachment_image($_COMPASS_ICON, 'full') ?>
                                            <span>Aventure</span>
                                        </span>
                                    </div>
                                    <div class="customized-trip__card-overlay"></div>
                                    <div class="customized-trip__card-content">
                                        <h3 class="customized-trip__card-title">Vietnam en 13 jours – Pure Évasion</h3>
                                        <p class="customized-trip__card-duration">13 jours- 10 nuits</p>
                                        <p class="customized-trip__card-location">
                                            <?= wp_get_attachment_image($_LOCATION_ICON, 'full') ?>
                                            <span>Hanoi - Tuan Chau - Lan Ha Bay - Ha Giang - Cao Bang - Hanoi</span>
                                        </p>
                                        <button class="customized-trip__card-button">
                                            <span>Découvrir</span>
                                            <?= wp_get_attachment_image($_ARROW_LEFT_ICON, 'full') ?>
                                        </button>
                                    </div>
                                    <a class="customized-trip__card-link" href=""></a>
                                </div>
                            </div>
                        <?php endfor; ?>
                    </div>
                </div>
            </div>
            <div class="customized-trip__swiper__nav">
                <button class="swiper-navigation customized-trip__swiper-button customized-trip__swiper-button-prev">
                    <?= wp_get_attachment_image($_ARROW_SPECIAL_ICON, 'full') ?>
                </button>
                <button class="swiper-navigation customized-trip__swiper-button customized-trip__swiper-button-next">
                    <?= wp_get_attachment_image($_ARROW_SPECIAL_ICON, 'full') ?>
                </button>
            </div>
        </div>
    </div>
</section>