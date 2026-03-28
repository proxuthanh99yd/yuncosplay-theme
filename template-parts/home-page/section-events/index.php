<?php
$bg_item_id = 159;
$overlay_item_id = 160;
$bg_item_content_id = 161;
$mirror_item_content_id = 162;
$icon_left_id = 166;

$section_sponsored_program = get_field('sponsored_program');
$section_sponsored_program_title = $section_sponsored_program['title'];
$section_sponsored_program_subtitle = $section_sponsored_program['subtitle'];
$section_sponsored_program_items = $section_sponsored_program['sponsored_program_items'] ?? [];
?>

<section class="home__events">
  <div class="home__events-header">
    <h2 class="home__events-title">
      <?= $section_sponsored_program_title ?>
    </h2>
    <p class="home__events-description">
      <?= $section_sponsored_program_subtitle ?>
    </p>
  </div>
  <div class="home_events-swiper-container">
    <?php if (!empty($section_sponsored_program_items)) : ?>
    <div class="home_events-swiper swiper">
      <div class="swiper-wrapper">
        <?php foreach ($section_sponsored_program_items as $sponsored_program_item) : ?>
        <?php 
        $sponsored_program_item_title = $sponsored_program_item['title'];
        $sponsored_program_item_thumbnail = $sponsored_program_item['thumbnail'];
        $sponsored_program_item_link = $sponsored_program_item['link'];
        $sponsored_program_item_description = $sponsored_program_item['description'];
        if(!empty($sponsored_program_item_link) && !empty($sponsored_program_item_link['url'])):
          $sponsored_program_item_link_url = $sponsored_program_item_link['url'] ? $sponsored_program_item_link['url'] : '#';
          $sponsored_program_item_link_target = $sponsored_program_item_link['target'] ? $sponsored_program_item_link['target'] : '_self';
        ?>
        <div class="swiper-slide">
          <div class="home_events-swiper-item">
            <?= wp_get_attachment_image($bg_item_id, 'full', false, array('loading' => 'lazy', 'decoding' => 'async', 'class' => 'home_events-swiper-item-image')) ?>
            <?= wp_get_attachment_image($overlay_item_id, 'full', false, array('loading' => 'lazy', 'decoding' => 'async', 'class' => 'home_events-swiper-item-overlay')) ?>

            <div class="home_events-swiper-item-wrapper">
              <h3 class="home_events-swiper-item-title">
                <?= $sponsored_program_item_title; ?>
              </h3>

              <a href="<?= $sponsored_program_item_link_url; ?>" target="<?= $sponsored_program_item_link_target; ?>" class="home_events-swiper-image">
                <?= wp_get_attachment_image($bg_item_content_id, 'full', false, array('loading' => 'lazy', 'decoding' => 'async', 'class' => 'home_events-swiper-image-image')) ?>
                <?= wp_get_attachment_image($mirror_item_content_id, 'full', false, array('loading' => 'lazy', 'decoding' => 'async', 'class' => 'home_events-swiper-image-mirror')) ?>
                <div class="home_events-swiper-image-overlay"></div>
                <?php if (!empty($sponsored_program_item_thumbnail)) : ?>
                <?= wp_get_attachment_image($sponsored_program_item_thumbnail, 'full', false, array('loading' => 'lazy', 'decoding' => 'async', 'class' => 'home_events-swiper-image-main')) ?>
                <?php endif; ?>
                <div class="home_events-swiper-image-hover"></div>
                <button type="button" class="home_events-swiper-image-link">
                  Xem thêm
                </button>
              </a>
            </div>

            <p class="home_events-swiper-item-description">
              <?= $sponsored_program_item_description ?>
            </p>
          </div>
        </div>
        <?php endif; ?>
        <?php endforeach; ?>
      </div>
    </div>
    <?php endif; ?>
  </div>
  <div class="home_events-swiper-controls">
    <button class="home_events-swiper-button home_events-swiper-button--prev" type="button" aria-label="Previous slide">
      <?= wp_get_attachment_image($icon_left_id, 'full', false, array('loading' => 'lazy', 'decoding' => 'async', 'class' => 'home_events-swiper-button-left')) ?>
    </button>
    <button class="home_events-swiper-button home_events-swiper-button--next" type="button" aria-label="Next slide">
      <?= wp_get_attachment_image($icon_left_id, 'full', false, array('loading' => 'lazy', 'decoding' => 'async', 'class' => 'home_events-swiper-button-right')) ?>
    </button>
  </div>
</section>