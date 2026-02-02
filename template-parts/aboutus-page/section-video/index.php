<?php
$media = get_field("about_media");
$thumbnail = isset($media['thumbnail']) ? $media['thumbnail'] : [];
$video = isset($media['video']) ? $media['video'] : "";

$image_video_id = 1339;
$image_video_id_mb = 1337;
?>

<section class="about__media">
  <video class="about__media__video" loop muted playsinline controls -webkit-playsinline x5-playsinline
    data-src="<?= esc_attr($video); ?>"></video>

  <picture class="about__media__picture">
    <?= '<source media="(max-width: 639px)" srcset="' . esc_url(wp_get_attachment_image_url($thumbnail['mobile'], 'full')) . '" />' ?>
    <?= wp_get_attachment_image($thumbnail['desktop'], 'full', false, array('class' => 'about__media__image')) ?>
  </picture>

  <div class="about__media__container">
    <button class="about__media__button">
      <svg xmlns="http://www.w3.org/2000/svg" width="21" height="24" viewBox="0 0 21 24" fill="none">
        <path d="M20.5479 11.8634L0 23.7267L1.03712e-06 0L20.5479 11.8634Z" fill="white" />
      </svg>
    </button>
  </div>
</section>