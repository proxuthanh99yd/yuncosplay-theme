<?php
$process = get_field('process');
$subtitle = $process['subtitle'] ?? '';
$title = $process['title'] ?? '';
$process_list = $process['process_list'] ?? [];
$link = $process['link'] ?? [];
?>

<section id="about-us-process" class="section-process">

    <div class="section-process__container">

        <!-- header -->
        <div class="section-process__header">
            <div class="section-process__heading">
                <p class="section-process__subtitle"><?= esc_html($subtitle) ?></p>
                <h2 class="section-process__title"><?= esc_html($title) ?></h2>
            </div>

            <?php if (!empty($link['url'])): ?>
                <a
                    href="<?= esc_url($link['url']) ?>"
                    target="<?= esc_attr($link['target'] ?? '_self') ?>"
                    class="section-process__action">
                    <?php get_template_part(
                        'template-parts/components/animated-button/index',
                        null,
                        ['text' => $link['title'] ?? 'Liên hệ ngay']
                    ); ?>
                </a>
            <?php endif; ?>
        </div>

        <!-- list -->
        <?php if (!empty($process_list)): ?>
            <div class="section-process__list">

                <?php foreach ($process_list as $index => $item):
                    $icon = $item['icon'] ?? '';
                    $item_title = $item['title'] ?? '';
                    $description = $item['description'] ?? '';
                ?>
                    <div class="section-process__item">
                        <div>
                            <?php if ($icon): ?>
                                <?= wp_get_attachment_image($icon, 'full', false, ['class' => 'section-process__icon']) ?>
                            <?php endif; ?>

                            <p class="section-process__step">
                                BƯỚC <?= $index + 1 ?>
                            </p>

                            <?php if ($item_title): ?>
                                <h3 class="section-process__item-title">
                                    <?= esc_html($item_title) ?>
                                </h3>
                            <?php endif; ?>
                        </div>

                        <?php if ($description): ?>
                            <p class="section-process__item-description">
                                <?= esc_html($description) ?>
                            </p>
                        <?php endif; ?>
                    </div>
                <?php endforeach; ?>

            </div>
        <?php endif; ?>

        <?php if (!empty($link['url'])): ?>
            <div class="section-process__action-mobile">
                <a
                    href="<?= esc_url($link['url']) ?>"
                    target="<?= esc_attr($link['target'] ?? '_self') ?>">
                    <?php get_template_part(
                        'template-parts/components/animated-button/index',
                        null,
                        ['text' => $link['title'] ?? 'Liên hệ ngay']
                    ); ?>
                </a>
            </div>
        <?php endif; ?>
    </div>

</section>