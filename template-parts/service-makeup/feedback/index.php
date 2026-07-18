<?php
      $feedback_data = get_field('feedback_data');
      $title = $feedback_data['title'] ?? '';
      $subtitle = $feedback_data['subtitle'] ?? '';
      $gallery = $feedback_data['gallery'] ?? [];
      $socials = $feedback_data['socials'] ?? [];
      $image_ids = [];
      if (!empty($gallery)) {
        foreach ($gallery as $image) {
                $image_ids[] = $image['ID'];
        }
      }
?>

<section class="gallery-section">
    <div class="gallery-header">
        <div class="gallery-titles">
            <span class="gallery-sub"><?= $title ?></span>
            <h2 class="gallery-main"><?= $subtitle ?></h2>
        </div>

        <?php if (!empty($socials)) : ?>
        <div class="gallery-socials">
            <?php foreach ($socials as $social) :
                $social_icon   = $social['icon'] ?? null;
                $social_link   = $social['link'] ?? [];
                $social_url    = $social_link['url'] ?? '';
                $social_target = !empty($social_link['target']) ? $social_link['target'] : '_self';
                if (empty($social_url) || empty($social_icon)) {
                    continue;
                }
            ?>
            <a href="<?= esc_url($social_url) ?>" target="<?= esc_attr($social_target) ?>" rel="noopener noreferrer" class="social-icon">
                <?= wp_get_attachment_image($social_icon, 'full', false, array('loading' => 'lazy', 'decoding' => 'async', 'class' => 'social-icon__img')) ?>
            </a>
            <?php endforeach; ?>
        </div>
        <?php endif; ?>
    </div>

    <?php get_template_part('template-parts/components/marquee/index', null, [
        'image_ids' => $image_ids,
    ]);?>

</section>
