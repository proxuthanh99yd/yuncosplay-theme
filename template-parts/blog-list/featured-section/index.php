<?php
$title_section = get_field('title');
$featured_news = get_field('featured_news_list');
?>



<section id="featured-new">
      <div class="featured-desktop-wrapper">
            <div class="heading">
                  <h2 class="title"><?= esc_html($title_section); ?></h2>
                  <div class="swiper-pagination pagination-desktop"></div>
            </div>

            <div class="box-swiper">
                  <div class="swiper swiper-desktop">
                        <div class="swiper-wrapper">
                              <?php if ($featured_news): foreach ($featured_news as $item):
                                          $post_id = $item->ID;
                                          $title   = get_the_title($post_id);
                                          $link    = get_the_permalink($post_id);
                                          $date    = get_the_date('d/m/Y', $post_id);
                                          $categories = get_the_category($post_id);
                                          $cat_name = !empty($categories) ? $categories[0]->name : 'Tin tức';
                              ?>
                                          <div class="card-swiper swiper-slide img-zoom-on-hover">
                                                <?php if (has_post_thumbnail($post_id)) : ?>
                                                      <?= get_the_post_thumbnail($post_id, 'full', array('class' => 'card_image zoom-image')) ?>
                                                <?php else : ?>
                                                      <img src="<?= get_template_directory_uri() ?>/assets/images/default.jpg" class="card_image" alt="<?= esc_attr($title) ?>">
                                                <?php endif; ?>
                                                <img class="post-card__layer" src="<?= get_template_directory_uri(); ?>/assets/images/layer_image.svg" alt="Layer Overlay" />

                                                <div class="card_info">
                                                      <div class="heading-info">
                                                            <div class="info">
                                                                  <span class="tag"><?= esc_html($cat_name) ?></span>
                                                                  <span class="date"><?= $date ?></span>
                                                            </div>
                                                            <a href="<?= esc_url($link) ?>" class="view-detail">
                                                                  <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none">
                                                                        <path d="M19.8574 12L13.332 18.5244L11.4385 18.4668L14.165 15.7402C15.0043 14.9022 15.7723 14.1511 16.4697 13.4883L17.2705 12.7275L16.165 12.7217L5.40918 12.6709L5.47461 11.2197L16.2656 11.2725L17.3887 11.2773L16.5732 10.5049C15.8917 9.85915 15.137 9.12137 14.3086 8.29297L11.4893 5.47559L13.2715 5.41699L19.8574 12Z" fill="#F26C59" stroke="#F26C59" stroke-width="0.8888" />
                                                                  </svg>
                                                                  <span>Chi tiết</span>
                                                            </a>
                                                      </div>
                                                      <?= okhub_img('icons/line-1239-1', array('class' => 'card_info-line')) ?>
                                                      <h3 class="description">
                                                            <a href="<?= esc_url($link) ?>"><?= wp_trim_words($title, 15, '...') ?></a>
                                                      </h3>
                                                      <div class="layer-bg"></div>
                                                </div>
                                          </div>
                              <?php endforeach;
                              endif; ?>
                        </div>
                  </div>
                  <span class="prev nav-desktop btn-hover-gradient">
                        <svg width="20" height="20" viewBox="0 0 20 20" fill="none">
                              <path d="M3.55762 10.0002L8.91992 15.3616L10.2939 15.3196L8.14355 13.1692C7.4445 12.4711 6.80521 11.846 6.22461 11.2942L5.42383 10.5325L6.52832 10.5276L15.415 10.4856L15.3672 9.42407L6.44629 9.46704L5.32324 9.4729L6.13867 8.70044C6.42226 8.43175 6.72109 8.14401 7.03516 7.83716L8.02344 6.85864L10.252 4.6311L8.9707 4.58911L3.55762 10.0002Z" fill="#680103" stroke="#680103" stroke-width="0.8888" />
                        </svg>
                  </span>
                  <span class="next nav-desktop btn-hover-gradient">
                        <svg width="20" height="20" viewBox="0 0 20 20" fill="none">
                              <path d="M16.4424 10.0002L11.0801 15.3616L9.70605 15.3196L11.8564 13.1692C12.5555 12.4711 13.1948 11.846 13.7754 11.2942L14.5762 10.5325L13.4717 10.5276L4.58496 10.4856L4.63281 9.42407L13.5537 9.46704L14.6768 9.4729L13.8613 8.70044C13.5777 8.43175 13.2789 8.14401 12.9648 7.83716L11.9766 6.85864L9.74805 4.6311L11.0293 4.58911L16.4424 10.0002Z" fill="#680103" stroke="#680103" stroke-width="0.8888" />
                        </svg>
                  </span>
            </div>
      </div>

      <div class="featured-mobile-wrapper">
            <div class="heading">
                  <h2 class="title"><?= esc_html($title_section); ?></h2>
            </div>
            <div class="box-swiper">
                  <div class="swiper swiper-mobile">
                        <div class="swiper-pagination pagination-mobile"></div>
                        <div class="swiper-wrapper">
                              <?php if ($featured_news): foreach ($featured_news as $item):
                                          $post_id = $item->ID;
                                          $title   = get_the_title($post_id);
                                          $link    = get_the_permalink($post_id);
                                          $date    = get_the_date('d/m/Y', $post_id);
                                          $categories = get_the_category($post_id);
                                          $cat_name = !empty($categories) ? $categories[0]->name : 'Tin tức';
                              ?>
                                          <div class="card-swiper swiper-slide img-zoom-on-hover">
                                                <div class="card_image_wrapper">
                                                      <?php if (has_post_thumbnail($post_id)) : ?>
                                                            <?= get_the_post_thumbnail($post_id, 'full', array('class' => 'card_image zoom-image')) ?>
                                                      <?php endif; ?>
                                                      <img class="post-card__layer" src="<?= get_template_directory_uri(); ?>/assets/images/layer_image.svg" alt="Layer Overlay" />
                                                </div>
                                                <div class="card_info">
                                                      <div class="heading-info">
                                                            <div class="info">
                                                                  <span class="tag"><?= esc_html($cat_name) ?></span>
                                                                  <span class="date"><?= $date ?></span>
                                                            </div>
                                                            <a href="<?= esc_url($link) ?>" class="view-detail">
                                                                  <svg xmlns="http://www.w3.org/2000/svg" width="17" height="17" viewBox="0 0 17 17" fill="none">
                                                                        <path d="M13.8828 8.49951L9.39062 12.9897L8.40527 12.9595L10.125 11.2407C10.7189 10.6477 11.2619 10.1164 11.7549 9.64795L12.5557 8.88721L11.4512 8.88135L3.9668 8.84619L4.00098 8.07764L11.5205 8.11377L12.6436 8.11963L11.8281 7.34717C11.5873 7.11896 11.3333 6.87449 11.0664 6.61377L10.2266 5.78271L8.44141 3.99756L9.34863 3.96826L13.8828 8.49951Z" fill="#680103" stroke="#680103" stroke-width="0.8888" />
                                                                  </svg>
                                                                  <span>Chi tiết</span>
                                                            </a>
                                                      </div>
                                                      <?= okhub_img('icons/line-1239-2', array('class' => 'card_info__line')) ?>
                                                      <h3 class="description">
                                                            <a href="<?= esc_url($link) ?>"><?= wp_trim_words($title, 12, '...') ?></a>
                                                      </h3>
                                                </div>
                                          </div>
                              <?php endforeach;
                              endif; ?>
                        </div>
                  </div>
            </div>
      </div>
</section>