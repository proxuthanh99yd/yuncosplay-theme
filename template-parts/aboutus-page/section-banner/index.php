<?php
$banner = get_field("about_banner");
// $title = isset($banner['title']) ? $banner['title'] : '';
$desc = isset($banner['desc']) ? $banner['desc'] : '';

$image_banner_id = 2468;
$image_banner_id_mb = 2274;
$image_title_id = 2476;
$image_subtitle_id = 2264;
?>


<section id="banner" class="banner">
  <picture>
    <?= '<source media="(max-width: 639px)" srcset="' . esc_url(wp_get_attachment_image_url($image_banner_id_mb, 'full')) . '" />' ?>
    <?= wp_get_attachment_image($image_banner_id, 'full', false, array('class' => 'aboutus_banner__image')) ?>
  </picture>
  <div class="banner__content container">
    <h1 class="banner__content__title sr-only">
      <!--<?=  esc_html($title); ?>-->
      About Us
    </h1>
    <?= wp_get_attachment_image($image_title_id, 'full', false, array('class' => 'banner__content__title__image', 'data-aos' => 'fade-up', 'data-aos-duration' => '1000', 'data-aos-offset' => '0')) ?>
    <div class="banner__content__box">
      <?= wp_get_attachment_image($image_subtitle_id, 'full', false, array('class' => 'banner__content__subtitle','data-aos' => 'fade-up','data-aos-duration' => '1000','data-aos-offset' => '0')) ?>
      <h2 class="banner__content__description" data-aos="fade-up" data-aos-duration="1000" data-aos-offset="0">
        <?= esc_html($desc); ?>
      </h2>
    </div>
  </div>
</section>