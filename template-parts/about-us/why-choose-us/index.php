<?php
$sticky_image = 273;
$icon_arrow = 263;
$icon_bg = 267;
$item_thumb = 247;
$reasons = [
    "Agence locale professionnelle et reconnue",
    "Service personnalisé et à l’écoute",
    "Prix compétitifs et transparence",
    "Expérience et expertise locale",
    "Voyages sur mesure adaptés à vos envies",
    "Engagement pour un tourisme responsable",
    "Assistance 24/7 pendant votre voyage",
    "Guides passionnés et expérimentés",
    "Sécurité et confort garantis",
    "Témoignages clients positifs"
];
?>
<section class="why-choose-us__section">
    <section class="why-choose-us">
        <div class="why-choose-us__sticky">
            <div class="why-choose-us__sticky__content">
                <span class="why-choose-us__subtitle pc-sub-14m">À PROPOS DE NOUS</span>
                <h2 class="why-choose-us__title"><strong>10 bonnes</strong> raisons de nous choisir</h2>
                <p class="why-choose-us__desc">HORIZON VIETNAM Travel est une agence locale francophone spécialisée dans les voyages privés et sur mesure en Indochine. Fondée par Bau, un passionné du tourisme, elle réunit une équipe expérimentée.</p>
            </div>
            <?= wp_get_attachment_image($sticky_image, 'full', false, [
                'class' => 'why-choose-us__sticky__thumb',
            ]) ?>
        </div>
    
        <?php if (!wp_is_mobile()) { ?>
            <div class="why-choose-us__reasons">
                <div class="why-choose-us__reasons-column">
                    <?php foreach ($reasons as $index => $title) : ?>
                        <?php if (($index + 1) % 2 != 0) : ?>
                            <div class="why-choose-us__reasons-items">
                                <span class="why-choose-us__reasons-items__number">
                                    <?= str_pad($index + 1, 2, '0', STR_PAD_LEFT); ?>
                                </span>
                                <h3 class="why-choose-us__reasons-items__title"><?= $title; ?></h3>
                                <p>Horizon Vietnam est une agence de voyage locale, professionnelle et reconnue au Vietnam. Nous avons une équipe d'experts passionnés par le voyage et la culture vietnamienne.</p>
                                <div class="why-choose-us__reasons-links">
                                    <?= wp_get_attachment_image($icon_arrow, 'full', false); ?>
                                </div>
                                <?= wp_get_attachment_image($icon_bg, 'full', false, ['class' => 'why-choose-us__reasons-items__bg']); ?>
                                <?= wp_get_attachment_image($item_thumb, 'full', false, ['class' => 'why-choose-us__reasons-items__thumb']); ?>
    
                            </div>
                        <?php endif; ?>
                    <?php endforeach; ?>
                </div>
                <div class="why-choose-us__reasons-column column-2">
                    <?php foreach ($reasons as $index => $title) : ?>
                        <?php if (($index + 1) % 2 == 0) : ?>
                            <div class="why-choose-us__reasons-items">
                                <span class="why-choose-us__reasons-items__number">
                                    <?= str_pad($index + 1, 2, '0', STR_PAD_LEFT); ?>
                                </span>
                                <h3 class="why-choose-us__reasons-items__title"><?= $title; ?></h3>
                                <p>Horizon Vietnam est une agence de voyage locale, professionnelle et reconnue au Vietnam. Nous avons une équipe d'experts passionnés par le voyage et la culture vietnamienne.</p>
                                <div class="why-choose-us__reasons-links">
                                    <?= wp_get_attachment_image($icon_arrow, 'full', false); ?>
                                </div>
                                <?= wp_get_attachment_image($icon_bg, 'full', false, ['class' => 'why-choose-us__reasons-items__bg']); ?>
                                <?= wp_get_attachment_image($item_thumb, 'full', false, ['class' => 'why-choose-us__reasons-items__thumb']); ?>
    
                            </div>
                        <?php endif; ?>
                    <?php endforeach; ?>
                </div>
            </div>
        <?php } else { ?>
            <div class="why-choose-us__reasons scroll-bar-hide">
                <?php foreach ($reasons as $index => $title) : ?>
                    <div class="why-choose-us__reasons-items">
                        <span class="why-choose-us__reasons-items__number"><?= str_pad($index + 1, 2, '0', STR_PAD_LEFT); ?></span>
                        <h3 class="why-choose-us__reasons-items__title"><?= $title; ?></h3>
                        <p>Horizon Vietnam est une agence de voyage locale, professionnelle et reconnue au Vietnam. Nous avons une équipe d'experts passionnés par le voyage et la culture vietnamienne.</p>
                        <div class="why-choose-us__reasons-links">
                            <?= wp_get_attachment_image($icon_arrow, 'full', false) ?>
                        </div>
                        <?= wp_get_attachment_image($icon_bg, 'full', false, [
                            'class' => 'why-choose-us__reasons-items__bg',
                        ]) ?>
                        <?= wp_get_attachment_image($item_thumb, 'full', false, ['class' => 'why-choose-us__reasons-items__thumb']); ?>
    
                    </div>
                <?php endforeach; ?>
            <?php } ?>
    </section>
</p>