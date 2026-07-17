<?php
$related_query = null;
$categories      = wp_get_post_categories(get_the_ID());

if ($categories) {
      $args = array(
            'category__in'   => $categories,
            'post__not_in'   => array(get_the_ID()),
            'posts_per_page' => 8,
            'orderby'        => 'date',
            'order'          => 'DESC',
      );

      $related_query = new WP_Query($args);
}

$show_related_nav = $related_query && $related_query->post_count >= 4;
?>
<div class="blog-related-wrapper">
      <section class="related-news-section">
            <div class="related-news-header">
                  <h2 class="related-news-title">Tin tức liên quan</h2>
            </div>

            <div class="related-news-container">

                  <?php if ($show_related_nav) : ?>
                        <button class="nav-btn prev related-prev">
                              <svg width="20" height="20" viewBox="0 0 24 24" fill="none">
                                    <path d="M15 18L9 12L15 6" stroke="#680103" stroke-width="2" />
                              </svg>
                        </button>
                        <button class="nav-btn next related-next">
                              <svg width="20" height="20" viewBox="0 0 24 24" fill="none">
                                    <path d="M9 18L15 12L9 6" stroke="#680103" stroke-width="2" />
                              </svg>
                        </button>
                  <?php endif; ?>
                  <div class="swiper related-news-slider">
                        <div class="swiper-wrapper">
                              <?php
                              if ($related_query && $related_query->have_posts()) :
                                    while ($related_query->have_posts()) :
                                          $related_query->the_post();
                                          $thumbnail_id = get_post_thumbnail_id();
                                          $current_cats = get_the_category();
                                          $first_cat    = !empty($current_cats) ? $current_cats[0]->name : 'Tin tức';
                              ?>
                                          <div class="swiper-slide">
                                                <article class="related-news-card">
                                                      <a href="<?php the_permalink(); ?>" class="card-thumb">
                                                            <?php if ($thumbnail_id) : ?>
                                                                  <?= wp_get_attachment_image($thumbnail_id, 'full', false, array('loading' => 'lazy', 'decoding' => 'async', 'class' => 'zoom-image')) ?>
                                                            <?php else : ?>
                                                                  <?= wp_get_attachment_image(168, 'full', false, array('loading' => 'lazy', 'decoding' => 'async')) ?>
                                                            <?php endif; ?>
                                                            <img class="post-card__layer" src="<?= get_template_directory_uri(); ?>/assets/images/layer_image.svg" alt="Layer Overlay" />
                                                      </a>

                                                      <div class="card-content">
                                                            <div class="card-meta">
                                                                  <span class="card-tag"><?= esc_html($first_cat); ?></span>
                                                                  <span class="card-date"><?php echo get_the_date('d/m/Y'); ?></span>
                                                            </div>
                                                            <h3 class="card-title">
                                                                  <a href="<?php the_permalink(); ?>"><?php the_title(); ?></a>
                                                            </h3>
                                                      </div>
                                                </article>
                                          </div>

                              <?php
                                    endwhile;
                                    wp_reset_postdata();
                              endif;
                              ?>
                        </div>
                  </div>
            </div>
      </section>
</div>