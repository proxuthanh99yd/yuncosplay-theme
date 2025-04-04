<?php
$_BACKGROUND_IMAGE_DECO_1 = 61;
$_BACKGROUND_IMAGE_DECO_2 = 62;
$_FLAG_SHADOW = 58;
$_COMPASS_ICON = 57;
$_LOCATION_ICON = 63;
$_ARROW_LEFT_ICON = 56;
$_ARROW_SPECIAL_ICON = 43;
$_ARROW_DOWN_ICON = 16;
?>

<section class="customized-trip">
    <div class="customized-trip__deco customized-trip__deco--3"></div>
    <?= wp_get_attachment_image($_BACKGROUND_IMAGE_DECO_1, 'full', false, [
        'class' => 'customized-trip__deco customized-trip__deco--2',
    ]) ?>
    <?= wp_get_attachment_image($_BACKGROUND_IMAGE_DECO_2, 'full', false, [
        'class' => 'customized-trip__deco customized-trip__deco--2',
    ]) ?>
    <div class="customized-trip__container">
        <div class="customized-trip__header">
            <span class="pc-tag-14-m customized-trip__subtitle">VOYAGE SUR-MESURE</span>
            <div class="heading customized-trip__title">
                <h2>Nos Coups De Coeur</h2>
            </div>
        </div>
        <nav class="customized-trip__nav">
            <div class="customized-trip__nav-select">
                <span class="customized-trip__nav-icon">
                    <img class="customized-trip__nav-flag" src="/wp-content/uploads/2025/03/vietnam-flag.svg" alt="">
                    <?= wp_get_attachment_image($_FLAG_SHADOW, 'full', false, [
                        'class' => 'customized-trip__nav-flag-shadow',
                    ]) ?>
                </span>
                <span class="text">VIETNAM 1</span>
                <span class="customized-trip__nav-arrow">
                    <?= wp_get_attachment_image($_ARROW_DOWN_ICON, 'full') ?>
                </span>
            </div>
            <ul class="customized-trip__nav-list">
                <?php for ($i = 0; $i < 5; $i++): ?>
                <li class="pc-sub-14m customized-trip__nav-item <?= $i === 0 ? 'active' : '' ?>">
                    <span class="customized-trip__nav-icon">
                        <img class="customized-trip__nav-flag" src="/wp-content/uploads/2025/03/vietnam-flag.svg"
                            alt="">
                        <?= wp_get_attachment_image($_FLAG_SHADOW, 'full', false, [
                                'class' => 'customized-trip__nav-flag-shadow',
                            ]) ?>
                    </span>
                    VIETNAM <?= $i + 1 ?>
                </li>
                <?php endfor; ?>
            </ul>
        </nav>
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
        <div class="customized-trip__swiper-pagination"></div>
    </div>
    <template id="customized-trip__card-template">
        <div class=" customized-trip__card">
            <div class="customized-trip__card-image">
                <img class="customized-trip__card-image-main" src="" alt="">
                <span class="customized-trip__card-image-icon">
                    <?= wp_get_attachment_image($_COMPASS_ICON, 'full') ?>
                    <span></span>
                </span>
            </div>
            <div class="customized-trip__card-overlay"></div>
            <div class="customized-trip__card-content">
                <h3 class="customized-trip__card-title"></h3>
                <p class="customized-trip__card-duration"></p>
                <p class="customized-trip__card-location">
                    <?= wp_get_attachment_image($_LOCATION_ICON, 'full') ?>
                    <span></span>
                </p>
                <button class="customized-trip__card-button">
                    <span>Découvrir</span>
                    <?= wp_get_attachment_image($_ARROW_LEFT_ICON, 'full') ?>
                </button>
            </div>
            <a class="customized-trip__card-link" href=""></a>
        </div>
    </template>
</section>