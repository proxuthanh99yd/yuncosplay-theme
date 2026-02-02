<?php
$our_way = get_field("about_our-way");
$title = isset($our_way['title']) ? $our_way['title'] : '';
$desc_1 = isset($our_way['desc_1']) ? $our_way['desc_1'] : '';
$desc_2 = isset($our_way['desc_2']) ? $our_way['desc_2'] : '';
$desc_3 = isset($our_way['desc_3']) ? $our_way['desc_3'] : '';

$image_our_way = 2293;
$image_our_way_mb = 2291;
?>

<section class="our__way">
  <picture>
    <?= '<source media="(max-width: 1025px)" srcset="' . esc_url(wp_get_attachment_image_url($image_our_way_mb, 'full')) . '" />' ?>
    <?= wp_get_attachment_image($image_our_way, 'full', false, array('class' => 'our__way__image')) ?>
  </picture>
  <div class="our__way__content">
    <div class="our__way__content__container" data-aos="fade-up" data-aos-duration="1000">
        <h2 class="our__way__content__title"><?= esc_html($title); ?></h2>
        <div class="our__way__content__description__container">
          <p class="our__way__content__description"><?= esc_html($desc_1); ?></p>
          <p class="our__way__content__description"><?= esc_html($desc_2); ?></p>
          <p class="our__way__content__description"><?= esc_html($desc_3); ?></p>
        </div>
    </div>
   
  </div>
</section>