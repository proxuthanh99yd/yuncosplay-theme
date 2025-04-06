<?php
$bg_img = 165;
$cloud_img = 166;
$icon_arrow = 153;

?>
<section class="csr-activities__horizon-vietnam">
    <?= wp_get_attachment_image($bg_img, 'full', false, [
        'class' => 'csr-activities__horizon-vietnam_bg',
    ]) ?>
    <?= wp_get_attachment_image($cloud_img, 'full', false, [
        'class' => 'csr-activities__horizon-vietnam_cloud',
    ]) ?>
    <div class="container">
        <span class="csr-activities__horizon-vietnam_subtitle">À PROPOS DE NOUS</span>
        <h2 class="csr-activities__horizon-vietnam_title"><strong>Horizon Vietnam</strong> - Une agence qui <br />
            propose un voyage responsable</h2>
        <?php if (!wp_is_mobile()) { ?>
            <div class="csr-activities__horizon-vietnam_content">
                <?php for ($i = 0; $i < 6; $i++) { ?>
                    <div class="csr-activities__horizon-vietnam_content_item">
                        <span>0<?php echo $i + 1; ?></span>
                        <div class="csr-activities__horizon-vietnam_content_text">
                            Chaque année, <strong>Horizon Vietnam</strong> Travel crée des milliers d’emplois pour les habitants qui sont paysans au Vietnam et d’autres pays en Asie du Sud-est.
                        </div>
                    </div>
                <?php } ?>
            </div>
        <?php } else { ?>
            <div class="csr-activities__horizon-vietnam_content">
                <div class="swiper mySwiperCsr-activities_horizon-vietnam">
                    <div class="swiper-wrapper">
                        <?php for ($i = 0; $i < 6; $i++) { ?>
                            <div class="swiper-slide">
                                <div class="csr-activities__horizon-vietnam_content_item">
                                    <span>0<?php echo $i + 1; ?></span>
                                    <div class="csr-activities__horizon-vietnam_content_text">
                                        Chaque année, <strong>Horizon Vietnam</strong> Travel crée des milliers d’emplois pour les habitants qui sont paysans au Vietnam et d’autres pays en Asie du Sud-est.
                                    </div>
                                </div>
                            </div>
                        <?php } ?>
                    </div>
                    <!-- Pagination + Navigation -->
                    <div class="swiperHorizonVnCSR-next">
                        <?= wp_get_attachment_image($icon_arrow, 'full') ?>
                    </div>
                   
                </div>
            </div>
        <?php } ?>

    </div>
</section>