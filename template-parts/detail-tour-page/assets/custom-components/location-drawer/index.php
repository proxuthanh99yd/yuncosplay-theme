<?php
$icon_close = 1735;
$icon_review = 1994;
$icon_location = 1995;
$icon_star = 1996;
?>

<div id="location-drawer" class="location-drawer">
    <div id="location-drawer-overlay" class="location-drawer__overlay"></div>
    <div id="location-drawer-content" class="location-drawer__content">
        <button data-close-location-drawer-trigger class="location-drawer__close-btn">
            <?php echo wp_get_attachment_image($icon_close, 'full', false, array( 'class' => '')) ?>
        </button>
        <div class="location-drawer__gallery">
            <div class="location-drawer__gallery-swiper-main swiper">
                <div data-lenis-prevent class="swiper-wrapper location-drawer__gallery-swiper-main-wrapper">
                </div>
            </div>
            <div thumbsSlider="" class="location-drawer__gallery-swiper-thumbs swiper">
                <div data-lenis-prevent class="swiper-wrapper location-drawer__gallery-swiper-thumbs-wrapper">
                </div>
            </div>
        </div>
        <div class="location-drawer__details">
            <h3 class="location-drawer__title"></h3>
            <div data-lenis-prevent class="location-drawer__desc-wrapper custom-scrollbar">
                <div class="location-drawer__desc">
                </div>
            </div>
            <a href="/" class="location-drawer__link compound-avian-button">
                <div class="compound-avian-button__content">
                    <span class="location-drawer__link-text">Location detail</span>
                </div>
            </a>
        </div>
    </div>
</div>