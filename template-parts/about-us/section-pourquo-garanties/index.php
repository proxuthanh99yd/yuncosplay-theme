<?php
$line = 289;
?>
<section class="section-pourquo-garanties">
    <div class="container-section">
        <?php get_template_part('template-parts/components/pourquoi_nous_choisir/index'); ?>
        <?= wp_get_attachment_image($line, 'full', false, [
            'class' => 'section-pourquo-garanties__line',
        ]) ?>
        <?php get_template_part('template-parts/components/nos_four_garanties/index'); ?>
    </div>
</section>