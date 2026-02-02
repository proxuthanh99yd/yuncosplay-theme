<?php
$icon_discover_id = 1122;
$icon_location_id = 1707;
$fallback_banner_id = 1916;

$section_banner = get_field('banner');
$title = $section_banner['title'];
$image_pc = $section_banner['background_desktop'] ?: $fallback_banner_id;
$image_mb = $section_banner['background_mobile'] ?: $fallback_banner_id;

// Get post categories (only first one)
$categories = get_the_category();
$category_string = 'Uncategorized';
if (!empty($categories)) {
  $category_string = $categories[0]->name;
}

// Calculate reading time (200 words per minute)
$content = get_the_content();
$word_count = str_word_count(strip_tags($content));
$reading_time = ceil($word_count / 200);
$reading_string = $reading_time . ' min read';
?>

<section id="banner" class="banner">
  <?= wp_get_attachment_image($image_pc, 'full', false, array('class' => 'banner__image banner__image--pc')) ?>
  <?= wp_get_attachment_image($image_mb, 'full', false, array('class' => 'banner__image banner__image--mb')) ?>
  <div class="banner__overlay-1"></div>
  <div class="banner__overlay-2"></div>
  <div class="banner__content">
    <div class="banner__category">
      <span><?= esc_html($category_string) ?></span>
      <svg xmlns="http://www.w3.org/2000/svg" width="7" height="7" viewBox="0 0 7 7" fill="none">
        <path d="M0 3.5C1.55555 3.11111 3.11111 1.55556 3.5 0C3.88889 1.55556 5.44445 3.11111 7 3.5C5.44445 3.88889 3.88889 5.44444 3.5 7.00002C3.11111 5.44444 1.55555 3.88889 0 3.5Z" fill="white" />
      </svg>
      <span><?= esc_html($reading_string) ?></span>
    </div>
    <h1 class="banner__title"><?= $title ?></h1>
  </div>
  <div class="banner__discover">
    <?= wp_get_attachment_image($icon_discover_id, 'full', false, array('class' => 'banner__discover-icon')) ?>
    <span class="banner__discover-text">Discover</span>
  </div>
</section>