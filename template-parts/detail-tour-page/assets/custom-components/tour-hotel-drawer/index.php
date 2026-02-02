<?php
$icon_close = 1735;
$icon_review = 1994;
$icon_location = 1995;
$icon_star = 1996;
?>

<div id="hotel-info-drawer" class="hotel-info-drawer">
    <div id="hotel-info-drawer-overlay" class="hotel-info-drawer__overlay"></div>
    <div id="hotel-info-drawer-content" class="hotel-info-drawer__content">
        <button data-close-hotel-drawer-trigger class="hotel-info-drawer__close-btn">
            <?php echo wp_get_attachment_image($icon_close, 'full', false, array( 'class' => '')) ?>
        </button>
        <div class="hotel-info-drawer__gallery">
            <div class="hotel-info-drawer__gallery-swiper-main swiper">
                <div data-lenis-prevent class="swiper-wrapper hotel-info-drawer__gallery-swiper-main-wrapper">
                </div>
            </div>
            <div thumbsSlider="" class="hotel-info-drawer__gallery-swiper-thumbs swiper">
                <div data-lenis-prevent class="swiper-wrapper hotel-info-drawer__gallery-swiper-thumbs-wrapper">
                </div>
            </div>
        </div>
        <div class="hotel-info-drawer__details">
            <h3 class="hotel-info-drawer__title">JW Marriott Hotel Hanoi</h3>
            <div class="hotel-info-drawer__meta">
                <div class="hotel-info-drawer__meta__item hotel-info-drawer__meta__review">
                    <span class="hotel-info-drawer__meta__item-icon">
                        <?php echo wp_get_attachment_image($icon_review, 'full', false, array( 'class' => '')) ?>
                    </span>
                    <p class="hotel-info-drawer__meta__item-label">Review:</p>
                    <div class="hotel-info-drawer__meta__item-value hotel-info-drawer__meta__review-rating">
                        <?php echo wp_get_attachment_image($icon_star, 'full', false, array( 'class' => '')) ?>
                        <?php echo wp_get_attachment_image($icon_star, 'full', false, array( 'class' => '')) ?>
                        <?php echo wp_get_attachment_image($icon_star, 'full', false, array( 'class' => '')) ?>
                        <?php echo wp_get_attachment_image($icon_star, 'full', false, array( 'class' => '')) ?>
                        <?php echo wp_get_attachment_image($icon_star, 'full', false, array( 'class' => '')) ?>
                    </div>
                </div>
                <div class="hotel-info-drawer__meta__item hotel-info-drawer__meta__location">
                    <span class="hotel-info-drawer__meta__item-icon">
                        <?php echo wp_get_attachment_image($icon_location, 'full', false, array( 'class' => '')) ?>
                    </span>
                    <p class="hotel-info-drawer__meta__item-label hotel-info-drawer__meta__location-label">8 Do Duc Duc, Hanoi, Vietnam</p>
                    <a href="#" class="hotel-info-drawer__meta__item-value hotel-info-drawer__meta__location-link">
                        Display map
                    </a>
                </div>
            </div>
            <div data-lenis-prevent class="hotel-info-drawer__desc-wrapper custom-scrollbar">
                <p class="hotel-info-drawer__desc">
                    Start your journey off right at our hotel, where complimentary Wi-Fi is available in all rooms. Strategically located near Dong Xuan Market, our hotel offers easy access to local attractions and sightseeing. Don't miss the chance to explore the renowned Old Quarter. For your convenience, an on-site restaurant is available to cater to your needs.
                </p>
            </div>
            <a href="/" class="hotel-info-drawer__link compound-avian-button">
                <div class="compound-avian-button__content">
                    <span class="hotel-info-drawer__link-text">Hotel detail</span>
                </div>
            </a>
        </div>
    </div>
</div>