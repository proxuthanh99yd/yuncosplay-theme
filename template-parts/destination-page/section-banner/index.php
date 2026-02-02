<?php
$term = get_queried_object();
$banner = get_field('destination_banner', $term);
 
 
$title = isset($banner['title']) ? trim($banner['title']) : '';
$desc = isset($banner['desc']) ? trim($banner['desc']) : '';
$video = isset($banner['video']) ? $banner['video'] : '';
 
$logo_icon_id = 1158;
?>
  
  <section id="banner" class="destination-banner">
      <video
        src='<?= esc_url($video); ?>'
        alt="Video"
        autoplay
        muted
        loop
        class="destination-banner_video"
      ></video>
      <div class="destination-banner_overlay"></div>
      <div class="destination-banner_breadcrumbs breadcrumbs">
        <a href="/" class="breadcrumbs-item">Home</a>
        <span class="breadcrumbs-seperator">/</span>
        <span class="breadcrumbs-item active">Destination</span>
      </div>
      <div class="destination-banner_content">
        <div class="destination-banner_bar"></div>
        <div class="destination-banner_text">
          <h1 class="destination-banner_title"><?= $title ?></h1>
          <p class="destination-banner_subtitle"><?= $desc ?></p>
        </div>
        <button type="button" class="destination-banner_scrolldown">
          <?= wp_get_attachment_image($logo_icon_id, 'full', false, array( 'class' => 'destination-banner_scrolldown_icon')) ?>
          <span class="destination-banner_scrolldown_text">Discover</span>
        </button>
      </div>
    </section>