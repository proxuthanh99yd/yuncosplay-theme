<?php
$image_item_id = 97;
$overlay_item_id = 127;
$overlay_mb_item_id = 141;
$icon_star_id = 139;
$bg_id = 140;


$section_service = get_field('service');
$section_service_title = $section_service['title'] ?? '';

$service_items_args = [
  'post_type' => 'service',
  'post_status' => 'publish',
  'posts_per_page' => -1,
  'orderby' => 'menu_order',
  'order' => 'ASC'
];
$section_service_items = [];
$service_items_query = new WP_Query($service_items_args);

if ($service_items_query->have_posts()) {
  while ($service_items_query->have_posts()) {
    $service_items_query->the_post();
    $service_offer = get_field('service_offer') ?: [];

    $section_service_items[] = [
      'service_title' => get_the_title() ?? '',
      'service_description' => get_the_excerpt() ?? '',
      'service_thumbnail' => get_post_thumbnail_id() ?? null,
      'service_link' => get_the_permalink() ?? '',
      'service_offer_title' => $service_offer['title'] ?? '',
      'service_offer_subtitle' => $service_offer['subtitle'] ?? null,
      'service_offer_items' =>  $service_offer['offer_items'] ?? [],
    ];
  }
  wp_reset_postdata();
}

$isMobileDevice = isMobileDevice() || wp_is_mobile();
?>

<section class="home-services">
  <div class="home-services__container">
    <?= wp_get_attachment_image($bg_id, 'full', false, array('loading' => 'lazy', 'decoding' => 'async', 'class' => 'home-services__bg')) ?>

    <!-- left -->
    <div class="home-services__list-container">
      <h2 class="home-services__title">
        <?= $section_service_title ?>
      </h2>
      <div class="home-services__list" <?= $isMobileDevice  ? '' : 'data-lenis-prevent'; ?> >
        <?php
        foreach ($section_service_items as $index => $service_item): ?>
          <?php
          $service_title = $service_item['service_title'] ?? '';
          $service_description = $service_item['service_description'] ?? '';
          $service_thumbnail = $service_item['service_thumbnail'] ?? null;
          $service_link = $service_item['service_link'] ?? '';
          $service_offer_title = $service_item['service_offer_title'] ?? '';
          $service_offer_subtitle = $service_item['service_offer_subtitle'] ?? null;
          $service_offer_items = $service_item['service_offer_items'] ?? [];
          ?>
          <div class="home-services__list-item <?= $index === 0 ? 'active' : '' ?>" data-index="<?= $index ?>">
            <h3 class="home-services__list-item-title">
              <?= $service_title; ?>
            </h3>
            <p class="home-services__list-item-description">
              <?= $service_description; ?>
            </p>
            <svg class="home-services__list-item-icon" xmlns="http://www.w3.org/2000/svg" width="18" height="18"
              viewBox="0 0 18 18" fill="none">
              <path d="M3.75009 5.99991L9.5088 11.6251L15.0001 5.99991" stroke="#680103" stroke-width="2" />
            </svg>
          </div>

          <div class="home-services__accordion">
            <p class="home-services__accordion-description">
              <?= $service_offer_subtitle; ?>
            </p>
            <div class="home-services__accordion-media">
              <div class="home-services__media-gradient"></div>
              <div class="home-services__media-gradient-1"></div>
              <?php if ($service_thumbnail): ?>
                <?= wp_get_attachment_image($service_thumbnail, 'full', false, array('loading' => 'lazy', 'decoding' => 'async', 'class' => 'home-services__media-image')) ?>
              <?php endif; ?>
              <?= wp_get_attachment_image($overlay_mb_item_id, 'full', false, array('loading' => 'lazy', 'decoding' => 'async', 'class' => 'home-services__media-overlay')) ?>
              <div class="home-services__accordion-media-content">
                <div class="home-services__decor-text">
                  <?= $service_offer_subtitle; ?>
                </div>
                <h4 class="home-services__content-title">
                  <?= $service_offer_title; ?>
                </h4>
              </div>
            </div>
            <ul class="home-services__content-list">
              <?php foreach ($service_offer_items as $service_offer_item): ?>
                <li class="home-services__content-list-item">
                  <?= wp_get_attachment_image($icon_star_id, 'full', false, array('loading' => 'lazy', 'decoding' => 'async', 'class' => 'home-services__media-icon')) ?>
                  <span class="home-services__content-list-item-text">
                    <?= $service_offer_item['offer_item'] ?? '' ?>
                  </span>
                </li>
              <?php endforeach; ?>
            </ul>
            <a href="<?= $service_link ?>" class="home-services__button">
              <?php get_template_part('template-parts/components/animated-button/index', null, array('text' => 'Chi tiết dịch vụ')); ?>
            </a>
          </div>
          <?php
        endforeach;
        ?>
      </div>
    </div>

    <!-- right -->
    <div class="home-services__panels">
      <?php
      foreach ($section_service_items as $index => $service_item):
        $service_title = $service_item['service_title'] ?? '';
        $service_description = $service_item['service_description'] ?? '';
        $service_thumbnail = $service_item['service_thumbnail'] ?? null;
        $service_link = $service_item['service_link'] ?? '';
        $service_offer_title = $service_item['service_offer_title'] ?? '';
        $service_offer_subtitle = $service_item['service_offer_subtitle'] ?? null;
        $service_offer_items = $service_item['service_offer_items'] ?? [];
        ?>
        <div class="home-services__media <?= $index === 0 ? 'active' : '' ?>" data-index="<?= $index ?>">
          <div class="home-services__media-gradient"></div>
          <div class="home-services__media-gradient-1"></div>
          <?php if ($service_thumbnail): ?>
            <?= wp_get_attachment_image($service_thumbnail, 'full', false, array('loading' => 'lazy', 'decoding' => 'async', 'class' => 'home-services__media-image')) ?>
          <?php endif; ?>
          <?= wp_get_attachment_image($overlay_item_id, 'full', false, array('loading' => 'lazy', 'decoding' => 'async', 'class' => 'home-services__media-overlay')) ?>
          <div class="home-services__content">
            <h4 class="home-services__content-title">
              <?= $service_offer_title ?>
            </h4>
            <ul class="home-services__content-list">
              <?php foreach ($service_offer_items as $service_offer_item): ?>
                <li class="home-services__content-list-item">
                  <?= wp_get_attachment_image($icon_star_id, 'full', false, array('loading' => 'lazy', 'decoding' => 'async', 'class' => 'home-services__media-icon')) ?>
                  <span class="home-services__content-list-item-text">
                    <?= $service_offer_item['offer_item'] ?? '' ?>
                  </span>
                </li>
              <?php endforeach; ?>
            </ul>
            <a href="<?= $service_link ?>" class="home-services__button">
              <?php get_template_part('template-parts/components/animated-button/index', null, array('text' => 'Chi tiết dịch vụ')); ?>
            </a>
          </div>
          <?php if ($service_offer_subtitle): ?>
            <div class="home-services__decor-text">
              <?= $service_offer_subtitle ?>
            </div>
          <?php endif; ?>
        </div>
        <?php
      endforeach;
      ?>
    </div>
  </div>
</section>