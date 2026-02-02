<?php
$term = get_queried_object();
$resort = get_field('holiday_resort', $term);
$title = isset($resort['title']) ? $resort['title'] : '';

$args = [
  'post_type'      => 'hotel',
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

$hotel_query = new WP_Query($args);

$items = [];

if ($hotel_query->have_posts()) {
    while ($hotel_query->have_posts()) {
        $hotel_query->the_post();
        $hotel_id   = get_the_ID();
        $permalink = get_permalink();
        $hotel_title     = get_the_title();
        $hotel_desc      = get_the_excerpt();
        $thumb_id  = get_post_thumbnail_id($hotel_id);
        $thumbnail_image = $thumb_id ? $thumb_id : 1916;
        
        $items[] = [
            'id' => $hotel_id,
            'title' => $hotel_title,
            'desc' => $hotel_desc,
            'link' => $permalink,
            'thumb_id' => $thumb_id,
            'thumbnail_image' => $thumbnail_image
        ];
    }
    wp_reset_postdata();
}

$deco_id = 2095;
$deco_mb_id = 2096;

$total = count($items);

$totalFormatted = str_pad($total, 2, '0', STR_PAD_LEFT);
?>

<section id="resort-collection" class="ht-resort">
  <?= wp_get_attachment_image($deco_id, "full", false, [
    "class" => "ht-resort_deco"
  ]) ?>
  <?= wp_get_attachment_image($deco_mb_id, "full", false, [
    "class" => "ht-resort_deco-mb"
  ]) ?>
  <div class="ht-resort_container">
    <div class="ht-resort_left">
      <h2 class="ht-resort_title">
        <?= esc_html($title); ?>
      </h2>
      <div class="ht-resort_line"></div>
      <div class="ht-resort_list-wrapper">
        <div class="swiper ht-resort_list">
          <div class="swiper-wrapper">
            <?php foreach($items as $item): ?>
            <?php 
              $item_id = $item['id'];
              $title = $item['title'];
              $desc  = $item['desc'];
              $permalink = $item['link'];
              $img_id = $item['thumb_id'];
            ?>
            <div class="swiper-slide ht-resort_item">
              <div class="ht-resort_item-content">
                <h3 class="ht-resort_item-title">
                  <?= esc_html($title); ?>
                </h3>
                <p class="ht-resort_item-desc">
                  <?= esc_html($desc); ?>
                </p>
                <a href="<?= esc_attr($permalink); ?>"
                  class="ht-resort_link compound-avian-button compound-avian-button--lg">
                  <div class="compound-avian-button__content">
                    <span class="compound-avian-button__content-text">View detail</span>
                  </div>
                </a>
              </div>
              <?= wp_get_attachment_image($img_id, "full", false, [
                "class" => "ht-resort_img mb"
              ]) ?>
            </div>
            <?php endforeach; ?>
          </div>
          <div class="swiper-pagination"></div>
        </div>
        <div class="swiper-button-prev"></div>
        <div class="swiper-button-next"></div>
      </div>
    </div>
    <div class="ht-resort_right">
      <div class="swiper ht-resort_thumbnails">
        <div class="swiper-wrapper">
          <?php foreach($items as $item): ?>
          <?php
            $item_id = $item['id'];
            $img_id = $item['thumb_id']; ?>
          <div class="swiper-slide">
            <?= wp_get_attachment_image($img_id, "full", false, ["class" => "ht-resort_img"]) ?>
          </div>
          <?php endforeach ?>
        </div>

      </div>
      <div class="ht-resort_count">
        <span class="current">01</span>
        <span>
          /
        </span>
        <span class="total"><?= esc_html($totalFormatted); ?></span>
      </div>
    </div>

  </div>
</section>