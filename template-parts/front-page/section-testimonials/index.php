<?php
$_UNION = wp_is_mobile() ? 109 : 107;
$_ARROW_ICON = 43;
$_BACKGROUND =  106;
$_STAR_ICON = 108;
?>
<section class="testimonials">
    <?= wp_get_attachment_image($_BACKGROUND, 'full', false, [
        'class' => 'testimonials__background'
    ]) ?>
    <div class="testimonials__container">
        <div class="testimonials__header">
            <span class="pc-tag-14-m testimonials__header-subtitle">
                TESTIMONIAL
            </span>
            <div class="heading testimonials__header-title">
                <h2>Donnez un avis sur 252 <br> <strong>reviews</strong> des clients</h2>
            </div>
            <p class="pc-body-body2-14-r testimonials__header-description">L'agence de voyage locale fiable a reçu de
                nombreuses <br>
                évaluations 5 étoiles sur les forums de voyage.
            </p>
        </div>
        <div class="testimonials__body">
            <div class="swiper testimonials__swiper">
                <div class="swiper-wrapper">
                    <?php for ($i = 0; $i < 10; $i++): ?>
                    <div class="swiper-slide">
                        <div class="testimonials__item">
                            <div class="testimonials__item-header">
                                <div class="testimonials__item-bg">
                                    <?= wp_get_attachment_image($_UNION, 'full') ?>
                                </div>
                                <div class="testimonials__item-content">
                                    <div class="testimonials__item-content-header">
                                        <p class="testimonials__item-content-title">
                                            Elliot
                                        </p>
                                        <p class="testimonials__item-content-subtitle">
                                            Elliot
                                        </p>
                                    </div>
                                    <div class="testimonials__item-content-body">
                                        <p>“Lorem ipsum dolor sit amet, consectetur adipiscing elit. Sed do eiusmod
                                            tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam,
                                            quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo
                                            consequat.”</p>
                                    </div>
                                    <div class="testimonials__item-content-footer">
                                        <div class="testimonials__item-content-footer-rating">
                                            <?php for ($j = 0; $j < 5; $j++): ?>
                                            <?= wp_get_attachment_image($_STAR_ICON, 'full') ?>
                                            <?php endfor; ?>
                                        </div>
                                        <p class="testimonials__item-content-footer-numerical">
                                            5.0
                                        </p>
                                    </div>
                                </div>
                                <div class="testimonials__item-slide">
                                    <div class="swiper testimonials__item-swiper">
                                        <div class="swiper-wrapper">
                                            <?php for ($j = 0; $j < 5; $j++): ?>
                                            <div class="swiper-slide">
                                                <img src="https://swiperjs.com/demos/images/nature-<?= $j + 1 ?>.jpg"
                                                    alt="">
                                                <div class="testimonials__item-swiper-overlay"></div>
                                            </div>
                                            <?php endfor; ?>
                                        </div>
                                    </div>
                                    <div class="testimonials__item-swiper-nav">
                                        <button
                                            class="swiper-navigation testimonials__item-swiper-prev testimonials__item-swiper-prev-<?= $i ?>">
                                            <?= wp_get_attachment_image($_ARROW_ICON, 'full') ?>
                                        </button>
                                        <button
                                            class="swiper-navigation testimonials__item-swiper-next testimonials__item-swiper-next-<?= $i ?>">
                                            <?= wp_get_attachment_image($_ARROW_ICON, 'full') ?>
                                        </button>
                                    </div>
                                    <div
                                        class="testimonials__item-swiper-pagination testimonials__item-swiper-pagination-<?= $i ?>">
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <?php endfor; ?>
                </div>
            </div>
            <div class="testimonials__swiper-nav">
                <button class="swiper-navigation testimonials__swiper-prev">
                    <?= wp_get_attachment_image($_ARROW_ICON, 'full') ?>
                </button>
                <button class="swiper-navigation testimonials__swiper-next">
                    <?= wp_get_attachment_image($_ARROW_ICON, 'full') ?>
                </button>
            </div>
        </div>
    </div>
</section>