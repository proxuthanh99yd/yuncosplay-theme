<?php
$_ARROW_ICON = 43;
$_CLOSE_ICON = 30;
$_CLOSE_ARROW_ICON = 'https://horizonvietnamtravel.okhub-tech.com/wp-content/uploads/2025/04/arrow-left.svg';
$_ITEM_BACKGROUND = wp_is_mobile() ? 'https://horizonvietnamtravel.okhub-tech.com/wp-content/uploads/2025/03/Testimonial-Image-1.svg' : 'https://horizonvietnamtravel.okhub-tech.com/wp-content/uploads/2025/03/LIGHT.svg';
?>
<section class="testimonial">
    <div class="testimonial__header">
        <div class="heading testimonial__title">
            <h2>Avis de voyage avec Horizon Vietnam Travel - <strong>525 reviews</strong> avis</h2>
        </div>
        <p class="pc-body-body2-14-r testimonial__description">Depuis sa fondation, avec plus de 16 ans d’expérience,
            HORIZON VIETNAM
            Travel a organisé un grand nombre de circuits pour des dizaines de milliers de personnes. 98% des voyageurs
            se disent pleinement satisfaits de leur voyage. Dans cette page, nous vous invitons à lire plus de 700
            messages de remerciement de nos voyageurs concernant leur derniers séjours au Vietnam, au Cambodge, au Laos
            & en Thailande et à faire de connaissance avec eux.</p>
    </div>
    <div class="testimonial__body">
        <div class="testimonial__list">
            <?php for ($i = 0; $i < 8; $i++): ?>
            <div class="testimonial__item">
                <img src="<?= $_ITEM_BACKGROUND ?>" alt="" class="testimonial__item-background">
                <div class="testimonial__item-swiper-container">
                    <div class="swiper testimonial__item-swiper">
                        <div class="swiper-wrapper">
                            <?php for ($j = 0; $j < 3; $j++): ?>
                            <div class="swiper-slide testimonial__item-swiper-slide">
                                <img src="https://horizonvietnamtravel.okhub-tech.com/wp-content/uploads/2025/03/horizon-vietnam-2.webp"
                                    alt="" class="testimonial__item-swiper-background">
                                <div class="testimonial__item-swiper-overlay"></div>
                            </div>
                            <?php endfor; ?>
                        </div>
                    </div>
                    <div class="testimonial__item-swiper-pagination testimonial__item-swiper-pagination--<?= $i ?>">
                    </div>
                    <div class="testimonial__item-swiper-navigation">
                        <button
                            class="swiper-navigation testimonial__item-swiper-prev testimonial__item-swiper-prev--<?= $i ?>">
                            <?= wp_get_attachment_image($_ARROW_ICON, 'full') ?>
                        </button>
                        <button
                            class="swiper-navigation testimonial__item-swiper-next testimonial__item-swiper-next--<?= $i ?>">
                            <?= wp_get_attachment_image($_ARROW_ICON, 'full') ?>
                        </button>
                    </div>
                </div>
                <div class="testimonial__item-content">
                    <h3 class="testimonial__item-title">Jenny Tran</h3>
                    <p class="testimonial__item-subtitle">Groupe de 02 personnes</p>
                    <div class="testimonial__item-destination">
                        <div class="testimonial__item-destination-item">
                            <img src="https://horizonvietnamtravel.okhub-tech.com/wp-content/uploads/2025/03/route-square.svg"
                                alt="" class="testimonial__item-destination-icon">
                            <p class="testimonial__item-destination-text">Vietnam</p>
                        </div>
                        <div class="testimonial__item-destination-item">
                            <img src="https://horizonvietnamtravel.okhub-tech.com/wp-content/uploads/2025/03/calendar.svg"
                                alt="" class="testimonial__item-destination-icon">
                            <p class="testimonial__item-destination-text">Circuit de 13 jours</p>
                        </div>
                    </div>
                    <div class="testimonial__item-review">
                        Horizon Travel with the ha giang loop is the best things to do. I really recommend it do not
                        hesitates !! Slowly was the best driver of the trip ! An experience you will not forget !
                    </div>
                </div>
            </div>
            <?php endfor; ?>
        </div>
    </div>
    <div class="testimonial__footer">
        <nav class="pagination">
            <span class="pagination__nav pagination__nav--prev" href="">
                <img class="pagination__nav-icon"
                    src="https://horizonvietnamtravel.okhub-tech.com/wp-content/uploads/2025/03/chevron-left-double.svg"
                    alt="">
            </span>
            <ul class="pagination__list">
                <li class="pagination__item active">
                    1
                </li>
                <li class="pagination__item">
                    2
                </li>
                <li class="pagination__item">
                    3
                </li>
                <li class="pagination__item dots">
                    ...
                </li>
                <li class="pagination__item">
                    10
                </li>
            </ul>
            <span class="pagination__nav pagination__nav--next" href="">
                <img class="pagination__nav-icon"
                    src="https://horizonvietnamtravel.okhub-tech.com/wp-content/uploads/2025/03/chevron-right-double.svg"
                    alt="">
            </span>
        </nav>
    </div>
