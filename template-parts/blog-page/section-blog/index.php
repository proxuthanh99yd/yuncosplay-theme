<?php
$category = get_queried_object();
$title_pc = get_field('title_pc', $category);
$title_mb = get_field('title_mobile', $category);
$image_no_result_id = 2003;
$bakgroud_desktop_id = 2046;
$background_category_blog_top_id = 2049;

$categories = get_categories([
  'taxonomy'   => 'category',
  'hide_empty' => false,
]);

$paged = max(1, get_query_var('paged'));
$posts_per_page = wp_is_mobile() ? 6 : 11;

$query_args = [
  'post_type'      => 'post',
  'posts_per_page' => $posts_per_page,
  'paged'          => $paged,
  'post_status'    => 'publish',
  'orderby'        => 'modified',
  'order'          => 'DESC',
];

if (is_category()) {
  $category_id = (int) get_queried_object_id();
  if ($category_id) {
    $query_args['cat'] = $category_id;
  }
}

$query = new WP_Query($query_args);

$total_pages  = max(1, (int) $query->max_num_pages);
$total_posts  = (int) $query->found_posts;
$progress   = min(100, max(0, ($paged / $total_pages) * 100));
$prev_page  = max(1, $paged - 1);
$next_page  = min($total_pages, $paged + 1);

$pagination_base_url = get_pagenum_link(1);
$pagination_prev_url = $prev_page > 1 ? add_query_arg('paged', $prev_page, $pagination_base_url) : $pagination_base_url;
$pagination_next_url = $next_page > 1 ? add_query_arg('paged', $next_page, $pagination_base_url) : $pagination_base_url;

$endpoint = home_url('/wp-json/api/v1/get-all/post');
?>

