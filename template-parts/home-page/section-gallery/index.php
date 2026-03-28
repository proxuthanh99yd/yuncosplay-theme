<?php
$icon_youtube_id = 152;
$icon_instagram_id = 154;
$icon_facebook_id = 153;
$icon_tiktok_id = 155;

$slider_item_1_id = 158;
$slider_item_2_id = 157;
$slider_item_3_id = 72;
$slider_item_4_id = 156;

$section_customer_gallery = get_field('customer_gallery');
$section_customer_gallery_title = $section_customer_gallery['title'];
$section_customer_gallery_subtitle = $section_customer_gallery['subtitle'];
$section_customer_gallery_items= $section_customer_gallery['gallery_items'] ?? [];
if (!empty($section_customer_gallery_items)) {
  $section_customer_gallery_items = array_merge($section_customer_gallery_items, $section_customer_gallery_items);
}
$section_customer_gallery_social_items = $section_customer_gallery['social_items'] ?? [];
?>

<section class="gallery">
  <div class="gallery__header">
    <div class="gallery__header-title">
      <h2 class="gallery__header-title-text">
        <?= $section_customer_gallery_title ?>
      </h2>
      <p class="gallery__header-title-description">
        <?= $section_customer_gallery_subtitle ?>
      </p>
    </div>
    <?php if (!empty($section_customer_gallery_social_items)) : ?>
    <div class="gallery__header-socials">
      <?php foreach ($section_customer_gallery_social_items as $social_item) : ?>
      <?php 
      $social_icon = $social_item['icon'];
      $social_link = $social_item['link'];
      if(!empty($social_link) && !empty($social_link['url'])):
        $social_link_url = $social_link['url'];
        $social_link_target = $social_link['target'] ? $social_link['target'] : '_self';
      ?>
      <a href="<?= $social_link_url ?>" target="<?= $social_link_target ?>" class="gallery__header-social">
        <?= wp_get_attachment_image($social_icon, 'full', false, array('loading' => 'lazy', 'decoding' => 'async', 'class' => 'gallery__header-social-image')) ?>
      </a>
      <?php endif; ?>
      <?php endforeach; ?>
    </div>
    <?php endif; ?>
  </div>
  <?php if (!empty($section_customer_gallery_items)) : ?>
  <div class="gallery__slider">
    <div class="gallery__slider-track">
      <?php foreach ($section_customer_gallery_items as $gallery_item) : ?>
      <div class="gallery__slider-item">
        <?= wp_get_attachment_image($gallery_item, 'full', false, array('loading' => 'lazy', 'decoding' => 'async', 'class' => 'gallery__slider-item-image')) ?>
      </div>
      <?php endforeach; ?>
    </div>
  </div>
  <?php endif; ?>
</section>