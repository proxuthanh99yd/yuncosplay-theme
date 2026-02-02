<?php
$term  = get_queried_object();
$stays = get_field('destination_stays', $term);
$title = $stays['title'] ?? '';

$args = [
  'post_type'      => 'hotel',
  'post_status'    => 'publish',
  'posts_per_page' => -1, // hoặc giới hạn số lượng
  'tax_query' => [
    [
      'taxonomy' => 'destination',
      'field'    => 'term_id',
      'terms'    => $term->term_id,
    ],
  ],
];

$hotel_query = new WP_Query($args);

$deco_id = 1450;
$deco_mb_id = 1977;
$review_icon_id = 1994;
$location_icon_id = 1995;
$star_icon_id = 1996;
?>

<section id="stays-collection" class="destination-stays">
  <?= wp_get_attachment_image($deco_id, 'full', false, ['class' => 'destination-stays_deco']) ?>
  <?= wp_get_attachment_image($deco_mb_id, 'full', false, ['class' => 'destination-stays_deco-mb']) ?>

  <div class="destination-stays_container">
    <h2 class="destination-stays_title"><?= esc_html($title) ?></h2>

    <div class="destination-stays_cards">
     
        <?php if ($hotel_query->have_posts()): ?>
        <?php while ($hotel_query->have_posts()): $hotel_query->the_post(); ?>
        <?php
        $hotel_id = get_the_ID();

        $permalink = get_permalink($hotel_id);
        $hotel_title = get_the_title($hotel_id);
        $desc = get_the_excerpt($hotel_id);
        $thumb_id = get_post_thumbnail_id($hotel_id);

        $information = get_field('information', $hotel_id) ?: [];
        $gallery_ids = $information['images'] ?? [];
        $gallery_urls = [];
        if (is_array($gallery_ids)) {
          foreach ($gallery_ids as $gallery_id) {
            $url = wp_get_attachment_image_url($gallery_id, 'full');
            if ($url) $gallery_urls[] = $url;
          }
        }

        $price_range = $information['price_range'] ?? [];
        $price_from = $price_range['price_from'] ?? '';
        $price_to   = $price_range['price_to'] ?? '';

        $rating = isset($information['review_rating'])
          ? floatval($information['review_rating'])
          : 5;
        $google_map_link = $information['google_map_link'] ?? '';
        $google_map_url = '';
        $google_map_target = '';
        $google_map_title = '';
        if (is_array($google_map_link)) {
          $google_map_url = $google_map_link['url'] ?? '';
          $google_map_target = $google_map_link['target'] ?? '';
          $google_map_title = $google_map_link['title'] ?? '';
        } else {
          $google_map_url = $google_map_link;
        }

        $services = [];
        $terms = get_the_terms($hotel_id, 'service');

        if (!is_wp_error($terms) && !empty($terms)) {
          foreach ($terms as $t) {
            $icon_id = get_field('icon', 'term_' . $t->term_id);

            $services[] = [
              'id'       => (int) $t->term_id,
              'name'     => $t->name,
              'icon_id'  => (int) $icon_id,
              'icon_url' => wp_get_attachment_image_url($icon_id, 'full'),
            ];
          }
        }
      ?>
        <button
          type="button"
          class="destination-stays_card"
          data-stay-id="<?= esc_attr($hotel_id); ?>"
          data-name="<?= esc_attr($hotel_title); ?>"
          data-desc="<?= esc_attr($desc); ?>"
          data-link="<?= esc_url($permalink); ?>"
          data-price-from="<?= esc_attr($price_from); ?>"
          data-price-to="<?= esc_attr($price_to); ?>"
          data-rating="<?= esc_attr($rating); ?>"
          data-google-map-link="<?= esc_attr($google_map_url); ?>"
          data-google-map-target="<?= esc_attr($google_map_target); ?>"
          data-google-map-title="<?= esc_attr($google_map_title); ?>"
          data-gallery='<?= esc_attr(json_encode($gallery_urls)); ?>'
          data-services='<?= esc_attr(json_encode($services)); ?>'>
          <?= wp_get_attachment_image(
            $thumb_id,
            'full',
            false,
            ['class' => 'destination-stays_card-img']
          ) ?>

          <div class="destination-stays_card-overlay"></div>

          <div class="destination-stays_card-content">
            <h3 class="destination-stays_card-title"><?= esc_html($hotel_title) ?></h3>
            <?php if ($desc): ?>
              <p class="destination-stays_card-desc"><?= esc_html($desc) ?></p>
            <?php endif; ?>

            <div class="destination-stays_card-view">View hotel</div>
          </div>
        </button>
          <?php endwhile; ?>
        <?php wp_reset_postdata(); ?>
        <?php endif; ?>
    </div>

    <a href="/hotels" class="destination-stays_view-all compound-avian-button compound-avian-button--lg">
      <div class="compound-avian-button__content">
        <span class="compound-avian-button__content-text">View all</span>
      </div>
    </a>
  </div>
