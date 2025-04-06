<?php
$_ARROW_LEFT_ICON = 310;
$_ARROW_SPECIAL_ICON = 309;
$sign_one = 297;
$sign_two = 278;
$background = 295;
$thorn = 275;
$ids_slide = [
    303,
    304,
    316,
    315,
    306,
];
$count_slide = [
    [
        "number" => 20,
        "currency" => "+",
        "title" => "MILLE VOYAGEURS",
    ],
    [
        "number" => 98,
        "currency" => "%",
        "title" => "CLIENT SATISFAIT",
    ],
    [
        "number" => 16,
        "currency" => "ans",
        "title" => "EXPÉRIENCES",
    ],
    [
        "number" => 100,
        "currency" => "+",
        "title" => "CIRCUITS UNIQUES",
    ],

];
$ids_image = [
    285,
    282,
    280,
    283,
];
$description = [
    [
        "image" => 288,
        "desc" => "Vous aider à organiser un voyage original et sur mesure",
    ],
    [
        "image" => 286,
        "desc" => "Vous aider à organiser un voyage original et sur mesure",
    ],
    [
        "image" => 276,
        "desc" => "Vous aider à organiser un voyage original et sur mesure",
    ],
    [
        "image" => 272,
        "desc" => "Vous aider à organiser un voyage original et sur mesure",
    ]
]
?>
<section class="about-us-horizon__container">
    <div class="about-us-horizon">
        <div class="about-us-horizon__content">
            <span class="about-us-horizon__subtitle">À PROPOS DE NOUS</span>
            <h2 class="about-us-horizon__title"><strong>Horizon Vietnam</strong>- Agencede voyage locale au Vietnam</h2>
            <p class="about-us-horizon__desc">HORIZON VIETNAM Travel est une agence locale francophone spécialisée dans les voyages privés et sur mesure en Indochine. Fondée par Bau, un passionné du tourisme, elle réunit une équipe expérimentée. Depuis plus de 16 ans, l’agence a organisé des circuits pour des milliers de voyageurs, avec un taux de satisfaction de 98 %, attesté par de nombreux avis positifs.</p>
            <div class="about-us-horizon__desc-line"></div>
            <div class="about-us-horizon__slide1">
                <?php foreach ($ids_slide as $id_slide) : ?>
                    <?= wp_get_attachment_image($id_slide, 'full', false, [
                        'class' => 'about-us-horizon__slide-item',
                    ]) ?>
                <?php endforeach; ?>
            </div>
            <div class="about-us-horizon__desc-line"></div>
            <div class="about-us-horizon__information">
                <?php
                foreach ($count_slide as $item) : ?>
                    <div class="about-us-horizon__information-item">
                        <div class="about-us-horizon__information-number">
                            <span class="about-us-horizon__information-number-value"><?= $item['number'] ?></span>
                            <span class="about-us-horizon__information-number-currency"><?= $item['currency'] ?></span>
                        </div>
                        <h3 class="about-us-horizon__information-title"><?= $item['title'] ?></h3>
                    </div>
                <?php endforeach; ?>
            </div>
        </div>
        <div class="about-us-horizon__desc-line-mb"></div>

        <div class="about-us-horizon__slide">
            <?= wp_get_attachment_image($sign_one, 'full', false, [
                'class' => 'about-us-horizon__slide__topsub',
            ]) ?>
            <div class="about-us-horizon__slide-container">
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
            <?= wp_get_attachment_image($sign_two, 'full', false, [
                'class' => 'about-us-horizon__slide__botsub',
            ]) ?>
        </div>

    </div>
    <?= wp_get_attachment_image($background, 'full', false, [
        'class' => 'about-us-horizon__image-background',
    ]) ?>
    <?= wp_get_attachment_image($thorn, 'full', false, [
        'class' => 'about-us-horizon__image-thorn',
    ]) ?>
    <div class="about-us-horizon_notre">
        <?php
        foreach ($ids_image as $index => $id_image) : ?>
            <?= wp_get_attachment_image($id_image, 'full', false, [
                'class' => 'about-us-horizon__image-item item-' . $index,
            ]) ?>
        <?php endforeach; ?>

        <div class="about-us-horizon_notre-content">
            <span class="about-us-horizon_subtitle pc-sub-14m">À PROPOS DE NOUS</span>
            <h2 class="about-us-horizon_title heading">Notre philosophie</h2>
            <p class="about-us-horizon_desc pc-body-body2-14-r">Horizon Vietnam Travel est une agence locale et indépendante, attachée à ses valeurs. Passionnés par notre pays, nous avons à cœur de vous faire découvrir le Vietnam sous son vrai visage, dans le respect de sa population et de son environnement.</p>
            <div class="about-us-horizon_line"></div>
            <span class="about-us-horizon_subtitle2">Nos valeurs</span>
            <div class="list-information">
                <?php foreach ($description as $item) : ?>
                    <div class="list-information-item">
                        <?= wp_get_attachment_image($item['image'], 'full', false, [
                            'class' => 'list-information-image',
                        ]) ?>
                        <p class="list-information-desc description"><?= $item['desc'] ?></p>
                    </div>
                <?php endforeach; ?>
            </div>
        </div>
    </div>
</section>