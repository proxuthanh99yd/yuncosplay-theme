<?php
$img_human = 302;
$icon_arrow = 263;
$bg_section = 294;
?>
<section class="experts-locaux-section">
    <?= wp_get_attachment_image($bg_section, 'full', false, [
        'class' => 'experts-locaux-section__background',
    ]) ?>
    <div class="container-section">
        <span class="experts-locaux-section__subtitle">NOTRE ÉQUIPE</span>
        <h2 class="experts-locaux-section__title">Des experts locaux à <strong>votre écoute</strong></h2>
        <div class="experts-locaux-section__content">
            <div class="swiper expertsLocauxSwiper">
                <div class="swiper-wrapper">
                    <?php for ($i = 1; $i <= 10; $i++): ?>
                        <div class="swiper-slide">
                            <div class="expertsLocauxSwiper-item">
                                <?= wp_get_attachment_image($img_human, 'full', false, [
                                    'class' => 'expertsLocauxSwiper-item__image',
                                ]) ?>
                                <div class="expertsLocauxSwiper-item__content">
                                    <span class="expertsLocauxSwiper-item__name">June Tran <?= $i ?></span>
                                    <span class="expertsLocauxSwiper-item__position">Manager <?= $i ?></span>
                                </div>
                            </div>
                        </div>
                    <?php endfor; ?>
                </div>
                <div class="expertsLocauxSwiper-pagination"></div>
            </div>
            <div class="expertsLocauxSwiper-next">
                <?= wp_get_attachment_image($icon_arrow, 'full') ?>
            </div>
            <div class="expertsLocauxSwiper-prev">
                <?= wp_get_attachment_image($icon_arrow, 'full') ?>
            </div>
        </div>
    </div>
</section>