<?php
$section_banner = get_field('banner');
$title = $section_banner['title'];
$image_pc = $section_banner['desktop'];
$image_mb = $section_banner['mobile'];
$where_is_this = $section_banner['where_is_this'];

$icon_discover_id = 1122;
$icon_location_id = 1707;
?>

<section id="banner" class="banner">
  <?= wp_get_attachment_image($image_pc, 'full', false, array( 'class' => 'banner__image banner__image--pc')) ?>
  <?= wp_get_attachment_image($image_mb, 'full', false, array( 'class' => 'banner__image banner__image--mb')) ?>
  <div class="banner__overlay-1"></div>
  <div class="banner__overlay-2"></div>
  <div class="banner__content">
    <h1 class="banner__title"><?= $title ?></h1>
  </div>
  <div class="banner__discover">
    <?= wp_get_attachment_image($icon_discover_id, 'full', false, array( 'class' => 'banner__discover-icon')) ?>
    <span class="banner__discover-text">Discover</span>
  </div>
  <div class="banner__where-is-this">
    <?= wp_get_attachment_image($icon_location_id, 'full', false, array( 'class' => 'banner__where-is-this-icon')) ?>
    <div class="banner_line"></div>
    <div class="">
      <p class="banner__where-is-this-text">Where is this ?</p>
      <p class="banner__where-is-this-title"><?= $where_is_this['title'] ?></p>
      <div class="banner__where-is-this-link-container">
        <a href="<?= $where_is_this['link']['url'] ?>" class="banner__where-is-this-link">See more of this location</a>
        <svg class="banner__where-is-this-arrow" xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 14 14" fill="none">
          <path d="M4.66602 11.0835L9.04116 6.6045L4.66602 2.33349" stroke="white" stroke-opacity="0.8"/>
        </svg>
      </div>
    </div>
  </div>
</section>