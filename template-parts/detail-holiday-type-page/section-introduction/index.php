<?php

$term = get_queried_object();
$introduction = get_field("holiday_introduction", $term);
$title = isset($introduction['title']) ? $introduction['title'] : '';
$desc = isset($introduction['desc']) ? $introduction['desc'] : '';
$content = isset($introduction['content']) ? $introduction['content'] : '';
$img1 = isset($introduction['image_1']) ? $introduction['image_1'] : '';
$img2 = isset($introduction['image_2']) ? $introduction['image_2'] : '';
$img3 = isset($introduction['image_3']) ? $introduction['image_3'] : '';

$deco_id = 2094;
$deco_mb_id = 2093;
?>

<section id="introduction" class="ht-introduction">
  <?= wp_get_attachment_image($deco_id, "full", false, [
    "class" => "ht-introduction_deco",
  ]) ?>
  <?= wp_get_attachment_image($deco_mb_id, "full", false, [
    "class" => "ht-introduction_deco-mb",
  ]) ?>
  <div class="ht-introduction_container">
    <div class="ht-introduction_left">
      <?= wp_get_attachment_image($img1, "full", false, [
    "class" => "ht-introduction_img1",
  ]) ?>
      <div class="ht-introduction_content">
        <?= $content ?>
      </div>
      <div class="ht-introduction_bottom">
        <?= wp_get_attachment_image($img3, "full", false, [
    "class" => "ht-introduction_img3",
  ]) ?>
        <a href="/contact" class="ht-introduction_link compound-avian-button compound-avian-button--lg">
          <div class="compound-avian-button__content">
            <span class="compound-avian-button__content-text">
              Enquire now
            </span>
          </div>
        </a>
      </div>
    </div>
    <div class="ht-introduction_right">
      <!-- prettier-ignore -->
      <h2 class="ht-introduction_title"><?= esc_html($title); ?></h2>
      <p class="ht-introduction_desc">
        <?= esc_html($desc); ?>
      </p>
      <div class="ht-introduction_img-wrapper">
        <?= wp_get_attachment_image($img2, "full", false, [
    "class" => "ht-introduction_img2",
  ]) ?>
        <div class="ht-introduction_wrapper">
          <?= wp_get_attachment_image($img3, "full", false, [
    "class" => "ht-introduction_img3",
  ]) ?>
          <a href="/contact" class="ht-introduction_link compound-avian-button compound-avian-button--lg">
            <div class="compound-avian-button__content">
              <span class="compound-avian-button__content-text">
                Enquire now
              </span>
            </div>
          </a>
        </div>
      </div>
    </div>


  </div>
</section>