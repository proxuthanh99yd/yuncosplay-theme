<?php
$id_thumbnail = 266;
$id_icon = 269;
?>
<section class="component-banner">
    <?= wp_get_attachment_image($id_thumbnail, 'full', false, [
        'class' => 'component-banner__thumbnail',
    ]) ?>
    <div class="component-banner__content">
        <span class="component-banner__subtitle">À propos de nous</span>
        <h1 class="component-banner__title">Horizon: <?= get_the_title(); ?></h1>
        <?= wp_get_attachment_image($id_icon, 'full', false, [
            'class' => 'component-banner__down-icon',
        ]) ?>
    </div>

</section>