<?php
$_PLAY_ICON = 112;
$_ARROW_ICON = 43;
$_STAR_ICON = 108;
?>
<section class="testimonials-2nd">
    <div class="testimonials-2nd__container">
        <div class="testimonials-2nd__swiper-container">
            <div class="swiper testimonials-2nd__swiper">
                <div class="swiper-wrapper">
                    <?php for ($i = 0; $i < 3; $i++): ?>
                    <div class="swiper-slide">
                        <div class="testimonials-2nd__item">
                            <div class="testimonials-2nd__item-content">
                                <h3>Kiel Tredrea <?= $i ?></h3>
                                <span>Groupe de 02 personnes</span>
                                <p>I really recommend it do not <br> hesitates !! Slowly was the best driver <br> of the
                                    trip !
                                    An experience you will not <br> forget in your life !</p>
                                <div class="testimonials-2nd__item-rating">
                                    <div class="testimonials-2nd__item-rating-stars">
                                        <?php for ($j = 0; $j < 5; $j++): ?>
                                        <?= wp_get_attachment_image($_STAR_ICON, 'full') ?>
                                        <?php endfor; ?>
                                    </div>
                                    <p class="testimonials-2nd__item-rating-numerical">
                                        5.0
                                    </p>
                                </div>
                            </div>
                            <div class="testimonials-2nd__item-media">
                                <div class="testimonials-2nd__player" data-plyr-provider="youtube"
                                    data-plyr-embed-id="IEyMZlEfsZA">
                                </div>
                                <!-- <img class="testimonials-2nd__item-thumbnail"
                                    src="/wp-content/uploads/2025/03/horizon-vietnam-2.webp" alt=""> -->
                            </div>
                        </div>
                    </div>
                    <?php endfor; ?>
                </div>
            </div>
            <div class="testimonials-2nd__swiper-nav">
                <button class="swiper-navigation testimonials-2nd__swiper-button-prev">
                    <?= wp_get_attachment_image($_ARROW_ICON, 'full') ?>
                </button>
                <button class="swiper-navigation testimonials-2nd__swiper-button-next">
                    <?= wp_get_attachment_image($_ARROW_ICON, 'full') ?>
                </button>
            </div>
            <div class="testimonials-2nd__swiper-pagination">
            </div>
        </div>
        <div class="testimonials-2nd__nav">
            <?php for ($j = 0; $j < 3; $j++): ?>
            <div class="testimonials-2nd__nav-item <?= $j == 0 ? 'active' : '' ?>">
                <?= wp_get_attachment_image(113, 'full', false, [
                        'class' => 'testimonials-2nd__nav-item-avatar'
                    ]) ?>
                <div class="testimonials-2nd__nav-item-content">
                    <h4>Kiel Tredrea <?= $j ?></h4>
                    <span>Groupe de 02 personnes</span>
                </div>
                <div class="testimonials-2nd__nav-item-rating">
                    <?= wp_get_attachment_image($_STAR_ICON, 'full') ?>
                    5.0
                </div>
            </div>
            <?php endfor; ?>
        </div>
    </div>
    <template id="testimonials-2nd__item-template">
        <div class="testimonials-2nd__item">
            <div class="testimonials-2nd__item-content">
                <h3></h3>
                <span></span>
                <p></p>
                <div class="testimonials-2nd__item-rating">
                    <div class="testimonials-2nd__item-rating-stars">
                        <?php for ($j = 0; $j < 5; $j++): ?>
                        <?= wp_get_attachment_image($_STAR_ICON, 'full') ?>
                        <?php endfor; ?>
                    </div>
                    <p class="testimonials-2nd__item-rating-numerical">
                    </p>
                </div>
            </div>
            <div class="testimonials-2nd__item-media">
                <video src="">
                </video>
                <iframe src="" frameborder="0"></iframe>
                <img class="testimonials-2nd__item-thumbnail" src="" alt="">
                <div class="testimonials-2nd__item-overlay"></div>
                <button class="testimonials-2nd__item-play">
                    <?= wp_get_attachment_image($_PLAY_ICON, 'full') ?>
                </button>
            </div>
        </div>
    </template>
</section>