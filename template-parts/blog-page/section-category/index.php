<?php
$background_category_blog_top_mobile_id = 2047;
$background_category_blog_bottom_mobile_id = 2048;
$editorial_image_id = 1200;

$current_category = get_queried_object();
$current_slug = $current_category->slug ?? '';
$default_image_id = 1916;

/* Lấy category */
$categories = get_categories([
  'taxonomy'   => 'category',
  'hide_empty' => false,
]);
?>

<section class="section-category" id="section-category">

  <?= wp_get_attachment_image(
    $background_category_blog_top_mobile_id,
    'full',
    false,
    ['class' => 'category-background__top--mobile']
  ) ?>

  <?= wp_get_attachment_image(
    $background_category_blog_bottom_mobile_id,
    'full',
    false,
    ['class' => 'category-background__bottom--mobile']
  ) ?>

  <div class="editorial-container">

    <?php foreach ($categories as $cat) :
      $thumb_id = get_field('category_thumbnail', 'category_' . $cat->term_id);
      $image_id = $thumb_id ?: $default_image_id;
      $is_disabled = ($cat->slug === $current_slug);
    ?>
      <a href="<?= esc_url(get_category_link($cat->term_id)); ?>" class="editorial-card <?= $is_disabled ? 'is-disabled' : '' ?>" <?= $is_disabled ? 'aria-disabled="true"' : '' ?>>

        <div class="editorial-card__image">
            <?= wp_get_attachment_image(
              $image_id,
              'large',
              false,
              ['alt' => esc_attr($cat->name)]
            ); ?>
        </div>
        <div class="editorial-card__overlay"></div>

        <h3 class="editorial-card__title">
          <?= esc_html($cat->name); ?>
        </h3>

        <span class="editorial-card__view-more">
          View more
        </span>

      </a>
    <?php endforeach; ?>

  </div>
</section>