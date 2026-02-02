<?php
$mission = get_field("about_mission");
$title = isset($mission['title']) ? $mission['title'] : '';
$desc = isset($mission['desc']) ? $mission['desc'] : '';
$item_1 = isset($mission['item_1']) ? $mission['item_1'] : [];
$item_2 = isset($mission['item_2']) ? $mission['item_2'] : [];

$image_mission_id = 2292;
$image_mission_id_mb = 2290;
$image_mission_bird_id = 2295;
$image_mission_normal_id = 2283;
?>

<section class="section__mission">
  <div class="section__mission__container">
    <picture class="section__mission__picture">
      <?= '<source media="(max-width: 1025px)" srcset="' . esc_url(wp_get_attachment_image_url($image_mission_id_mb, 'full')) . '" />' ?>
      <?= wp_get_attachment_image($image_mission_id, 'full', false, array('class' => 'section__mission__image')) ?>
    </picture>
    <picture class="section__mission__picture__normal">
      <?= '<source media="(max-width: 1025px)" srcset="' . esc_url(wp_get_attachment_image_url($image_mission_normal_id, 'full')) . '" />' ?>
      <?= wp_get_attachment_image($image_mission_normal_id, 'full', false, array('class' => 'section__mission__image__normal')) ?>
    </picture>
    <?= wp_get_attachment_image($image_mission_bird_id, 'full', false, array('class' => 'section__mission__bird')) ?>
    <p class="section__mission__description__first"><?= esc_html($title); ?></p>

    <p class="section__mission__description__second"><?= esc_html($desc); ?></p>
    <?= wp_get_attachment_image($item_1['image'], 'full', false, array('class' => 'our__mission__image our__mission__image__first')) ?>
    <div class="our__mission__content__container">
      <div class="our__mission__content our__mission__content__first">
        <h2 class="our__mission__content__title"><?= esc_html($item_1['title']); ?></h2>
        <p class="our__mission__content__description"><?= esc_html($item_1['desc']); ?></p>
      </div>
    </div>
    <?= wp_get_attachment_image($item_2['image'], 'full', false, array('class' => 'our__mission__image our__mission__image__second')) ?>
    <div class="our__mission__content__container">
      <div class="our__mission__content our__mission__content__second">
        <h2 class="our__mission__content__title"><?= esc_html($item_2['title']); ?></h2>
        <p class="our__mission__content__description"><?= esc_html($item_1['desc']); ?></p>
      </div>
    </div>
  </div>
</section>