<?php
$purpose = get_field("about_purpose");
$title = isset($purpose['title']) ? $purpose['title'] : '';
$desc = isset($purpose['desc']) ? $purpose['desc'] : '';
$image_1 = isset($purpose['image_1']) ? $purpose['image_1'] : '';
$image_2 = isset($purpose['image_2']) ? $purpose['image_2'] : '';
$items = isset($purpose['items']) ? $purpose['items'] : [];

$image_purpose_shadow = 2289;
$image_purpose_context = 2294;

?>

<section class="purpose">
  <?= wp_get_attachment_image($image_purpose_context, 'full', false, array('class' => 'purpose__image__context')) ?>
  <div class="purpose__container container">
    <div class="purpose__image__container">
      <picture data-aos="fade-left">
        <?= '<source media="(max-width: 639px)" srcset="' . esc_url(wp_get_attachment_image_url($image_1['mobile'], 'full')) . '" />' ?>
        <?= wp_get_attachment_image($image_1['desktop'], 'full', false, array('class' => 'purpose__image__item__1')) ?>
      </picture>
      <?= wp_get_attachment_image($image_purpose_shadow, 'full', false, array('class' => 'purpose__image__shadow')) ?>
      <picture data-aos="fade-left">
        <?= '<source media="(max-width: 639px)" srcset="' . esc_url(wp_get_attachment_image_url($image_2['mobile'], 'full')) . '" />' ?>
        <?= wp_get_attachment_image($image_2['desktop'], 'full', false, array('class' => 'purpose__image__item__2')) ?>
      </picture>
    </div>
    <div class="purpose__content" data-aos="fade-up" data-aos-duration="1000">
      <h2 class="purpose__title"><?= esc_html($title); ?></h2>
      <div class="purpose__items" data-lenis-prevent>
        <?php foreach ($items as $item): ?>
        <div class="purpose__item">
          <svg xmlns="http://www.w3.org/2000/svg" width="12" height="12" viewBox="0 0 12 12" fill="none"
            class="purpose__item__icon">
            <path
              d="M0 5.99999C2.66666 5.33332 5.33333 2.66666 6 0C6.66667 2.66666 9.33334 5.33332 12 5.99999C9.33334 6.66665 6.66667 9.33331 6 12C5.33333 9.33331 2.66666 6.66665 0 5.99999Z"
              fill="#2E2E2E" />
          </svg>
          <p class="purpose__item__text"><?= esc_html($item['content']); ?></p>
        </div>
        <?php endforeach; ?>
      </div>
    </div>
  </div>
</section>