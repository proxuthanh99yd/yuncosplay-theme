<?php
// template-parts/detail-tour-page/components/tour-hotel-popup.php
?>

<div class="tour-hotel-popup" id="tourHotelPopup" aria-hidden="true">
    <div class="tour-hotel-popup__overlay" data-hotel-popup-close></div>

    <div class="tour-hotel-popup__panel" role="dialog" aria-modal="true">
        <button
            type="button"
            class="tour-hotel-popup__close"
            data-hotel-popup-close
            aria-label="Close"
        >
            <img
                src="<?php echo get_template_directory_uri(); ?>/template-parts/detail-tour-page/assets/icons/d-icon-close.webp"
                alt="img-close"
                draggable="false"
            />
        </button>

        <div class="tour-hotel-popup__hero">
            <img class="tour-hotel-popup__hero-img" src="" alt="" draggable="false" />

            <div class="tour-hotel-popup__thumbs">
                <div class="tour-hotel-popup__thumbs-track"></div>
            </div>
        </div>

        <div class="tour-hotel-popup__body">
            <div class="tour-hotel-popup__content">
                <h3 class="tour-hotel-popup__title"></h3>

                <div class="tour-hotel-popup__meta">
                    <div class="tour-hotel-popup__meta-row">
                        <span class="tour-hotel-popup__meta-icon"></span>
                        <span class="tour-hotel-popup__meta-label">Review:</span>
                        <span class="tour-hotel-popup__stars" aria-label="rating"></span>
                    </div>

                    <div class="tour-hotel-popup__meta-row">
                        <span class="tour-hotel-popup__meta-icon"></span>
                        <span class="tour-hotel-popup__meta-text tour-hotel-popup__address"></span>
                        <button
                            type="button"
                            class="tour-hotel-popup__map"
                            data-hotel-popup-map
                        >
                            Display map
                        </button>
                    </div>
                </div>

                <div class="tour-hotel-popup__divider"></div>

                <p class="tour-hotel-popup__desc"></p>

                <div class="tour-hotel-popup__divider"></div>
            </div>

            <button type="button" class="tour-hotel-popup__cta">
                <span>HOTEL DETAIL</span>
            </button>
        </div>
    </div>
</div>
