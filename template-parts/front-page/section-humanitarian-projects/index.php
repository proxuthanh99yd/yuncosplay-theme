<?php
$_MASK_1  = 84;
$_MASK_2  = 85;
$_MASK_3  = wp_is_mobile() ? 104 : 86;
$_DECO_1  = 95;
$_DECO_2  = 102;
$_DECO_3  = 101;
$_DECO_4  = 100;
$_DECO_5  = 99;
$_DECO_6  = 98;
$_DECO_7  = 97;
$_DECO_8  = 96;

?>

<section class="humanitarian-projects">
    <?= wp_get_attachment_image($_MASK_3, 'full', false, [
        'class' => 'humanitarian-projects__mask humanitarian-projects__mask--3',
    ]) ?>
    <div class="humanitarian-projects__mask humanitarian-projects__mask--ellipse"></div>

    <!-- images -->
    <div class="humanitarian-projects__image humanitarian-projects__image--1">
        <?= wp_get_attachment_image(87, 'full', false, [
            'class' => 'humanitarian-projects__image-main',
        ]) ?>
        <?= wp_get_attachment_image($_DECO_1, 'full', false, [
            'class' => 'humanitarian-projects__image-deco humanitarian-projects__image-deco--1',
        ]) ?>
    </div>
    <div class="humanitarian-projects__image humanitarian-projects__image--2">
        <?= wp_get_attachment_image(88, 'full', false, [
            'class' => 'humanitarian-projects__image-main',
        ]) ?>
        <?= wp_get_attachment_image($_DECO_2, 'full', false, [
            'class' => 'humanitarian-projects__image-deco humanitarian-projects__image-deco--2',
        ]) ?>
    </div>
    <div class="humanitarian-projects__image humanitarian-projects__image--3">
        <?= wp_get_attachment_image(89, 'full', false, [
            'class' => 'humanitarian-projects__image-main',
        ]) ?>
        <?= wp_get_attachment_image($_DECO_3, 'full', false, [
            'class' => 'humanitarian-projects__image-deco humanitarian-projects__image-deco--3',
        ]) ?>
    </div>
    <div class="humanitarian-projects__image humanitarian-projects__image--4">
        <?= wp_get_attachment_image(90, 'full', false, [
            'class' => 'humanitarian-projects__image-main',
        ]) ?>
        <?= wp_get_attachment_image($_DECO_4, 'full', false, [
            'class' => 'humanitarian-projects__image-deco humanitarian-projects__image-deco--4',
        ]) ?>
    </div>
    <div class="humanitarian-projects__image humanitarian-projects__image--5">
        <?= wp_get_attachment_image(91, 'full', false, [
            'class' => 'humanitarian-projects__image-main',
        ]) ?>
        <?= wp_get_attachment_image($_DECO_5, 'full', false, [
            'class' => 'humanitarian-projects__image-deco humanitarian-projects__image-deco--5',
        ]) ?>
    </div>
    <div class="humanitarian-projects__image humanitarian-projects__image--6">
        <?= wp_get_attachment_image(92, 'full', false, [
            'class' => 'humanitarian-projects__image-main',
        ]) ?>
        <?= wp_get_attachment_image($_DECO_6, 'full', false, [
            'class' => 'humanitarian-projects__image-deco humanitarian-projects__image-deco--6',
        ]) ?>
    </div>
    <div class="humanitarian-projects__image humanitarian-projects__image--7">
        <?= wp_get_attachment_image(93, 'full', false, [
            'class' => 'humanitarian-projects__image-main',
        ]) ?>
        <?= wp_get_attachment_image($_DECO_7, 'full', false, [
            'class' => 'humanitarian-projects__image-deco humanitarian-projects__image-deco--7',
        ]) ?>
    </div>
    <div class="humanitarian-projects__image humanitarian-projects__image--8">
        <?= wp_get_attachment_image(94, 'full', false, [
            'class' => 'humanitarian-projects__image-main',
        ]) ?>
        <?= wp_get_attachment_image($_DECO_8, 'full', false, [
            'class' => 'humanitarian-projects__image-deco humanitarian-projects__image-deco--8',
        ]) ?>
    </div>
    <!-- images -->

    <div class="humanitarian-projects__container">
        <h2 class="heading humanitarian-projects__title">200+ projets humanitaires <br> effectués par an</h2>
        <p class="humanitarian-projects__description">Nous sommes une agence de voyage responsable. Une grande partie de
            notre bénéfice de l’agence est consacrée
            aux activités humanitaires. Le bouddhisme enseigne que la loi de cause et d’effet sous-tend le
            fonctionnement de toute chose. En effet, on récolte ce qu’on sème.</p>
        <a class="pc-button-16-b humanitarian-projects__link" href="">Explorer davantage</a>
    </div>

    <?= wp_get_attachment_image($_MASK_1, 'full', false, [
        'class' => 'humanitarian-projects__mask humanitarian-projects__mask--1',
    ]) ?>
    <div class="humanitarian-projects__mask humanitarian-projects__mask--ellipse-2"></div>
    <?= wp_get_attachment_image($_MASK_2, 'full', false, [
        'class' => 'humanitarian-projects__mask humanitarian-projects__mask--2',
    ]) ?>
</section>