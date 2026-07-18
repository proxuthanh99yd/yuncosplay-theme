<?php
      $workflow_data = get_field('workflow_data');
      $title = $workflow_data['title'] ?? '';
      $subtitle = $workflow_data['subtitle'] ?? '';
      $workflow = $workflow_data['workflow'];
?>

<section class="process-section">
    <div class="process-overlay"></div>

    <div class="process-global-lines">
        <span></span><span></span><span></span><span></span><span></span>
    </div>

    <div class="process-header">
        <div class="header-titles">
            <span class="sub-title"><?= $title ?></span>
            <h2 class="main-title"><?= $subtitle ?></h2>
        </div>

        <a href="<?= okhub_page_url('lien-he') ?>" class="btn-link-desktop">
            <?php get_template_part('template-parts/components/animated-button/index', null, ['text' => 'Đặt lịch makeup ngay' ?? '']); ?>
        </a>
    </div>

    <?php if (isset($workflow)) : ?>
    <div class="process-steps-container">
        <div class="process-steps-track">
            <?php foreach ($workflow as $index => $item) : ?>
            <div class="step-card">
                <?= wp_get_attachment_image($item['icon']['ID'], 'full', false, array('loading' => 'lazy', 'decoding' => 'async', 'class' => '')) ?>
                <div class="step-content">
                    <span class="step-label">BƯỚC <?= $index + 1 ?></span>
                    <h3 class="step-title"><?= $item['subtitle'] ?? '' ?></h3>
                    <p class="step-desc"><?= $item['description'] ?? '' ?></p>
                </div>
            </div>
            <?php endforeach; ?>
        </div>
    </div>
    <?php endif; ?>

    <div class="btn-link-mobile">
        <a href="<?= okhub_page_url('lien-he') ?>" class="">
            <?php get_template_part('template-parts/components/animated-button/index', null, ['text' => 'Đặt lịch makeup ngay' ?? '']); ?>
        </a>
    </div>
</section>