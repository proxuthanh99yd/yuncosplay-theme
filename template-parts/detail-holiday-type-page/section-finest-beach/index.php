<?php
$term = get_queried_object();
$finest_beach = get_field('holiday_finest-beach', $term);
$title = isset($finest_beach['title']) ? $finest_beach['title'] : '';
$desc = isset($finest_beach['desc']) ? $finest_beach['desc'] : '';
$fallback_id = 1916;


$args = [
  'post_type'      => 'location-details',
  'post_status'    => 'publish',
  'posts_per_page' => -1, // hoặc giới hạn số lượng
  'tax_query' => [
    [
      'taxonomy' => 'holiday-type',
      'field'    => 'term_id',
      'terms'    => $term->term_id,
    ],
  ],
];

$location_query = new WP_Query($args);

$locations = [];


if ($location_query->have_posts()) {
    while ($location_query->have_posts()) {
        $location_query->the_post();
        
        $location_id = get_the_ID();
        $location_title = get_the_title();
        $location_desc = get_the_excerpt();
        $location_slug = get_post_field('post_name', $location_id);
        $information = get_field('information', $location_id);
        $gallery_ids = $information['images'];
        $gallery = [];
        $permalink = get_permalink();
        
        if (!empty($gallery_ids) && is_array($gallery_ids)) {
            foreach ($gallery_ids as $gallery_id) {
                $url = wp_get_attachment_image_url($gallery_id, 'full');
                if ($url) {
                    $gallery[] = $url; // ✅ chỉ push string
                }
            }
        }
        
        $thumb_id  = get_post_thumbnail_id($location_id);
        $thumb_url = $thumb_id ? wp_get_attachment_image_url($thumb_id, 'full') : wp_get_attachment_image_url($fallback_id, 'full');
        
        $locations[] = [
            'id'        => $location_id,
            'name'      => $location_title,
            'desc'      => $location_desc,
            'slug'      => $location_slug,
            'thumbnail' => $thumb_url,
            'thumb_id'  => $thumb_id,
            'gallery'   => $gallery,
            'link'      => $permalink,
        ];
    }
    wp_reset_postdata();
}

$total = count($locations);

$has_view_more = $total > 3;
$percent_progress = 3 / $total * 100;
?>



<section id="finest-beach" class="ht-finest-beach">
  <div class="ht-finest-beach_container">
    <h2 class="ht-finest-beach_title"><?= $title ?></h2>
    <p class="ht-finest-beach_desc">
      <?= $desc ?>
    </p>
    <div class="ht-finest-beach_cards">
      <?php foreach($locations as $index => $item): ?>
      <button type="button" 
        class="ht-finest-beach_card <?= $index > 2 ? 'hidden-mb' : '' ?>"
        data-id="<?= esc_attr($item['id']); ?>"
        data-name="<?= esc_attr($item['name']); ?>"
        data-desc="<?= esc_attr($item['desc']); ?>"
        data-link="<?= esc_url($item['link']); ?>"
        data-gallery='<?= esc_attr(json_encode($item['gallery'] ?? [])); ?>'
        data-thumbnail="<?= esc_url($thumb_url); ?>"
        >
        <div class="ht-finest-beach_card-img-wrapper">
          <?= wp_get_attachment_image($item['thumb_id'], 'full', false, array( 'class' => 'ht-finest-beach_card-img')) ?>
          <div class="ht-finest-beach_card-overlay"></div>
          <div class="ht-finest-beach_card-button">View detail</div>
        </div>
        <div class="ht-finest-beach_card-content">
          <h3 class="ht-finest-beach_card-title"><?= esc_html($item['name']) ?></h3>
        </div>
      </button>
      <?php endforeach; ?>
    </div>
    <?php if ($total > 3): ?>
    <div class="ht-finest-beach_view-more-wrapper">
      <button type='button'
        class="ht-finest-beach_view-more compound-avian-button compound-avian-button--lg <?= !$has_view_more ? 'hidden' : '' ?>">
        <div class="compound-avian-button__content">
          <span class="compound-avian-button__content-text">View more</span>
        </div>
      </button>
      <div class="ht-finest-beach_progress">
        <p class="ht-finest-beach_progress-text">You've viewed <span class="ht-finest-beach_progress-viewed">3</span> of
          <?= $total ?> locations</p>
        <div class="ht-finest-beach_progress-bar">
          <div class="ht-finest-beach-progress-current" style="width: <?= $percent_progress ?>%"></div>
        </div>
      </div>
    </div>
    <?php endif; ?>
  </div>
</section>


<!-- Popup Location -->
<div class="attractions-popup__popup" aria-hidden="true">
  <div class="attractions-popup__overlay"></div>
  <div class="attractions-popup__content" role="dialog" aria-modal="true" aria-label="Attraction details" tabindex="-1">
    <button type="button" class="attractions-popup__close">
      <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 18 18" fill="none">
        <path
          d="M0.407651 15.5169C-0.132211 16.0568 -0.143229 17.0153 0.418669 17.5772C0.991584 18.1391 1.95011 18.1281 2.47896 17.5992L9.00138 11.0768L15.5128 17.5882C16.0637 18.1391 17.0112 18.1391 17.5731 17.5772C18.135 17.0043 18.135 16.0678 17.5841 15.5169L11.0727 9.00551L17.5841 2.4831C18.135 1.93222 18.146 0.984707 17.5731 0.42281C17.0112 -0.139087 16.0637 -0.139087 15.5128 0.411792L9.00138 6.92319L2.47896 0.411792C1.95011 -0.128069 0.980566 -0.150104 0.418669 0.42281C-0.143229 0.984707 -0.132211 1.95425 0.407651 2.4831L6.91905 9.00551L0.407651 15.5169Z"
          fill="white" />
      </svg>
    </button>

    <div class="attractions-popup__top">
      <div class="attractions-popup__gallery">
        <div class="attractions-popup__gallery-overlay"></div>
        <div class="attractions-popup__gallery-main">
          <div class="attractions-popup__gallery-swiper swiper">
            <div class="swiper-wrapper">
            </div>
          </div>
        </div>
        <div class="attractions-popup__gallery-thumbs">
          <div class="attractions-popup__gallery-thumbs-swiper swiper">
            <div class="swiper-wrapper"></div>
          </div>
        </div>
      </div>
    </div>

    <div class="attractions-popup__bottom">
      <div class="attractions-popup__bottom-content">
        <h3 class="attractions-popup__title"></h3>
        <p class="attractions-popup__desc" data-lenise-prevent></p>
      </div>
      <div class="attractions-popup__link-wrapper">
        <a href="#" class="attractions-popup__link compound-avian-button compound-avian-button--lg">
          <div class="compound-avian-button__content">
            <span class="compound-avian-button__content-text">Location details</span>
          </div>
        </a>
        <a href="#" class="attractions-popup__link-2 compound-avian-button compound-avian-button--lg">
          <div class="compound-avian-button__content">
            <span class="compound-avian-button__content-text">Explore related tours</span>
          </div>
        </a>
      </div>
    </div>
  </div>
</div>