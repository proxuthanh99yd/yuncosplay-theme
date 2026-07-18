<?php
$banner_acf = get_field('banner') ?: [];
?>

<section id="banner">
  <div class="banner__container">
    <div class="banner_overlay"></div>
    <div class="banner__swiper swiper">
      <div class="swiper-wrapper">
        <?php foreach ($banner_acf as $i => $banner): ?>
          <div class="swiper-slide" data-banner-index="<?= esc_attr($i) ?>">
            <div class="banner__slide-inner" data-swiper-parallax="70%">
              <?= wp_get_attachment_image($banner['image_pc'], 'full', false, okhub_image_attrs(array('class' => 'banner-image'), $i === 0 && !IS_MOBILE ? 'lcp' : 'lazy')) ?>
              <?= wp_get_attachment_image($banner['image_mb'], 'full', false, okhub_image_attrs(array('class' => 'banner-image-mb'), $i === 0 && IS_MOBILE ? 'lcp' : 'lazy')) ?>
            </div>
          </div>
        <?php endforeach; ?>
      </div>
    </div>
  </div>
  <div class="banner__content">
    <div class="banner__content-first">
      <?php foreach ($banner_acf as $i => $banner): ?>
        <div class="banner__content-item<?= $i === 0 ? ' is-active' : '' ?>" data-banner-index="<?= esc_attr($i) ?>"
          aria-hidden="<?= $i === 0 ? 'false' : 'true' ?>">
          <h2>
            <a href="<?= esc_url(($banner['link']['url'] ?? '') ?: '#') ?>">
              <?= esc_html($banner['title'] ?? '') ?>
              <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none">
                <path
                  d="M19.8571 12L13.3317 18.5244L11.4381 18.4668L14.1647 15.7402C15.004 14.9022 15.772 14.1511 16.4694 13.4883L17.2702 12.7275L16.1647 12.7217L5.40884 12.6709L5.47427 11.2197L16.2653 11.2725L17.3883 11.2773L16.5729 10.5049C15.8914 9.85915 15.1367 9.12137 14.3083 8.29297L11.4889 5.47559L13.2711 5.41699L19.8571 12Z"
                  fill="white" stroke="white" stroke-width="0.8888" />
              </svg>
            </a>
          </h2>
          <p><?= wp_kses_post($banner['desc'] ?? '') ?></p>
        </div>
      <?php endforeach; ?>
    </div>
    <div class="banner__content-second">
      <?php foreach ($banner_acf as $i => $banner): ?>
        <a href="<?= esc_url(($banner['link']['url'] ?? '') ?: '#') ?>"
          class="banner__content-second-item<?= $i === 0 ? ' is-active' : '' ?>" data-banner-index="<?= esc_attr($i) ?>"
          aria-hidden="<?= $i === 0 ? 'false' : 'true' ?>" tabindex="<?= $i === 0 ? '0' : '-1' ?>">
          <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none">
            <path
              d="M19.8569 12L13.3315 18.5244L11.438 18.4668L14.1646 15.7402C15.0038 14.9022 15.7718 14.1511 16.4692 13.4883L17.27 12.7275L16.1646 12.7217L5.40869 12.6709L5.47412 11.2197L16.2651 11.2725L17.3882 11.2773L16.5728 10.5049C15.8912 9.85915 15.1365 9.12137 14.3081 8.29297L11.4888 5.47559L13.271 5.41699L19.8569 12Z"
              fill="#F26C59" stroke="#F26C59" stroke-width="0.8888" />
          </svg>
          <p>
            <?= esc_html($banner['link']['title'] ?? '') ?>
            <?php if (isset($banner['count']) && $banner['count'] !== '') : ?>
            <span>(<?= esc_html($banner['count']) ?>)</span>
            <?php endif; ?>
          </p>
        </a>
      <?php endforeach; ?>
      <div class="banner__pagination"></div>
    </div>
  </div>
  <div class="banner__nav-container">
    <button class="banner__nav banner__nav-prev" type="button" aria-label="Previous banner">
    </button>
    <button class="banner__nav banner__nav-next" type="button" aria-label="Next banner">
    </button>
  </div>
</section>