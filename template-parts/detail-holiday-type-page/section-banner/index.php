<?php
$term = get_queried_object();
$banner = get_field("holiday_banner", $term);

$title = isset($banner["title"]) ? $banner["title"] : "";
$image = $banner["image"];
$img_pc = isset($image["desktop"]) ? $image["desktop"] : "";
$img_mb = isset($image["mobile"]) ? $image["mobile"] : "";
$location = $banner['location'];
$location_id = $location->ID;
$location_title = get_the_title($location_id);
$location_link = get_permalink($location_id);
 
$scrolldown_icon_id = 1158;

$map_pin_id = 2086;
$chevron_right_id = 2085;
?>


<section id="banner" class="ht-banner">
  <?= wp_get_attachment_image($img_pc, "full", false, [
    "class" => "ht-banner_img",
  ]) ?>
  <?= wp_get_attachment_image($img_mb, "full", false, [
    "class" => "ht-banner_img-mb",
  ]) ?>

  <div class="ht-banner_breadcrumbs breadcrumbs">
    <a href="/" class="breadcrumbs-item">Home</a>
    <span class="breadcrumbs-seperator">/</span>
    <a href="/holiday-type" class="breadcrumbs-item">Holidays Types</a>
    <span class="breadcrumbs-seperator">/</span>
    <span class="breadcrumbs-item active"><?= esc_html($title) ?></span>
  </div>
  <div class="ht-banner_container">
    <!-- prettier-ignore -->
    <h1 class="ht-banner_title"><?= esc_html($title); ?></h1>
  </div>
  <div class="ht-banner_location">
    <div class="ht-banner_location-pin-wrapper">
      <?= wp_get_attachment_image($map_pin_id, "full", false, [
      "class" => "ht-banner_location-pin",
    ]) ?>
    </div>
    <div class="ht-banner_location-line"></div>
    <div class="ht-banner_location-content">
      <!-- prettier-ignore -->
      <p class="text-normal">Where is this ?</p>
      <p class="text-hover"><?= esc_html($location_title) ?></p>
      <a class="ht-banner_location-link" href="<?= esc_attr($location_link) ?>">
        <span>See more of this location</span>
        <?= wp_get_attachment_image($chevron_right_id, "full", false, [
          "class" => "ht-banner_location-arrow",
        ]) ?>
      </a>
    </div>
    
  </div>
  
  <button type="button" class="ht-banner_scrolldown">
      <?= wp_get_attachment_image($scrolldown_icon_id, 'full', false, array( 'class' => 'ht-banner_scrolldown-icon')) ?>
      <span class="ht-banner_scrolldown-text">Discover</span>
  </button>
</section>