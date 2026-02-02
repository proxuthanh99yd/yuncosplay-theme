<?php
$icon_close_id = 1735;
?>

<div class="tour-location-popup" id="tourLocationPopup" aria-hidden="true">
    <div class="tour-location-popup__overlay" data-popup-close></div>

    <div class="tour-location-popup__panel" role="dialog" aria-modal="true">
        <button
            type="button"
            class="tour-location-popup__close"
            data-popup-close
            aria-label="Close"
        >
           <?= wp_get_attachment_image($icon_close_id, 'full', false, array( 'class' => '')) ?>
        </button>

        <div class="tour-location-popup__hero">
            <img class="tour-location-popup__hero-img" src="" alt="" />

            <div class="tour-location-popup__hero-overlay"></div>
            <div class="tour-location-popup__thumbs">
                <div class="tour-location-popup__thumbs-track"></div>
            </div>
        </div>

        <div class="tour-location-popup__body">
            <div class="tour-location-popup__content">
                <h3 class="tour-location-popup__title"></h3>
                <p class="tour-location-popup__desc"></p>
            </div>

            <button type="button" class="tour-location-popup__cta">
                <span>LOCATION DETAILS</span>
            </button>
        </div>
    </div>
</div>
