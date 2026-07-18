<?php
$related_blog_acf = get_field("home_related_blog") ?: [];
$subtitle = $related_blog_acf['subtitle'] ?? '';
$title = $related_blog_acf['title'] ?? '';
$desc = $related_blog_acf['desc'] ?? '';
$link = $related_blog_acf['link'] ?? [];
$link_url = is_array($link) ? ($link['url'] ?? '#') : ($link ?: '#');
$link_text = is_array($link) ? ($link['title'] ?? '') : '';
// Ảnh news nền + mũi tên → file tĩnh theme (okhub_img).
$news_key = wp_is_mobile() ? 'blog/news-mobile' : 'blog/news-desktop';
?>

<section id="related-blog">
  <div class="related-blog__container">
    <div class="related-blog__image-wrapper">
      <?= okhub_img($news_key, array('class' => 'related-blog__image')) ?>
      <div class="related-blog__image-content">
        <h3 class="related-blog__image-content-subtitle">
          <?= $subtitle ?>
        </h3>
        <h2 class="related-blog__image-content-title">
          <?= $title ?>
        </h2>
        <p class="related-blog__image-content-desc">
          <?= $desc ?>
        </p>
        <div class="animated-btn">
          <div class="animated-btn-wrapper">
            <a href="<?= esc_url($link_url) ?>" class="animated-btn__content-hidden">
              <div class="animated-btn__content-hidden-text"><?= $link_text ?></div>
              <span class="animated-btn__content-hidden-icon">
                <?= okhub_img('icons/arrow', array('class' => 'animated-btn__icon')) ?>
              </span>
            </a>
            <a href="<?= esc_url($link_url) ?>" class="animated-btn__content-visible">
              <div class="animated-btn__content-visible-text">
                <?= $link_text ?>
              </div>
              <span class="animated-btn__content-visible-icon">
                <?= okhub_img('icons/arrow', array('class' => 'animated-btn__icon')) ?>
              </span>
            </a>
          </div>
        </div>
      </div>
    </div>
    <div class="related-blog__post">
      <?php
      $latest_posts = new WP_Query([
        'post_type' => 'post',
        'posts_per_page' => 4,
        'post_status' => 'publish',
        'orderby' => 'date',
        'order' => 'DESC',
      ]);
      ?>
      <?php if ($latest_posts->have_posts()): ?>
        <div class="related-blog__progress">
          <?php for ($i = 0; $i < $latest_posts->post_count; $i++): ?>
            <div class="related-blog__progress-bar<?= $i === 0 ? ' is-active' : '' ?>">
              <div class="related-blog__progress-fill"></div>
            </div>
          <?php endfor; ?>
        </div>
        <div class="related-blog__swiper swiper">
          <div class="swiper-wrapper">
            <?php while ($latest_posts->have_posts()):
              $latest_posts->the_post(); ?>
              <div class="swiper-slide">
                <div class="related-blog__slide">
                  <?php if (has_post_thumbnail()): ?>
                    <?= get_the_post_thumbnail(get_the_ID(), 'large', [
                      'class' => 'related-blog__slide-image',
                      'loading' => 'lazy',
                      'decoding' => 'async',
                    ]) ?>
                  <?php endif; ?>
                  <div class="related-blog__slide-overlay"></div>
                  <div class="related-blog__slide-content">
                    <?php
                    $categories = get_the_category();
                    if (!empty($categories)):
                      ?>
                      <span class="related-blog__slide-category">
                        <?= esc_html($categories[0]->name) ?>
                      </span>
                    <?php endif; ?>
                    <h3 class="related-blog__slide-title">
                      <a href="<?= esc_url(get_permalink()) ?>"><?= get_the_title() ?></a>
                    </h3>
                  </div>
                  <a href="<?= esc_url(get_permalink()) ?>" class="related-blog__slide-cta">
                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none">
                      <path
                        d="M19.8572 12L13.3318 18.5244L11.4382 18.4668L14.1648 15.7402C15.004 14.9022 15.7721 14.1511 16.4695 13.4883L17.2703 12.7275L16.1648 12.7217L5.40894 12.6709L5.47437 11.2197L16.2654 11.2725L17.3884 11.2773L16.573 10.5049C15.8915 9.85915 15.1368 9.12137 14.3083 8.29297L11.489 5.47559L13.2712 5.41699L19.8572 12Z"
                        fill="#F26C59" stroke="#F26C59" stroke-width="0.8888" />
                    </svg>
                    <span class="related-blog__slide-cta-text">Chi tiết</span>
                  </a>
                </div>
              </div>
            <?php endwhile; ?>
          </div>
        </div>
        <div class="related-blog__nav">
          <button class="related-blog__nav-btn related-blog__nav-prev" type="button" aria-label="Previous slide">
            <svg width="40" height="40" viewBox="0 0 40 40" fill="none" xmlns="http://www.w3.org/2000/svg">
              <rect x="0.5" y="0.5" width="39" height="39" rx="19.5" stroke="white" />
              <path
                d="M26.4424 20.0002L21.0801 25.3616L19.7061 25.3196L21.8564 23.1692C22.5555 22.4711 23.1948 21.846 23.7754 21.2942L24.5762 20.5325L23.4717 20.5276L14.585 20.4856L14.6328 19.4241L23.5537 19.467L24.6768 19.4729L23.8613 18.7004C23.5777 18.4318 23.2789 18.144 22.9648 17.8372L21.9766 16.8586L19.748 14.6311L21.0293 14.5891L26.4424 20.0002Z"
                fill="white" stroke="white" stroke-width="0.8888" />
            </svg>

            <svg class="related-blog__nav-btn-icon-hover" width="40" height="40" viewBox="0 0 40 40" fill="none"
              xmlns="http://www.w3.org/2000/svg">
              <rect width="40" height="40" rx="20" fill="url(#paint0_radial_92_1000)" />
              <path
                d="M26.4424 20L21.0801 25.3613L19.7061 25.3193L21.8564 23.1689C22.5555 22.4709 23.1948 21.8457 23.7754 21.2939L24.5762 20.5322L23.4717 20.5273L14.585 20.4854L14.6328 19.4238L23.5537 19.4668L24.6768 19.4727L23.8613 18.7002C23.5777 18.4315 23.2789 18.1438 22.9648 17.8369L21.9766 16.8584L19.748 14.6309L21.0293 14.5889L26.4424 20Z"
                fill="white" stroke="white" stroke-width="0.8888" />
              <defs>
                <radialGradient id="paint0_radial_92_1000" cx="0" cy="0" r="1" gradientUnits="userSpaceOnUse"
                  gradientTransform="translate(20 20) scale(20 67.8431)">
                  <stop offset="0.465392" stop-color="#CB5140" />
                  <stop offset="0.910144" stop-color="#D8A061" />
                </radialGradient>
              </defs>
            </svg>


          </button>
          <button class="related-blog__nav-btn related-blog__nav-next" type="button" aria-label="Next slide">
            <svg width="40" height="40" viewBox="0 0 40 40" fill="none" xmlns="http://www.w3.org/2000/svg">
              <rect x="0.5" y="0.5" width="39" height="39" rx="19.5" stroke="white" />
              <path
                d="M26.4424 20.0002L21.0801 25.3616L19.7061 25.3196L21.8564 23.1692C22.5555 22.4711 23.1948 21.846 23.7754 21.2942L24.5762 20.5325L23.4717 20.5276L14.585 20.4856L14.6328 19.4241L23.5537 19.467L24.6768 19.4729L23.8613 18.7004C23.5777 18.4318 23.2789 18.144 22.9648 17.8372L21.9766 16.8586L19.748 14.6311L21.0293 14.5891L26.4424 20.0002Z"
                fill="white" stroke="white" stroke-width="0.8888" />
            </svg>

            <svg class="related-blog__nav-btn-icon-hover" width="40" height="40" viewBox="0 0 40 40" fill="none"
              xmlns="http://www.w3.org/2000/svg">
              <rect width="40" height="40" rx="20" fill="url(#paint0_radial_92_1000)" />
              <path
                d="M26.4424 20L21.0801 25.3613L19.7061 25.3193L21.8564 23.1689C22.5555 22.4709 23.1948 21.8457 23.7754 21.2939L24.5762 20.5322L23.4717 20.5273L14.585 20.4854L14.6328 19.4238L23.5537 19.4668L24.6768 19.4727L23.8613 18.7002C23.5777 18.4315 23.2789 18.1438 22.9648 17.8369L21.9766 16.8584L19.748 14.6309L21.0293 14.5889L26.4424 20Z"
                fill="white" stroke="white" stroke-width="0.8888" />
              <defs>
                <radialGradient id="paint0_radial_92_1000" cx="0" cy="0" r="1" gradientUnits="userSpaceOnUse"
                  gradientTransform="translate(20 20) scale(20 67.8431)">
                  <stop offset="0.465392" stop-color="#CB5140" />
                  <stop offset="0.910144" stop-color="#D8A061" />
                </radialGradient>
              </defs>
            </svg>

          </button>
        </div>
      <?php endif; ?>
      <?php wp_reset_postdata(); ?>
    </div>
  </div>
</section>