<section
  class="blog-section"
  data-blog-section
  data-endpoint="<?= esc_url($endpoint) ?>"
  data-page="<?= esc_attr($paged) ?>"
  data-limit="<?= esc_attr($posts_per_page) ?>"
  data-total="<?= esc_attr($total_posts) ?>"
  data-total-pages="<?= esc_attr($total_pages) ?>"
  data-category="<?= esc_attr(is_category() ? (get_queried_object()->slug ?? '') : '') ?>">
  <?= wp_get_attachment_image(
    $background_category_blog_top_id,
    'full',
    false,
    ['class' => 'blog-background-top-left--mobile']
  ) ?>

  <?= wp_get_attachment_image($bakgroud_desktop_id, 'full', false, ['class' => 'blog-background--desktop']) ?>

  <div class="blog-container">

    <!-- HEADER -->
    <div class="blog-container_header--desktop">
      <?php if (!empty($title_pc)) : ?>
        <h1 class="blog-container_header-title"><?= esc_html($title_pc) ?></h1>
      <?php endif; ?>
    </div>

    <div class="blog-container_header--mobile">
      <?php if (!empty($title_mb)) : ?>
        <h1 class="blog-container_header-title"><?= esc_html($title_mb) ?></h1>
      <?php endif; ?>
      <?php if (!empty($categories)) : ?>
        <div class="blog-container_header-tag">
          <?php foreach ($categories as $category) : ?>
            <?php $is_active = is_category() && $category->term_id === get_queried_object_id(); ?>
            <a href="<?= esc_url(get_term_link($category)) ?>" class="header-tag_item<?= $is_active ? ' active' : '' ?>" data-slug="<?= esc_attr($category->slug) ?>">
              <span class="header-tag_item-title"><?= esc_html($category->name) ?></span>
            </a>
          <?php endforeach; ?>
        </div>
      <?php endif; ?>
    </div>

    <!-- BLOG GRID -->
    <div class="blog-container_main">
      <div class="blog-container_main-grid" data-blog-grid>

        <?php if ($query->have_posts()) : ?>
          <?php
          $index = 0;
          $count = $query->post_count;
          ?>

          <?php while ($query->have_posts()) : $query->the_post(); ?>

            <?php
            /* UI layout rule (KHÔNG phụ thuộc data) */
            $layout = 'normal';

            /* 1 post → xl */
            if ($count === 1 && $index === 0) {
              $layout = 'xl';
            }

            /* 0 luôn là lg */
            if ($index === 0) {
              $layout = 'lg';
            }

            /* lg phụ thêm cho index 3 nếu đủ item */
            if ($count >= 4 && $index === 3) {
              $layout = 'lg';
            }

            /* xl chỉ dành cho item cuối khi đủ nhiều */
            if ($count > 4 && $index === $count - 1) {
              $layout = 'xl';
            }

            ?>

            <?php
            get_template_part(
              'template-parts/blog-page/section-blog/blog-card-item',
              null,
              [
                'post_id' => get_the_ID(),
                'layout'  => $layout,
              ]
            );
            ?>

            <?php $index++; ?>
          <?php endwhile; ?>
          <?php wp_reset_postdata(); ?>
        <?php else: ?>
          <div class="no-result">
            <div class="no-result__content">
              <?= wp_get_attachment_image($image_no_result_id, 'full', false, ['class' => 'no-result__image']) ?>
              <p class="no-result__text">No suitable news available</p>
              <button class="no-result__button compound-avian-button">
                <div class="compound-avian-button__content">
                  <span class="no-result__button-text compound-avian-button__content-text">
                    See other news
                  </span>
                </div>
              </button>
            </div>
          </div>
        <?php endif; ?>
      </div>

      <template data-blog-skeleton-template>
        <?php
        for ($i = 0; $i < $posts_per_page; $i++) {
          $skeleton_layout = 'normal';
          if ($i === 0 || $i === 3) {
            $skeleton_layout = 'lg';
          }
          if ($i === $posts_per_page - 1) {
            $skeleton_layout = 'xl';
          }

          $skeleton_layout_class = match ($skeleton_layout) {
            'lg'  => 'blog-card--lg',
            'xl' => 'blog-card--xl',
            default => '',
          };
        ?>
          <article class="blog-card blog-card--skeleton <?php echo esc_attr($skeleton_layout_class); ?>" aria-hidden="true">
            <div class="blog-card__image">
              <div class="blog-skeleton__media"></div>
            </div>
            <div class="blog-card__content">
              <div class="blog-skeleton__title"></div>
              <div class="blog-card__meta">
                <span class="blog-skeleton__meta"></span>
                <svg xmlns="http://www.w3.org/2000/svg" width="7" height="7" viewBox="0 0 7 7" fill="none">
                  <path d="M0 3.5C1.55555 3.11111 3.11111 1.55556 3.5 0C3.88889 1.55556 5.44445 3.11111 7 3.5C5.44445 3.88889 3.88889 5.44444 3.5 7.00002C3.11111 5.44444 1.55555 3.88889 0 3.5Z" fill="currentColor" />
                </svg>
                <span class="blog-skeleton__meta"></span>
              </div>
            </div>
          </article>
        <?php } ?>
      </template>

      <template data-blog-card-template>
        <article>
          <a href="#" class="blog-card">
            <div class="blog-card__image">
              <img src="" alt="" loading="lazy" />
            </div>

            <div class="blog-card__content">
              <h3 class="blog-card__title"></h3>

              <div class="blog-card__meta">
                <span data-blog-card-category></span>
                <svg xmlns="http://www.w3.org/2000/svg" width="7" height="7" viewBox="0 0 7 7" fill="none">
                  <path d="M0 3.5C1.55555 3.11111 3.11111 1.55556 3.5 0C3.88889 1.55556 5.44445 3.11111 7 3.5C5.44445 3.88889 3.88889 5.44444 3.5 7.00002C3.11111 5.44444 1.55555 3.88889 0 3.5Z" fill="#630F3F" />
                </svg>
                <span data-blog-card-reading-time></span>
              </div>
            </div>
          </a>
        </article>
      </template>

      <?php if ($total_pages > 1) : ?>
        <nav class="pagination" aria-label="Blog pagination" data-blog-pagination>
          <p class="pagination__text">
            You've viewed <?= min($paged * $posts_per_page, $total_posts) ?>
            of <?= $total_posts ?> articles
          </p>

          <div class="pagination__bar">
            <a href="<?= esc_url($pagination_prev_url) ?>" rel="prev" class="pagination__prev <?= $paged <= 1 ? 'is-disabled' : '' ?>" data-blog-page-link>
              <svg xmlns="http://www.w3.org/2000/svg" width="22" height="22" viewBox="0 0 22 22" fill="none">
                <path d="M13.4709 17.8872L7.61623 12.0326C6.9248 11.3411 6.9248 10.2097 7.61623 9.51827L13.4709 3.66357" stroke="#292D32" stroke-width="1.5" stroke-miterlimit="10" stroke-linecap="round" stroke-linejoin="round" />
              </svg>
            </a>

            <div class="pagination__progress">
              <div class="pagination__progress-inner" style="width: <?= esc_attr($progress) ?>%"></div>
            </div>

            <a href="<?= esc_url($pagination_next_url) ?>" rel="next" class="pagination__next <?= $paged >= $total_pages ? 'is-disabled' : '' ?>" data-blog-page-link>
              <svg xmlns="http://www.w3.org/2000/svg" width="22" height="22" viewBox="0 0 22 22" fill="none">
                <path d="M8 17.8872L13.8547 12.0326C14.5461 11.3411 14.5461 10.2097 13.8547 9.51827L8 3.66357" stroke="#292D32" stroke-width="1.34694" stroke-miterlimit="10" stroke-linecap="round" stroke-linejoin="round" />
              </svg>
            </a>
          </div>
        </nav>
      <?php endif; ?>
    </div>

  </div>
</section>