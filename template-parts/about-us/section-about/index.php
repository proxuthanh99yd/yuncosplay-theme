<?php
// Ảnh trang trí + icon → file tĩnh theme (okhub_img). Ảnh nội dung vẫn từ ACF.

$about = get_field('about_us');
$title = $about['title'];
$description = $about['description'];
$stats = $about['stats'];
$image = $about['image'];
$features = $about['features'];
?>

<section class="section-about">
    <div class="section-about__container">

        <!-- LEFT -->
        <div class="section-about__content">

            <!-- decor -->
            <?= okhub_img('about-us/bg-image', array('class' => 'section-about__decor-image')); ?>

            <?= okhub_img('icons/vector', array('class' => 'section-about__decor-icon')); ?>

            <!-- heading -->
            <div class="section-about__heading">
                <h3 class="section-about__title">
                    <?= esc_html($title); ?>
                </h3>
                <p class="section-about__description">
                    <?= esc_html($description); ?>
                </p>
            </div>

            <!-- stats -->
            <?php if (!empty($stats)) : ?>
                <div class="section-about__stats">
                    <?php foreach ($stats as $item) :
                        $number = $item['value'] ?? '';
                        $label = $item['label'] ?? '';
                        $counter_target = 0;
                        $counter_suffix = '';

                        if (preg_match('/^\s*([\d.,]+)\s*(.*)$/u', (string) $number, $matches)) {
                            $counter_target = (int) preg_replace('/[^\d]/', '', $matches[1]);
                            $counter_suffix = trim((string) ($matches[2] ?? ''));
                        }
                    ?>
                        <div class="section-about__stat">
                            <span class="section-about__stat-number"
                                data-counter-target="<?= esc_attr((string) $counter_target); ?>"
                                data-counter-suffix="<?= esc_attr($counter_suffix); ?>">
                                <?= esc_html($number); ?>
                            </span>
                            <span class="section-about__stat-label">
                                <?= esc_html($label); ?>
                            </span>
                        </div>
                    <?php endforeach; ?>
                </div>
            <?php endif; ?>

        </div>

        <!-- RIGHT -->
        <div class="section-about__media">

            <!-- image -->
            <div class="section-about__image-wrap">
                <div class="section-about__overlay"></div>
                <?= wp_get_attachment_image($image, 'full', false, [
                    'class' => 'section-about__image'
                ]); ?>
            </div>

            <!-- features -->
            <?php if (!empty($features)) : ?>
                <div class="section-about__features">

                    <?php foreach ($features as $item) :
                        $text = $item['text'] ?? '';
                    ?>
                        <div class="section-about__feature">

                            <?= okhub_img('icons/icon', array('class' => 'section-about__feature-icon')); ?>

                            <p class="section-about__feature-text">
                                <?= esc_html($text); ?>
                            </p>

                        </div>
                    <?php endforeach; ?>

                </div>
            <?php endif; ?>

        </div>

    </div>
</section>