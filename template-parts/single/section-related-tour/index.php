<?php
$fallback_image_id = 1916;
$arrow_right_mobile_id = 1365;
$arrow_right_id = 1210;
$background_image_id = 2231;
$fallback_contact_card_image_id = 1916;

// Get related tours based on current post's taxonomies (destination, holiday-type)
$current_post_id = get_the_ID();

// Build tax query for related tours
$tax_query = array('relation' => 'OR');

// Get destination terms from current post
$destination_terms = get_the_terms($current_post_id, 'destination');
if (!empty($destination_terms) && !is_wp_error($destination_terms)) {
    $destination_ids = wp_list_pluck($destination_terms, 'term_id');
    $tax_query[] = array(
        'taxonomy' => 'destination',
        'field'    => 'term_id',
        'terms'    => $destination_ids,
    );
}

// Get holiday-type terms from current post
$holiday_type_terms = get_the_terms($current_post_id, 'holiday-type');
if (!empty($holiday_type_terms) && !is_wp_error($holiday_type_terms)) {
    $holiday_type_ids = wp_list_pluck($holiday_type_terms, 'term_id');
    $tax_query[] = array(
        'taxonomy' => 'holiday-type',
        'field'    => 'term_id',
        'terms'    => $holiday_type_ids,
    );
}

// If we have taxonomy terms, query by taxonomy
if (count($tax_query) > 1) { // More than just 'relation'
    $args = array(
        'post_type' => 'tour',
        'posts_per_page' => 10,
        'post_status' => 'publish',
        'post__not_in' => array($current_post_id),
        'tax_query' => $tax_query,
        'orderby' => 'date',
        'order' => 'DESC',
    );
} else {
    // Fallback: get latest tours if no taxonomy terms found
    $args = array(
        'post_type' => 'tour',
        'posts_per_page' => 10,
        'post_status' => 'publish',
        'post__not_in' => array($current_post_id),
        'orderby' => 'date',
        'order' => 'DESC',
    );
}

$related_tours_query = new WP_Query($args);
$related_tour = get_field('related_tour');
$title = $related_tour['title'] ?? '';
$description = wp_is_mobile() ? ($related_tour['description_mobile'] ?? '') : ($related_tour['description_desktop'] ?? '');
$contact_card = $related_tour['contact_card'];
$card_image = !empty($contact_card['image']) ? (int) $contact_card['image'] : $fallback_contact_card_image_id;
$card_title = $contact_card['title'] ?? '';
$card_description = $contact_card['description'] ?? '';
$card_link = $contact_card['link']['url'] ?? '';
$card_link_title = $contact_card['link']['title'] ?? '';
?>
<section class="related-tour">

  <div class="related-tour__background">
    <?= wp_get_attachment_image($background_image_id, 'full', false, array('class' => 'related-tour__background-image')) ?>
  </div>
  <div class="related-tour__content">
    <div class="related-tour__left">
      <?php if (!empty($title)) : ?>
        <h2 class="related-tour__title"><?php echo esc_html($title); ?></h2>
      <?php endif; ?>
      <?php if (!empty($description)) : ?>
        <p class="related-tour__description"><?php echo esc_html($description); ?></p>
      <?php endif; ?>
    </div>
    <div class="related-tour__right">
      <div class="related-tour-swiper-container">
        <div id="related-tour-swiper" class="related-tour__swiper swiper">
          <div class="swiper-wrapper">
            <?php if ($related_tours_query->have_posts()) : ?>
              <?php while ($related_tours_query->have_posts()) : $related_tours_query->the_post(); ?>
                <div class="swiper-slide">
                  <?php get_template_part('template-parts/components/tour-item/index'); ?>
                </div>
              <?php endwhile; ?>
              <?php wp_reset_postdata(); ?>
            <?php endif; ?>
            
            <!-- card slide mobile -->
            <div class="related-tour__card related-tour__card--mobile">
              <div class="related-tour__overlay"></div>
              <?php if ($card_image): ?>
                <?= wp_get_attachment_image($card_image, 'full', false, ['class' => 'related-tour__image']) ?>
              <?php endif; ?>
              <div class="related-tour__card-title">
                <?php if (!empty($card_title)) : ?>
                  <h2><?php echo esc_html($card_title); ?></h2>
                <?php endif; ?>
                <?php if (!empty($card_description)) : ?>
                  <p><?php echo esc_html($card_description); ?></p>
                <?php endif; ?>
              </div>
              <?php if (!empty($card_link)) : ?>
                <a href="<?php echo esc_url($card_link); ?>" class="related-tour__button compound-avian-button">
                  <span class="compound-avian-button__content">
                    <span class="compound-avian-button__content-text"><?php echo esc_html($card_link_title); ?></span>
                  </span>
                </a>
              <?php endif; ?>
            </div>
            
          </div>
        </div>
        <div class="related-tour__navigation">
          <button class="related-tour__swiper-button related-tour__swiper-button--prev" aria-label="Previous slide">
            <?= wp_get_attachment_image($arrow_right_id, 'full', false, array('loading' => 'lazy', 'decoding' => 'async', 'class' => 'related-tour__swiper-button-icon')) ?>
          </button>
          <button class="related-tour__swiper-button related-tour__swiper-button--next" aria-label="Next slide">
            <?= wp_get_attachment_image($arrow_right_id, 'full', false, array('loading' => 'lazy', 'decoding' => 'async', 'class' => 'related-tour__swiper-button-icon')) ?>
          </button>
        </div>
      </div>

      <!-- card desktop -->
      <div class="related-tour__card related-tour__card--desktop">
        <div class="related-tour__overlay"></div>
        <?php if ($card_image): ?>
          <?= wp_get_attachment_image($card_image, 'full', false, array('class' => 'related-tour__image')) ?>
        <?php endif; ?>
        <div class="related-tour__card-title">
          <?php if (!empty($card_title)) : ?>
            <h2><?php echo esc_html($card_title); ?></h2>
          <?php endif; ?>
          <?php if (!empty($card_description)) : ?>
            <p><?php echo esc_html($card_description); ?></p>
          <?php endif; ?>
        </div>
        <?php if (!empty($card_link)) : ?>
          <a href="<?php echo esc_url($card_link); ?>" class="related-tour__button compound-avian-button">
            <span class="compound-avian-button__content">
              <span class="compound-avian-button__content-text"><?php echo esc_html($card_link_title); ?></span>
            </span>
          </a>
        <?php endif; ?>
      </div>

      <a href="/contact" class="related_tour__link-view-all">
        View ALL
      </a>
      <a href="/contact" class="related_tour__link-view-all-mobile compound-avian-button">
        <div class="compound-avian-button__content">
          <span class="compound-avian-button__content-text">View ALL</span>
        </div>
      </a>
    </div>
  </div>
</section>