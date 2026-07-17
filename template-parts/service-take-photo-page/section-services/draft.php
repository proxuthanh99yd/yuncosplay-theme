<?php

$deco = 10238
?>

<section class="section-services">
    <div class="main-container">
        <div class="main-container__left">
            <h3>dịch của chụp ảnh nghệ thuật</h3>
            <div class="combo">
                <div class="combo__item">Gói cá nhân</div>
                <div class="combo__item">Gói cá nhân</div>
            </div>
        </div>
        <div class="main-container__right">
            <a href="<?= esc_url($service_link) ?>" class="services__button">
                <?php get_template_part('template-parts/components/animated-button/index', null, array('text' => 'Chi tiết dịch vụ')); ?>
            </a>
            <div class="right-image">
                <div class="right-image__overlay"></div>
                <?php echo wp_get_attachment_image(10237, 'full', false, array(
                    'loading'  => 'lazy',
                    'decoding' => 'async',
                    'class'    => 'right-image__img',
                )); ?>
                <?php echo wp_get_attachment_image($deco, 'full', false, array(
                    'loading'  => 'lazy',
                    'decoding' => 'async',
                    'class'    => 'right-image__deco',
                )); ?>
            </div>
        </div>

    </div>

</section>
<div style="height: 1000px;"></div>