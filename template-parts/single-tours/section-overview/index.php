<?php
$_ARROW_LEFT_ICON = 66;
?>
<section id="overview" class="overview">
    <div class="overview__images-container">
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
                <?php for ($i = 0; $i < 2; $i++): ?>
                    <div class="discover__category">
                        <img class="discover__category-image" src="/wp-content/uploads/2025/03/Rectangle-34624892.png"
                            alt="">
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
    </div>
    <div class="overview__content">
        <h2 class="pc-h5-22b overview__title">L'esprit du voyage</h2>
        <div class="overview__description">
            <p>Préparez-vous à une exploration immersive unique au Vietnam. Loin des sentiers battus, ce circuit vous
                invite à rencontrer l'âme véritable du pays. Plongez au cœur de traditions ancestrales, savourez la
                richesse d'une culture millénaire et laissez-vous surprendre par la beauté brute des paysages
                vietnamiens. Une expérience authentique et inoubliable vous attend.</p>
        </div>
        <h3 class="pc-button-16-b overview__interest-title">Centre d'intérêt:</h3>
        <div style="--check-circle: url('https://horizonvietnamtravel.okhub-tech.com/wp-content/uploads/2025/04/Interest-Icon.svg')"
            class="overview__interest">
            <ul>
                <li>Voyage privé et 100% sur mesure</li>
                <li>Découvrir des plus beaux sites du pays</li>
                <li>Contempler la beauté de la nature et des paysages</li>
                <li>Découverte de la culture, de la tradition et du mode de vie des Vietnamiens locaux</li>
                <li>Rencontre chaleureuse avec des populations locales</li>
                <li>Croisière inoubliable dans la baie d’Ha Long en 02 jours et 01 nuit à bord d’une jonque de style
                    oriental au confort moderne</li>
                <li>Visite des marchés flottants typiques du Sud</li>
            </ul>
        </div>
        <div style="--location: url('https://horizonvietnamtravel.okhub-tech.com/wp-content/uploads/2025/04/Program-Icon.svg')"
            class="overview__summary">
            <div class="overview__summary-program">
                <h3 class="pc-button-16-b overview__interest-title">Programme en bref:</h3>
                <?php for ($i = 0; $i < 12; $i++): ?>
                    <div class="overview__program-item">
                        <img src="https://horizonvietnamtravel.okhub-tech.com/wp-content/uploads/2025/04/Program-Icon.svg"
                            alt="">
                        <div class="overview__program-item-text">
                            <strong>Jour <?= $i + 1 ?>:</strong>
                            <span>Baie de Bai Tu Long – Croisière – Hanoi ✈ Da Nang – Hoi An</span>
                        </div>
                    </div>
                    <?php if ($i != 11): ?>
                        <div class="overview__program-item-line">
                            <img src="https://horizonvietnamtravel.okhub-tech.com/wp-content/uploads/2025/04/Liine.svg" alt="">
                        </div>
                    <?php endif; ?>
                <?php endfor; ?>
            </div>
            <div class="overview__summary-map">
                <img src="https://horizonvietnamtravel.okhub-tech.com/wp-content/uploads/2025/04/map.webp" alt="">
            </div>
        </div>
    </div>
</section>