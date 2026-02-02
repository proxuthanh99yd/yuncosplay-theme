<?php
$bg_deco_id = 1256;
$bg_deco_mb_id = 1257;
$card_deco_id = 1258;
$card_deco_mb_id = 2338;
$fallback_id = 1916;

$term = get_queried_object();
$about = get_field("destination_about", $term);
$main_card = $about['main_card'];
$card_left = $about['card_left'];
$card_top_right = $about['card_top_right'];
$card_bottom_right = $about['card_bottom_right'];


$destination = $term->slug ?? '';
$url = add_query_arg(
    ['destination' => $destination],
    site_url('/contact')
);
?>

<section id="about" class="destination-about">
      <?= wp_get_attachment_image($bg_deco_id, 'full', false, array( 'class' => 'destination-about_bg')) ?>
      <?= wp_get_attachment_image($bg_deco_mb_id, 'full', false, array( 'class' => 'destination-about_bg-mb')) ?>
      <div class="destination-about_cards">
        <div class="destination-about_main-card">
          <?= wp_get_attachment_image($card_deco_id, 'full', false, array( 'class' => 'destination-about_main-card-deco')) ?>
          <div class="destination-about_main-card-content">
            <h2 class="destination-about_main-card-title">
              <?= $main_card['title']; ?>
            </h2>
            <p class="destination-about_main-card-description">
              <?= $main_card['desc']; ?>
            </p>
            <a
              href="<?= esc_url($url); ?>"
              class="destination-about_main-card-link compound-avian-button compound-avian-button--lg"
            >
              <div class="compound-avian-button__content">
                <span class="compound-avian-button__content-text">
                  Start planning
                </span>
              </div>
            </a>
          </div>
        </div>
        <div class="destination-about_card-1">
          <div class="destination-about_card-img-wrapper">
            <?= wp_get_attachment_image(isset($card_left['image']['desktop']) ? $card_left['image']['desktop'] : $fallback_id, 'full', false, array( 'class' => 'destination-about_card-img')); ?>
          </div>
          <div class="destination-about_card-content">
            <h3 class="destination-about_card-title">
              <?= $card_left['title']; ?>
            </h3>
            <p class="destination-about_card-description">
              <?= $card_left['desc']; ?>
            </p>
          </div>
        </div>
        <div class="destination-about_card-2">
          <div class="destination-about_card-img-wrapper">
            <?= wp_get_attachment_image(isset($card_top_right['image']['desktop']) ? $card_top_right['image']['desktop'] : $fallback_id, 'full', false, array( 'class' => 'destination-about_card-img')); ?>
          </div>

          <div class="destination-about_card-content">
            <h3 class="destination-about_card-title">
              <?= $card_top_right['title']; ?>
            </h3>
            <p class="destination-about_card-description">
              <?= $card_top_right['desc']; ?>
            </p>
          </div>
        </div>
        <div class="destination-about_card-3">
          <div class="destination-about_card-img-wrapper">
            <?= wp_get_attachment_image(isset($card_bottom_right['image']['desktop']) ? $card_bottom_right['image']['desktop'] : $fallback_id, 'full', false, array( 'class' => 'destination-about_card-img')); ?>
          </div>
          <div class="destination-about_card-content">
            <h3 class="destination-about_card-title">
              <?= $card_bottom_right['title']; ?>
            </h3>
            <p class="destination-about_card-description">
              <?= $card_bottom_right['desc']; ?>
            </p>
          </div>
        </div>
      </div>
      <div class="destination-about_card-mb-container">
        <div class="destination-about_card-mb">
          <?= wp_get_attachment_image($card_deco_mb_id, 'full', false, array('class' => 'destination-about_card-deco-mb')) ?>
          <div class="destination-about_card-mb-content">
            <h2 class="destination-about_card-mb-title"><?= $main_card['title']; ?></h2>
            <p class="destination-about_card-mb-description"><?= $main_card['desc']; ?></p>
            <a
              href="<?= esc_url($url); ?>"
              class="destination-about_card-mb-link compound-avian-button compound-avian-button--lg"
            >
              <div class="compound-avian-button__content">
                <span class="compound-avian-button__content-text">
                  Start planning
                </span>
              </div>
            </a>
          </div>
        </div>
      </div>
      <div class="destination-about_card-slides swiper">
        <div class="swiper-wrapper">
          <div class="swiper-slide">
            <div class="destination-about_card-slide-content">
              <h3 class="destination-about_card-slide-title"><?= $card_left['title']; ?></h3>
              <p class="destination-about_card-slide-description"><?= $card_left['desc']; ?></p>
            </div>
            <div class="destination-about_card-slide-img-wrapper">
              <?= wp_get_attachment_image(isset($card_left['image']['mobile']) ? $card_left['image']['mobile'] : $fallback_id, 'full', false, array( 'class' => 'destination-about_card-slide-img')) ?>
            </div>
          </div>
          <div class="swiper-slide">
            <div class="destination-about_card-slide-content">
              <h3 class="destination-about_card-slide-title">
                <?= $card_top_right['title']; ?>
              </h3>
              <p class="destination-about_card-slide-description">
                <?= $card_top_right['desc']; ?>
              </p>
            </div>
            <div class="destination-about_card-slide-img-wrapper">
                <?= wp_get_attachment_image(isset($card_top_right['image']['mobile']) ? $card_top_right['image']['mobile']: $fallback_id, 'full', false, array( 'class' => 'destination-about_card-slide-img')) ?>
            </div>
          </div>
          <div class="swiper-slide">
            <div class="destination-about_card-slide-content">
              <h3 class="destination-about_card-slide-title">
                <?= $card_bottom_right['title']; ?>
              </h3>
              <p class="destination-about_card-slide-description">
                <?= $card_bottom_right['desc']; ?>
              </p>
            </div>
            <div class="destination-about_card-slide-img-wrapper">
              <?= wp_get_attachment_image(isset($card_bottom_right['image']['mobile']) ? $card_bottom_right['image']['mobile'] : $fallback_id, 'full', false, array( 'class' => 'destination-about_card-slide-img')) ?>
            </div>
          </div>
        </div>
        <div class="swiper-pagination"></div>
      </div>
    </section>