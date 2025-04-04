<?php
$_SHADOW_IMG_1 = 42;
$_SHADOW_IMG_2 = 41;
$_SHADOW_IMG_3 = 40;
$_SHADOW_IMG_4 = 39;
$_ICON_ARROW = 43;
?>

<section class="horizon-vietnam">
    <div class="horizon-vietnam__images">
        <div class="horizon-vietnam__image horizon-vietnam__image--1">
            <img class="horizon-vietnam__image-image" src="/wp-content/uploads/2025/03/horizon-vietnam-1.webp" alt="">
            <?= wp_get_attachment_image($_SHADOW_IMG_1, 'full', false, [
                'class' => 'horizon-vietnam__shadow'
            ]) ?>
        </div>
        <div class="horizon-vietnam__image horizon-vietnam__image--2">
            <img class="horizon-vietnam__image-image" src="/wp-content/uploads/2025/03/horizon-vietnam-3.webp" alt="">
            <?= wp_get_attachment_image($_SHADOW_IMG_2, 'full', false, [
                'class' => 'horizon-vietnam__shadow'
            ]) ?>
        </div>
        <div class="horizon-vietnam__image horizon-vietnam__image--3">
            <img class="horizon-vietnam__image-image" src="/wp-content/uploads/2025/03/horizon-vietnam-2.webp" alt="">
            <?= wp_get_attachment_image($_SHADOW_IMG_3, 'full', false, [
                'class' => 'horizon-vietnam__shadow'
            ]) ?>
        </div>
        <div class="horizon-vietnam__image horizon-vietnam__image--4">
            <img class="horizon-vietnam__image-image" src="/wp-content/uploads/2025/03/horizon-vietnam-4.webp" alt="">
            <?= wp_get_attachment_image($_SHADOW_IMG_4, 'full', false, [
                'class' => 'horizon-vietnam__shadow'
            ]) ?>
        </div>
    </div>
    <div class="horizon-vietnam__content">
        <span class="pc-tag-14-m horizon-vietnam__subtitle">horizon VIETNAM</span>
        <div class="heading horizon-vietnam__title">
            <h2>
                Découvrez le <strong>Vietnam</strong><br>
                merveilleux avec nous.
            </h2>
        </div>
        <p class="description horizon-vietnam__description">
            Nous proposons des circuits de tourisme en Asie du Sud-Est, incluant le Vietnam, le Cambodge, le Laos, la
            Thaïlande et le Sri Lanka, offrant des expériences riches avec de magnifiques paysages, un patrimoine
            culturel unique et une gastronomie variée.
        </p>
        <p class="horizon-vietnam__swiper-title">Destination impressionnante</p>
        <div class="horizon-vietnam__swiper-container">
            <div class="swiper horizon-vietnam__swiper">
                <div class="swiper-wrapper">
                    <?php for ($i = 0; $i < 5; $i++): ?>
                    <div class="swiper-slide">
                        <div class="horizon-vietnam__card">
                            <img class="horizon-vietnam__card-img"
                                src="/wp-content/uploads/2025/03/david-emrich-f6hlrIboDUY-unsplash-1.png" alt="">
                            <span class="horizon-vietnam__card-overlay"></span>
                            <div class="horizon-vietnam__card-content">
                                <span class="horizon-vietnam__card-location">Hagiang</span>
                                <p class="pc-sub-14m horizon-vietnam__card-title">Boucles de Ha Giang Challenging</p>
                                <span class="horizon-vietnam__card-visiter">12 visites</span>
                            </div>
                            <a class="horizon-vietnam__card-link" href=""></a>
                        </div>
                    </div>
                    <?php endfor; ?>
                </div>
            </div>
        </div>
        <div class="horizon-vietnam__swiper-nav">
            <button class="swiper-navigation horizon-vietnam__swiper-prev">
                <?= wp_get_attachment_image($_ICON_ARROW, 'full') ?>
            </button>
            <button class="swiper-navigation horizon-vietnam__swiper-next">
                <?= wp_get_attachment_image($_ICON_ARROW, 'full') ?>
            </button>
        </div>
    </div>
</section>