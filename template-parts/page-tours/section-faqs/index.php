<?php
$_ARROW_LEFT_ICON = 66;
?>
<section id="faqs" class="faqs">
    <div class="faqs__container">
        <h2 class="heading faqs__title">Réponses à vos questions <br> de voyage.</h2>
        <div class="faqs__content">
            <?php for ($i = 0; $i < 5; $i++): ?>
            <div class="faqs__item">
                <div class="faqs__item-title">
                    <h3 class="faqs__item-title-text">
                        Quels services sont disponibles sur ce site web ?
                    </h3>
                </div>
                <div class="faqs__item-content">
                    <div class="faqs__item-content-text">
                        Nous proposons des réservations de vols, des réservations d'hôtels, des forfaits touristiques,
                        des transports locaux et des forfaits vacances complets pour diverses destinations.Nous
                        proposons des réservations de vols, des réservations d'hôtels, des forfaits touristiques, des
                        transports locaux et des forfaits vacances complets pour diverses destinations.
                    </div>
                </div>
            </div>
            <?php endfor; ?>
        </div>
    </div>
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
</section>