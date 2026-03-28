<?php
$fallback_thumbnail_id = 9763;
$home_categories = get_field('home_categories');
$category_title = isset($home_categories['title']) ? $home_categories['title'] : '';
$category_subtitle = isset($home_categories['subtitle']) ? $home_categories['subtitle'] : '';

$categories = get_terms([
  'taxonomy'   => 'product_cat', 
  'hide_empty' => false,
  'parent' => 0,
]);

$all_categories = get_terms([
  'taxonomy'   => 'product_cat',
  'hide_empty' => false,
]);


$categories_with_img = [];

foreach ($all_categories as $term) {
  $thumbnail_id = get_term_meta($term->term_id, 'thumbnail_id', true);
  $img_url = $thumbnail_id ? wp_get_attachment_image_url($thumbnail_id, 'full') : wp_get_attachment_image_url($fallback_thumbnail_id, 'full');

  $categories_with_img[] = [
    'id'       => $term->term_id,
    'name'     => $term->name,
    'slug'     => $term->slug,
    'parent'   => $term->parent,
    'img_url'  => $img_url,
    'link' => get_term_link($term),
  ];
}

$first_category = $categories[0];
?>

<section id="category" class="h-category">
  <div class="h-category__header-container">
    <h3 class="h-category__subtitle"><?= esc_html($category_subtitle); ?></h3>
    <h2 class="h-category__title"><?= esc_html($category_title); ?></h2>
  </div>
  <div class="h-category__tabs-container">
    <div class="swiper h-category__tabs">
      <div class="swiper-wrapper">
        <?php foreach ($categories as $category): ?>
        <div class="swiper-slide h-category__tab" data-parent-id="<?= esc_attr($category->term_id); ?>">
          <span class="h-category__tab-content">
            <?= esc_html($category->name); ?>
          </span>
          <div class="h-category__tab-bar"></div>
        </div>
        <?php endforeach; ?>
      </div>
    </div>
    <div class="h-category__navigations">
      <button type="button" class="h-category__tab-prev">
        <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 20 20" fill="none"
          class="h-category__tab-icon">
          <path
            d="M16.4424 10L11.0801 15.3613L9.70605 15.3193L11.8564 13.1689C12.5555 12.4709 13.1948 11.8457 13.7754 11.2939L14.5762 10.5322L13.4717 10.5273L4.58496 10.4854L4.63281 9.42383L13.5537 9.4668L14.6768 9.47266L13.8613 8.7002C13.5777 8.43151 13.2789 8.14377 12.9648 7.83691L11.9766 6.8584L9.74805 4.63086L11.0293 4.58887L16.4424 10Z"
            fill="#680103" stroke="#680103" stroke-width="0.8888" />
        </svg>
      </button>
      <button type="button" class="h-category__tab-next">
        <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 20 20" fill="none"
          class="h-category__tab-icon">
          <path
            d="M16.4424 10L11.0801 15.3613L9.70605 15.3193L11.8564 13.1689C12.5555 12.4709 13.1948 11.8457 13.7754 11.2939L14.5762 10.5322L13.4717 10.5273L4.58496 10.4854L4.63281 9.42383L13.5537 9.4668L14.6768 9.47266L13.8613 8.7002C13.5777 8.43151 13.2789 8.14377 12.9648 7.83691L11.9766 6.8584L9.74805 4.63086L11.0293 4.58887L16.4424 10Z"
            fill="#680103" stroke="#680103" stroke-width="0.8888" />
        </svg>
      </button>
    </div>
  </div>


  <div class="h-category__items-container">
    <div class="swiper h-category__items">
      <div class="swiper-wrapper">
        <?php if (IS_MOBILE): ?>
        <?php $subcategories = get_terms([
            'taxonomy'   => 'product_cat',
            'hide_empty' => false,
            'parent'     => $first_category->term_id,
          ]); ?>
        <div class="swiper-slide">
          <?php foreach($subcategories as $index => $subcategory): ?>
          <?php
            $thumbnail_id = (int) get_term_meta($subcategory->term_id, 'thumbnail_id', true);
            $thumbnail_id = $thumbnail_id ? $thumbnail_id : (int) $fallback_thumbnail_id;
          ?>
          <a href="<?= esc_url(get_term_link($subcategory)); ?>" class="h-category__item">
            <?php
              $thumb_html = wp_get_attachment_image($thumbnail_id, 'full', false, array('class' => 'h-category__item-img'));
              if (empty($thumb_html)) {
                $thumb_html = wp_get_attachment_image((int) $fallback_thumbnail_id, 'full', false, array('class' => 'h-category__item-img'));
              }
              echo $thumb_html;
            ?>
            <div class="h-category__item-text">
              <span>
                <?= esc_html($subcategory->name); ?>
              </span>
            </div>
          </a>
          <?php if( ($index + 1) % 2 == 0 ): ?>
          <div class="h-category__line"></div>
          <?php endif; ?>
          <?php endforeach; ?>
        </div>
        <?php else: ?>
        <?php foreach($categories as $category): ?>
        <?php $subcategories = get_terms([
            'taxonomy'   => 'product_cat',
            'hide_empty' => false,
            'parent'     => $category->term_id,
          ]); ?>
        <div class="swiper-slide">
          <?php foreach($subcategories as $subcategory): ?>
          <?php
            $thumbnail_id = (int) get_term_meta($subcategory->term_id, 'thumbnail_id', true);
            $thumbnail_id = $thumbnail_id ? $thumbnail_id : (int) $fallback_thumbnail_id;
          ?>
          <a href="<?= esc_url(get_term_link($subcategory)); ?>" class="h-category__item">
            <?php
              $thumb_html = wp_get_attachment_image($thumbnail_id, 'full', false, array('class' => 'h-category__item-img'));
              if (empty($thumb_html)) {
                $thumb_html = wp_get_attachment_image((int) $fallback_thumbnail_id, 'full', false, array('class' => 'h-category__item-img'));
              }
              echo $thumb_html;
            ?>
            <div class="h-category__item-text">
              <span>
                <?= esc_html($subcategory->name); ?>
              </span>
            </div>
            <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 20 20" fill="none"
              class="h-category__item-icon">
              <path
                d="M16.4424 10L11.0801 15.3613L9.70605 15.3193L11.8564 13.1689C12.5555 12.4709 13.1948 11.8457 13.7754 11.2939L14.5762 10.5322L13.4717 10.5273L4.58496 10.4854L4.63281 9.42383L13.5537 9.4668L14.6768 9.47266L13.8613 8.7002C13.5777 8.43151 13.2789 8.14377 12.9648 7.83691L11.9766 6.8584L9.74805 4.63086L11.0293 4.58887L16.4424 10Z"
                fill="#680103" stroke="#680103" stroke-width="0.8888" />
            </svg>
          </a>
          <?php endforeach; ?>
        </div>
        <?php endforeach; ?>
        <?php endif; ?>
      </div>
    </div>
  </div>


  <?php if (IS_MOBILE): ?>
  <div class="h-category__parent">
    <h2 class="h-category__parent-title">Danh sách danh mục</h2>
    <div class="h-category__parent-items">
      <?php foreach($categories as $category):  ?>
       <?php $thumbnail_id = (int) get_term_meta($category->term_id, 'thumbnail_id', true); ?>
      <div class="h-category__parent-item">
        <?php
          $thumb_id = $thumbnail_id ? $thumbnail_id : (int) $fallback_thumbnail_id;
          $thumb_html = wp_get_attachment_image($thumb_id, 'full', false, array('class' => 'h-category__parent-img'));
          if (empty($thumb_html)) {
            $thumb_html = wp_get_attachment_image((int) $fallback_thumbnail_id, 'full', false, array('class' => 'h-category__parent-img'));
          }
          echo $thumb_html;
        ?>
        <span><?= esc_html($category->name); ?></span>
      </div>
      <?php endforeach; ?>
    </div>
  </div>
  <?php endif; ?>
</section>



<script>
const categories = <?= json_encode($categories_with_img); ?>;
</script>