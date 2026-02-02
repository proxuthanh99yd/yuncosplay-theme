<?php
$term = get_queried_object();
$insider = get_field('holiday_coastal-inspirations', $term);
$title = isset($insider['title']) ? $insider['title'] : '';
$desc = isset($insider['desc']) ? $insider['desc'] : '';

$args = [
  'post_type'      => 'post',
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

$blog_query = new WP_Query($args);


?>

<section id="coastal-inspirations" class="destination-insider">
  <div class="destination-insider_container">
    <div class="destination-insider_header">
      <p class="destination-insider_desc"><?= esc_html($desc) ?></p>
      <h2 class="destination-insider_title"><?= esc_html($title) ?></h2>
    </div>
    <div class="destination-insider_content">
      <!-- Mobile -->
      <div class="destination-insider_cards-mb">
        <?php if ($blog_query->have_posts()): ?>
            <?php while ($blog_query->have_posts()): $blog_query->the_post() ?>
                <?php
                  $blog_id = get_the_ID();
                  $thumb_id = get_post_thumbnail_id($blog_id);
                  $permalink = get_permalink($blog_id);
                  $blog_title = get_the_title($blog_id);
                  $categories = get_the_category($blog_id);
                  $category_name = !empty($categories) ? $categories[0]->name : '';
                  $content = get_post_field('post_content', $post_id);
                  $word_count = str_word_count(wp_strip_all_tags($content));
                  $reading_time = max(1, ceil($word_count / 200));
                ?>
                <a href="<?= esc_url($permalink) ?>" class="destination-insider_card">
                  <?= wp_get_attachment_image($thumb_id, 'full', false, array( 'class' => 'destination-insider_card-img')) ?>
                  <div class="destination-insider_card-overlay"></div>
                  <div class="destination-insider_card-content">
                    <div class="destination-insider_card-top">
                      <span class="destination-insider_card-type"><?= $category_name ?></span>
                      <svg xmlns="http://www.w3.org/2000/svg" width="7" height="7" viewBox="0 0 7 7" fill="none"
                        class="destination-insider_card-icon">
                        <path
                          d="M0 3.5C1.55555 3.11111 3.11111 1.55556 3.5 0C3.88889 1.55556 5.44445 3.11111 7 3.5C5.44445 3.88889 3.88889 5.44444 3.5 7.00002C3.11111 5.44444 1.55555 3.88889 0 3.5Z"
                          fill="white" />
                      </svg>
                      <span class="destination-insider_card-duration"> <?= esc_html($reading_time); ?> min read </span>
                    </div>
                    <h3 class="destination-insider_card-title">
                      <?= $blog_title ?>
                    </h3>
                  </div>
                </a>
            <?php endwhile; ?>
            <?php wp_reset_postdata(); ?>
        <?php endif; ?>
      </div>

      <!-- Desktop -->
      <div class="destination-insider_cards swiper">
        <div class="swiper-wrapper">
            <?php if ($blog_query->have_posts()): ?>
                <?php while ($blog_query->have_posts()): $blog_query->the_post() ?>
                    <?php
                        $blog_id = get_the_ID();
                        $thumb_id = get_post_thumbnail_id($blog_id);
                        $permalink = get_permalink($blog_id);
                        $blog_title = get_the_title($blog_id);
                        $categories = get_the_category($blog_id);
                        $category_name = !empty($categories) ? $categories[0]->name : '';
                        $content = get_post_field('post_content', $post_id);
                        $word_count = str_word_count(wp_strip_all_tags($content));
                        $reading_time = max(1, ceil($word_count / 200));
                    ?>
                    <div class="swiper-slide">
                        <a href="<?= esc_url($permalink) ?>" class="destination-insider_card">
                          <?= wp_get_attachment_image($thumb_id, 'full', false, array( 'class' => 'destination-insider_card-img')) ?>
                          <div class="destination-insider_card-overlay"></div>
                          <div class="destination-insider_card-content">
                            <div class="destination-insider_card-top">
                              <span class="destination-insider_card-type"><?= $category_name ?></span>
                              <svg xmlns="http://www.w3.org/2000/svg" width="7" height="7" viewBox="0 0 7 7" fill="none"
                                class="destination-insider_card-icon">
                                <path
                                  d="M0 3.5C1.55555 3.11111 3.11111 1.55556 3.5 0C3.88889 1.55556 5.44445 3.11111 7 3.5C5.44445 3.88889 3.88889 5.44444 3.5 7.00002C3.11111 5.44444 1.55555 3.88889 0 3.5Z"
                                  fill="white" />
                              </svg>
                              <span class="destination-insider_card-duration"> <?= esc_html($reading_time); ?> min read </span>
                            </div>
                            <h3 class="destination-insider_card-title">
                              <span class='title-normal'>
                                <?= $blog_title ?>
                              </span>
                              <span class='title-hover'>
                                <?= $blog_title ?>
                              </span>
                            </h3>
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
  </div>
</section>