</section>


<!-- Popup stays -->
<div class="stays-popup__popup" aria-hidden="true">
  <div class="stays-popup__overlay"></div>
  <div class="stays-popup__content" role="dialog" aria-modal="true" aria-label="Hotel details" tabindex="-1">
    <button type="button" class="stays-popup__close">
      <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 18 18" fill="none">
        <path d="M0.407651 15.5169C-0.132211 16.0568 -0.143229 17.0153 0.418669 17.5772C0.991584 18.1391 1.95011 18.1281 2.47896 17.5992L9.00138 11.0768L15.5128 17.5882C16.0637 18.1391 17.0112 18.1391 17.5731 17.5772C18.135 17.0043 18.135 16.0678 17.5841 15.5169L11.0727 9.00551L17.5841 2.4831C18.135 1.93222 18.146 0.984707 17.5731 0.42281C17.0112 -0.139087 16.0637 -0.139087 15.5128 0.411792L9.00138 6.92319L2.47896 0.411792C1.95011 -0.128069 0.980566 -0.150104 0.418669 0.42281C-0.143229 0.984707 -0.132211 1.95425 0.407651 2.4831L6.91905 9.00551L0.407651 15.5169Z" fill="white" />
      </svg>
    </button>
    <div class="stays-popup__top">
      <div class="stays-popup__gallery">
        <div class="stays-popup__gallery-overlay"></div>
        <div class="stays-popup__gallery-main">
          <div class="stays-popup__gallery-swiper swiper">
            <div class="swiper-wrapper"></div>
          </div>
        </div>

        <div class="stays-popup__gallery-thumbs">
          <div class="stays-popup__gallery-thumbs-swiper swiper">
            <div class="swiper-wrapper"></div>
          </div>
        </div>
      </div>
    </div>

    <div class="stays-popup__bottom">
      <div class="stays-popup__bottom-content">
        <p class="stays-popup__price">
          <span class="price-gradient">
            $ <span class="stays-popup__price-from"></span> - $ <span class="stays-popup__price-to"></span>
          </span><span class="text-normal">/night</span>
        </p>
        <h3 class="stays-popup__title"></h3>

        <div class="stays-popup__review">
          <?= wp_get_attachment_image($review_icon_id, 'full', false, ['class' => 'stays-popup__rating-icon']) ?>
          <span class="stays-popup__review-text">Review:</span>
          <div class="stays-popup__stars">
            <?php for ($i = 0; $i < 5; $i++): ?>
              <?= wp_get_attachment_image($star_icon_id, 'full', false, ['class' => 'stays-popup__stars-icon']) ?>
            <?php endfor; ?>
          </div>
        </div>

        <div class="stays-popup__address">
          <?= wp_get_attachment_image($location_icon_id, 'full', false, ['class' => 'stays-popup__address-icon']) ?>
          <span class="stays-popup__address-text"></span>
          <a href="#" target="_blank" class="stays-popup__address-link">
            Display map
          </a>
        </div>
        <div class="stays-popup__body" data-lenis-prevent>
          <p class="stays-popup__desc"></p>
          <div class="stays-popup__services"></div>
        </div>
      </div>
          <a href="#" class="stays-popup__link compound-avian-button compound-avian-button--lg">
            <div class="compound-avian-button__content">
              <span class="compound-avian-button__content-text">Hotel Detail</span>
            </div>
          </a>
      
    </div>
  </div>
</div>