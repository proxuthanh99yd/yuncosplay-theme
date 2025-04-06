<?php
$ids_col1 = [167, 168, 169, 170, 171, 172, 173];
$ids_col2 = [173, 168, 171, 170, 169, 172, 167];
$ids_col3 = [168, 169, 170, 171, 172, 173, 167];
$ids_col4 = [173, 172, 171, 170, 169, 168, 167];

?>
<section class="enu-agence-section">
    <div class="container">
        <span class="enu-agence-section_subtitle">À PROPOS DE NOUS</span>
        <div class="enu-agence-section_title">
            Une agence de voyage humanitaire <strong>donner c’est recevoir</strong>
        </div>
        <div class="enu-agence-section_gallery">
            <div class="enu-agence-section_galler-col col1">
                <?php foreach ($ids_col1 as $id) { ?>
                    <?= wp_get_attachment_image($id, 'full', false, [
                        'class' => 'enu-agence-section_galler-col_img',
                    ]) ?>
                <?php } ?>
            </div>
            <div class="enu-agence-section_galler-col col2">
                <?php foreach ($ids_col2 as $id) { ?>
                    <?= wp_get_attachment_image($id, 'full', false, [
                        'class' => 'enu-agence-section_galler-col_img',
                    ]) ?>
                <?php } ?>
            </div>
            <div class="enu-agence-section_galler-col col3">
                <?php foreach ($ids_col3 as $id) { ?>
                    <?= wp_get_attachment_image($id, 'full', false, [
                        'class' => 'enu-agence-section_galler-col_img',
                    ]) ?>
                <?php } ?>
            </div>
            <div class="enu-agence-section_galler-col col4">
                <?php foreach ($ids_col4 as $id) { ?>
                    <?= wp_get_attachment_image($id, 'full', false, [
                        'class' => 'enu-agence-section_galler-col_img',
                    ]) ?>
                <?php } ?>
            </div>
        </div>
    </div>
</section>