</section>

<div class="testimonial-popup">
    <div hidden class="testimonial-popup__header">
        <button class="testimonial-popup__close testimonial-popup__close--mb">
            <img src="<?= $_CLOSE_ARROW_ICON ?>" alt="">
        </button>
        <span class="testimonial-popup__header-line"></span>
        <span class="testimonial-popup__header-text">Nos témoignages</span>
    </div>
    <button class="testimonial-popup__close">
        <?= wp_get_attachment_image($_CLOSE_ICON, 'full') ?>
    </button>
    <div class="testimonial-popup__content scroll-bar-hide">
    </div>
</div>
<div class="testimonial-popup__overlay"></div>
<template id="testimonial-popup__template">
    <div class="scroll-bar-hide testimonial-popup__body">
        {{content}}
        <h3>Madame Johanne LARIN</h3>
        <p> Good morning Mrs. Ly</p>

        <p>
            How beautiful memories will keep us off this magnificent trip to Vietnam?
            People in the villages always smiling, breathtaking landscapes to the north in the Sapa region, and a
            beautiful
            two days relaxed cruise in Ha Long Bay with a magical setting. The famous monuments of the site of Angkor
            Wow
            Wow, etc…….
        </p>

        <p>
            We are very happy to have chosen you. You helped make our trip unforgettable. The guides were all nice, the
            drivers were very careful, and everything was coordinated perfectly.
            Thank you again, Mrs. Ly and Mrs. Hoan and Mrs. Yen for your great dedication
        </p>

        <p> I sent pictures of our trip to Mrs. Yen by messenger because sending them is easier</p>

        <p>Have a nice day 😍😍😍</p>
        <img src="https://horizonvietnamtravel.okhub-tech.com/wp-content/uploads/2025/03/horizon-vietnam-3.webp" alt="">
    </div>
    <div class="testimonial-popup__sidebar">
        <div class="testimonial-popup__sidebar-header">
            <h3 class="testimonial-popup__sidebar-title">{{title}}</h3>
            <p class="testimonial-popup__sidebar-subtitle">({{subtitle}})</p>
            <div class="testimonial-popup__sidebar-destination">
                {{destination}}
            </div>
        </div>
    </div>
</template>
<template id="testimonial-popup__item-template">
    <div class="testimonial-popup__sidebar-item">
        <span class="testimonial-popup__sidebar-item--label-1">{{label_1}}</span>
        <p class="testimonial-popup__sidebar-item--content-1">{{content_1}}</p>
        <span class="testimonial-popup__sidebar-item--label-2">{{label_2}}</span>
        <p class="testimonial-popup__sidebar-item--content-2">{{content_2}}</p>
    </div>
</template>