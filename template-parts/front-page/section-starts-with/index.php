<?php
$_OVERLAY_1 = 82;
$_OVERLAY_2 = 83;
$_ITEM_BG = 103;
?>
<section class="starts-with">
    <?= !wp_is_mobile() ? wp_get_attachment_image($_OVERLAY_1, 'full', false, [
        'class' => 'starts-with__overlay starts-with__overlay--1'
    ]) : "" ?>
    <?= !wp_is_mobile() ? wp_get_attachment_image($_OVERLAY_2, 'full', false, [
        'class' => 'starts-with__overlay starts-with__overlay--2'
    ]) : "" ?>
    <div class="starts-with__container">
        <div class="starts-with__header">
            <div class="heading starts-with__title">
                <h2>Votre <strong>aventure</strong> vietnamienne commence ici <br> avec Horizon Vietnam Travel !</h2>
            </div>
            <p class="pc-18b starts-with__tag">
                10 bonnes raisons de nous choisir
            </p>
        </div>
        <div class="starts-with__body">
            <?php
            for ($i = 0; $i < 10; $i++) :
            ?>
                <div class="starts-with__item">
                    <div class="starts-with__item-icon">
                        <?= wp_get_attachment_image($i + 72, 'full') ?>
                    </div>
                    <h3 class="starts-with__item-title">Meilleur prix, -10% des concurrents</h3>
                    <?php if (!wp_is_mobile() && $i !== 4 && $i !== 9) : ?>
                        <span class="starts-with__item-line"></span>
                    <?php endif; ?>
                    <?= wp_is_mobile() ? wp_get_attachment_image($_ITEM_BG, 'full', false, [
                        'class' => 'starts-with__item-bg'
                    ]) : "" ?>
                </div>
            <?php endfor; ?>
        </div>
        <div class="starts-with__footer">
            <a href="#" class="pc-button-16-b starts-with__link">Planifiez le voyage</a>
        </div>
    </div>
</section>