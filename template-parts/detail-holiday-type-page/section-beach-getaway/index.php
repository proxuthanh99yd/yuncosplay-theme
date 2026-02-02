<?php
$term = get_queried_object();
$beach_getaway = get_field("holiday_beach-getaway", $term);
$title = isset($beach_getaway['title']) ? $beach_getaway['title'] : '';
$desc = isset($beach_getaway['desc']) ? $beach_getaway['desc'] : '';
$tours = isset($beach_getaway['tours']) ? $beach_getaway['tours'] : [];
$card_contact = $beach_getaway['card_contact'];


$args = [
  'post_type'      => 'tour',
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

$tour_query = new WP_Query($args);

$card_contact_link = $card_contact['link'];

$deco_id = 2089;
$deco_mb_id = 2344;
?>

<section id="beach-getaway" class="ht-beach-getaway">
  <div class="ht-beach-getaway_deco-wrapper">
    <?= wp_get_attachment_image($deco_id, 'full', false, array('class' => 'ht-beach-getaway_deco')) ?>
  </div>
  <?= wp_get_attachment_image($deco_mb_id, 'full', false, array('class' => 'ht-beach-getaway_deco-mb')) ?>
  <div class="ht-beach-getaway_container">
    <div class="ht-beach-getaway_content">
      <h2 class="ht-beach-getaway_title"><?= $title ?></h2>
      <p class="ht-beach-getaway_description">
        <?= $desc ?>
      </p>
    </div>
    <div class="ht-beach-getaway_card-container">
      <div class="ht-beach-getaway_card-wrapper">
        <div class="ht-beach-getaway_cards swiper">
          <div class="swiper-wrapper">
            <?php if ($tour_query->have_posts()): ?>
              <?php while ($tour_query->have_posts()): $tour_query->the_post(); ?>
                <?php
                $tour_id   = get_the_ID();
                $permalink = get_permalink();
                $title     = get_the_title();
                $desc      = get_the_excerpt();
                $thumb_id  = get_post_thumbnail_id($tour_id);
                $thumbnail_image = $thumb_id ? $thumb_id : 1916;

                $leaving_from = get_field('leaving_from', $tour_id);
                $duration     = get_field('tour_duration', $tour_id);
                $price        = get_field('tour_price', $tour_id);

                // Format price like Inspiring
                $tour_price_raw = $price;
                $tour_price = '';
                if (!empty($tour_price_raw) && is_numeric($tour_price_raw)) {
                  $tour_price = '$ ' . number_format($tour_price_raw, 0, '', ',');
                } elseif (!empty($tour_price_raw)) {
                  $tour_price = $tour_price_raw;
                }

                // Get destination like Inspiring
                $tour_destination = '';
                $destination_terms = get_the_terms($tour_id, 'destination');
                if (!empty($destination_terms) && !is_wp_error($destination_terms)) {
                  $tour_destination = $destination_terms[0]->name;
                }
                ?>
                <div class="swiper-slide">
                    <a href="<?= $permalink ?>" class="ht-beach-getaway_card">
                      <?= wp_get_attachment_image($thumbnail_image, 'full', false, array('class' => 'ht-beach-getaway_card-img')) ?>
                      <div class="ht-beach-getaway_card-overlay"></div>
                      <div class="ht-beach-getaway_card-content">
                        <h3 class="ht-beach-getaway_card-title"><?= $title ?></h3>
                        <p class="ht-beach-getaway_card-desc">
                          <?= $desc ?>
                        </p>
                        <div class="ht-beach-getaway_card-items-container">
                          <div class="ht-beach-getaway_card-items">
                            <div class="ht-beach-getaway_card-item">
                              <span class="ht-beach-getaway_card-item-title"> Leaving from </span>
                              <span class="ht-beach-getaway_card-item-title--mobile"> Departing from </span>
                              <p class="ht-beach-getaway_card-item-content">
                                <?= isset($leaving_from) ? $leaving_from : ''; ?></p>
                            </div>
                            <div class="ht-beach-getaway_card-item">
                              <span class="ht-beach-getaway_card-item-title">
                                Starting price at
                              </span>
                              <span class="ht-beach-getaway_card-item-title--mobile"> Price from </span>
                              <p class="ht-beach-getaway_card-item-content price">
                                <span class="ht-beach-getaway_card-item-content-duration">
                                  <?= isset($duration) ? $duration : ''; ?> <?= ($duration == 1) ? 'day' : 'days' ?> from
                                </span>
                                <?= isset($tour_price) ? $tour_price : ''; ?>
                              </p>
                            </div>
                          </div>
                        </div>
                        <div class="ht-beach-getaway_card-line"></div>
                        <div class="ht-beach-getaway_card-bottom">
                          <span>View the tour</span>
                          <div class="ht-beach-getaway_card-bottom-right">
                            <?php if ($tour_destination): ?>
                            <span><?= esc_html($tour_destination) ?></span>
                            <?php else: ?>
                            <span>Featured</span>
                            <?php endif; ?>
                            <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 20 20" fill="none"
                              class="ht-beach-getaway_card-bottom-icon">
                              <path
                                d="M9.375 0.625H10.625V3.1262H9.375V0.625ZM9.375 16.8738H10.625V19.375H9.375V16.8738ZM16.8738 9.375H19.375V10.625H16.8738V9.375ZM0.625 9.375H3.1262V10.625H0.625V9.375Z"
                                fill="white" />
                              <path
                                d="M10 20C4.4861 20 0 15.5139 0 10C0 4.4861 4.4861 0 10 0C15.5139 0 20 4.4861 20 10C20 15.5139 15.5139 20 10 20ZM10 1.25C5.1752 1.25 1.25 5.1752 1.25 10C1.25 14.8248 5.1752 18.75 10 18.75C14.8248 18.75 18.75 14.8248 18.75 10C18.75 5.1752 14.8248 1.25 10 1.25Z"
                                fill="white" />
                              <path
                                d="M13.7543 6.24227C13.6843 6.17248 13.5946 6.12589 13.4972 6.10881C13.3999 6.09173 13.2997 6.105 13.2101 6.14681L8.5517 8.32358C8.4493 8.37151 8.36699 8.45389 8.31916 8.55634L6.14668 13.2104C6.10494 13.2999 6.09173 13.4002 6.10885 13.4975C6.12597 13.5948 6.17258 13.6845 6.24238 13.7545C6.33285 13.8448 6.45548 13.8955 6.58333 13.8955C6.65198 13.8955 6.72133 13.881 6.78629 13.8504L11.4447 11.6825C11.5476 11.6349 11.6303 11.5523 11.6779 11.4493L13.8504 6.78637C13.8922 6.69677 13.9054 6.59649 13.8882 6.49915C13.871 6.4018 13.8242 6.31212 13.7543 6.24227ZM8.90113 9.58659L10.4144 11.1L7.57869 12.4195L8.90113 9.58659Z"
                                fill="white" />
                            </svg>
                          </div>
                        </div>
                      </div>
                    </a>
                </div>
            <?php endwhile; ?>
              <?php wp_reset_postdata(); ?>
            <?php endif; ?>
          </div>
        </div>
        <div class="swiper-button-prev"></div>
        <div class="swiper-button-next"></div>
      </div>
      <div class="ht-beach-getaway_card-contact">
        <?= wp_get_attachment_image($card_contact['image']['desktop'], 'full', false, array( 'class' => 'ht-beach-getaway_card-contact-img')) ?>
        <div class="ht-beach-getaway_card_contact-overlay"></div>
        <div class="ht-beach-getaway_card-contact-content">
          <div class="ht-beach-getaway_card-contact-top">
            <h3 class="ht-beach-getaway_card-contact-title"><?= $card_contact['title'] ?></h3>
            <p class="ht-beach-getaway_card-contact-description">
              <?= $card_contact['desc'] ?>
            </p>
          </div>
          <a href="/contact"
            class="ht-beach-getaway_card-contact-link compound-avian-button compound-avian-button--lg">
            <div class="compound-avian-button__content">
              <span class="compound-avian-button__content-text">Contact us for advice</span>
            </div>
          </a>
        </div>
      </div>
      <div class="ht-beach-getaway_view-all-wrapper">
        <a href="/tours" class="ht-beach-getaway_view-all">View all</a>
      </div>

      <div class="ht-beach-getaway_cards-mb">
        <?php if ($tour_query->have_posts()): ?>
            <?php while ($tour_query->have_posts()): $tour_query->the_post(); ?>
            
            <?php
                $tour_id   = get_the_ID();
                $permalink = get_permalink();
                $title     = get_the_title();
                $desc      = get_the_excerpt();
                $thumb_id  = get_post_thumbnail_id($tour_id);
                $thumbnail_image = $thumb_id ? $thumb_id : 1916;
    
                $leaving_from = get_field('leaving_from', $tour_id);
                $duration     = get_field('tour_duration', $tour_id);
                $price        = get_field('tour_price', $tour_id);
    
                // Format price like Inspiring
                $tour_price_raw = $price;
                $tour_price = '';
                if (!empty($tour_price_raw) && is_numeric($tour_price_raw)) {
                  $tour_price = '$ ' . number_format($tour_price_raw, 0, '', ',');
                } elseif (!empty($tour_price_raw)) {
                  $tour_price = $tour_price_raw;
                }
    
                // Get destination like Inspiring
                $tour_destination = '';
                $destination_terms = get_the_terms($tour_id, 'destination');
                if (!empty($destination_terms) && !is_wp_error($destination_terms)) {
                  $tour_destination = $destination_terms[0]->name;
                }
            ?>
            <a href="<?= esc_attr($permalink) ?>" class="ht-beach-getaway_card">
              <?= wp_get_attachment_image($thumbnail_image, 'full', false, array('class' => 'ht-beach-getaway_card-img')) ?>
              <div class="ht-beach-getaway_card-overlay"></div>
              <div class="ht-beach-getaway_card-content">
                <h3 class="ht-beach-getaway_card-title"><?= $title ?></h3>
                <p class="ht-beach-getaway_card-desc">
                  <?= $desc ?>
                </p>
                <div class="ht-beach-getaway_card-items-container">
                  <div class="ht-beach-getaway_card-items">
                    <div class="ht-beach-getaway_card-item">
                      <span class="ht-beach-getaway_card-item-title"> Leaving from </span>
                      <p class="ht-beach-getaway_card-item-content"><?= isset($leaving_from) ? $leaving_from : ''; ?></p>
                    </div>
                    <div class="ht-beach-getaway_card-item">
                      <span class="ht-beach-getaway_card-item-title"> Starting price at </span>
                      <p class="ht-beach-getaway_card-item-content price">
                        <span class="ht-beach-getaway_card-item-content-duration">
                          <?= isset($duration) ? $duration : ''; ?> <?= ($duration == 1) ? 'day' : 'days' ?> from
                        </span>
                        <?= isset($tour_price) ? $tour_price : ''; ?>
                      </p>
                    </div>
                  </div>
                </div>
                <div class="ht-beach-getaway_card-line"></div>
                <div class="ht-beach-getaway_card-bottom">
                  <span>View the tour</span>
                  <div class="ht-beach-getaway_card-bottom-right">
                    <?php if ($tour_destination): ?>
                    <span><?= esc_html($tour_destination) ?></span>
                    <?php else: ?>
                    <span>Featured</span>
                    <?php endif; ?>
                    <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 20 20" fill="none"
                      class="ht-beach-getaway_card-bottom-icon">
                      <path
                        d="M9.375 0.625H10.625V3.1262H9.375V0.625ZM9.375 16.8738H10.625V19.375H9.375V16.8738ZM16.8738 9.375H19.375V10.625H16.8738V9.375ZM0.625 9.375H3.1262V10.625H0.625V9.375Z"
                        fill="white" />
                      <path
                        d="M10 20C4.4861 20 0 15.5139 0 10C0 4.4861 4.4861 0 10 0C15.5139 0 20 4.4861 20 10C20 15.5139 15.5139 20 10 20ZM10 1.25C5.1752 1.25 1.25 5.1752 1.25 10C1.25 14.8248 5.1752 18.75 10 18.75C14.8248 18.75 18.75 14.8248 18.75 10C18.75 5.1752 14.8248 1.25 10 1.25Z"
                        fill="white" />
                      <path
                        d="M13.7543 6.24227C13.6843 6.17248 13.5946 6.12589 13.4972 6.10881C13.3999 6.09173 13.2997 6.105 13.2101 6.14681L8.5517 8.32358C8.4493 8.37151 8.36699 8.45389 8.31916 8.55634L6.14668 13.2104C6.10494 13.2999 6.09173 13.4002 6.10885 13.4975C6.12597 13.5948 6.17258 13.6845 6.24238 13.7545C6.33285 13.8448 6.45548 13.8955 6.58333 13.8955C6.65198 13.8955 6.72133 13.881 6.78629 13.8504L11.4447 11.6825C11.5476 11.6349 11.6303 11.5523 11.6779 11.4493L13.8504 6.78637C13.8922 6.69677 13.9054 6.59649 13.8882 6.49915C13.871 6.4018 13.8242 6.31212 13.7543 6.24227ZM8.90113 9.58659L10.4144 11.1L7.57869 12.4195L8.90113 9.58659Z"
                        fill="white" />
                    </svg>
                  </div>
                </div>
              </div>
            </a>
            <?php endwhile; ?>
            <?php wp_reset_postdata(); ?>
            <?php endif; ?>
        <div class='ht-beach-getaway_card-contact mobile'>
          <div class="ht-beach-getaway_card_contact-overlay"></div>
          <?= wp_get_attachment_image($card_contact['image']['mobile'], 'full', false, array( 'class' => 'ht-beach-getaway_card-contact-img')) ?>
          <div class="ht-beach-getaway_card-contact-content">
            <div class="ht-beach-getaway_card-contact-top">
              <h3 class="ht-beach-getaway_card-contact-title"><?= $card_contact['title'] ?></h3>
              <p class="ht-beach-getaway_card-contact-description">
                <?= $card_contact['desc'] ?>
              </p>
            </div>
            <a href="/contact"
              class="ht-beach-getaway_card-contact-link compound-avian-button compound-avian-button--lg">
              <div class="compound-avian-button__content">
                <span class="compound-avian-button__content-text">Contact us for advice</span>
              </div>
            </a>
          </div>
        </div>
      </div>
      <div class="ht-beach-getaway_view-all-wrapper-mb">
        <a href="/tours" class="ht-beach-getaway_view-all-mb compound-avian-button compound-avian-button--lg">
          <div class="compound-avian-button__content">
            <span class="compound-avian-button__content-text"> View all </span>
          </div>
        </a>

      </div>
    </div>
  </div>
</section>