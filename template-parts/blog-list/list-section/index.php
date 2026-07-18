<?php
$data_orther_news = get_field('orther-news');
$list_categories = get_categories(array(
      'orderby' => 'name',
      'order'   => 'ASC'
));

$cat_slug = isset($_GET['categories']) ? sanitize_text_field($_GET['categories']) : '';
$paged    = isset($_GET['paged']) ? max(1, intval($_GET['paged'])) : 1;

$blog_list_per_page = wp_is_mobile() ? 9 : 16;

$args = array(
      'post_type'      => 'post',
      'posts_per_page' => $blog_list_per_page,
      'paged'          => $paged,
      'orderby'        => 'date',
      'order'          => 'DESC',
      'post_status'    => 'publish',
);

if (!empty($cat_slug)) {
      $args['category_name'] = $cat_slug;
}

$query = new WP_Query($args);
?>

<section id="blog-list">
      <div class="heading">
            <h2 class="title">
                  <?= esc_html($data_orther_news['title'] ?? 'Tin tức khác'); ?>
            </h2>
            <div class="filter-mobile" id="openCategoryDrawer">
                  <span>
                        Chọn danh mục
                  </span>

                  <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 20 20" fill="none">
                        <path d="M2.79154 1.66675H10.2082C10.8249 1.66675 11.3332 2.17509 11.3332 2.79175V4.02507C11.3332 4.47507 11.0499 5.03342 10.7749 5.31675L8.35826 7.45008C8.02492 7.73342 7.79989 8.29174 7.79989 8.74174V11.1584C7.79989 11.4918 7.5749 11.9418 7.29156 12.1168L6.50823 12.6251C5.7749 13.0751 4.76654 12.5667 4.76654 11.6667V8.69174C4.76654 8.30008 4.54156 7.79176 4.31656 7.50842L2.18323 5.25841C1.89989 4.97508 1.67491 4.47507 1.67491 4.13341V2.84175C1.66657 2.17508 2.17488 1.66675 2.79154 1.66675Z" stroke="#F6F3EA" stroke-width="1.5" stroke-miterlimit="10" stroke-linecap="round" stroke-linejoin="round" />
                        <path d="M1.66699 10V12.5C1.66699 16.6667 3.33366 18.3334 7.50033 18.3334H12.5003C16.667 18.3334 18.3337 16.6667 18.3337 12.5V7.50004C18.3337 4.90004 17.6836 3.2667 16.1753 2.4167C15.7503 2.17504 14.9003 1.9917 14.1253 1.8667" stroke="#F6F3EA" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" />
                        <path d="M10.833 10.8333H14.9997" stroke="#F6F3EA" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" />
                        <path d="M9.16699 14.1667H15.0003" stroke="#F6F3EA" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" />
                  </svg>
            </div>
            <nav class="category-filter">
                  <button class="category-filter__back-btn btn-hover-gradient js-filter-prev" aria-label="Go back">
                        <svg width="20" height="20" viewBox="0 0 20 20" fill="none">
                              <path d="M3.55762 10.0002L8.91992 15.3616L10.2939 15.3196L8.14355 13.1692C7.4445 12.4711 6.80521 11.846 6.22461 11.2942L5.42383 10.5325L6.52832 10.5276L15.415 10.4856L15.3672 9.42407L6.44629 9.46704L5.32324 9.4729L6.13867 8.70044C6.42226 8.43175 6.72109 8.14401 7.03516 7.83716L8.02344 6.85864L10.252 4.6311L8.9707 4.58911L3.55762 10.0002Z" fill="#680103" stroke="#680103" stroke-width="0.8888" />
                        </svg>
                  </button>

                  <div class="category-filter__list">
                        <a href="<?= strtok($_SERVER["REQUEST_URI"], '?'); ?>"
                              data-slug=""
                              class="category-filter__item js-category-filter <?= empty($cat_slug) ? 'category-filter__item--active' : ''; ?>">
                              TẤT CẢ
                        </a>

                        <?php foreach ($list_categories as $item):
                              $active_class = ($cat_slug == $item->slug) ? 'category-filter__item--active' : '';
                        ?>
                              <a href="?categories=<?= $item->slug; ?>"
                                    data-slug="<?= $item->slug; ?>"
                                    class="category-filter__item js-category-filter <?= $active_class; ?>">
                                    <?= esc_html($item->name); ?>
                              </a>
                        <?php endforeach; ?>
                  </div>

                  <button class="category-filter__back-btn btn-hover-gradient js-filter-next" aria-label="Go Next">
                        <svg width="20" height="20" viewBox="0 0 20 20" fill="none">
                              <path d="M16.4424 10.0002L11.0801 15.3616L9.70605 15.3196L11.8564 13.1692C12.5555 12.4711 13.1948 11.846 13.7754 11.2942L14.5762 10.5325L13.4717 10.5276L4.58496 10.4856L4.63281 9.42407L13.5537 9.46704L14.6768 9.4729L13.8613 8.70044C13.5777 8.43175 13.2789 8.14401 12.9648 7.83716L11.9766 6.85864L9.74805 4.6311L11.0293 4.58911L16.4424 10.0002Z" fill="#680103" stroke="#680103" stroke-width="0.8888" />
                        </svg>
                  </button>
            </nav>
      </div>

      <div class="category-drawer" id="categoryDrawer">
            <div class="category-drawer__overlay" id="drawerOverlay"></div>
            <div class="category-drawer__content">
                  <div class="category-drawer__header">
                        <div class="category-drawer__title-group">
                              <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 20 20" fill="none">
                                    <path d="M2.79154 1.66675H10.2082C10.8249 1.66675 11.3332 2.17509 11.3332 2.79175V4.02507C11.3332 4.47507 11.0499 5.03342 10.7749 5.31675L8.35826 7.45008C8.02492 7.73342 7.79989 8.29174 7.79989 8.74174V11.1584C7.79989 11.4918 7.5749 11.9418 7.29156 12.1168L6.50823 12.6251C5.7749 13.0751 4.76654 12.5667 4.76654 11.6667V8.69174C4.76654 8.30008 4.54156 7.79176 4.31656 7.50842L2.18323 5.25841C1.89989 4.97508 1.67491 4.47507 1.67491 4.13341V2.84175C1.66657 2.17508 2.17488 1.66675 2.79154 1.66675Z" stroke="#680103" stroke-width="1.5" stroke-miterlimit="10" stroke-linecap="round" stroke-linejoin="round" />
                                    <path d="M1.66699 10V12.5C1.66699 16.6667 3.33366 18.3334 7.50033 18.3334H12.5003C16.667 18.3334 18.3337 16.6667 18.3337 12.5V7.50004C18.3337 4.90004 17.6836 3.2667 16.1753 2.4167C15.7503 2.17504 14.9003 1.9917 14.1253 1.8667" stroke="#680103" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" />
                                    <path d="M10.833 10.8333H14.9997" stroke="#680103" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" />
                                    <path d="M9.16699 14.1667H15.0003" stroke="#680103" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" />
                              </svg>
                              <h2 class="category-drawer__title">Chọn danh mục</h2>
                        </div>
                        <button class="category-drawer__close" id="closeDrawer" aria-label="Đóng">
                              <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none">
                                    <path d="M5.00098 5L19 18.9991" stroke="#680103" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" />
                                    <path d="M4.99996 18.9991L18.999 5" stroke="#680103" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" />
                              </svg>
                        </button>
                  </div>

                  <div class="category-drawer__list">
                        <a href="#" data-slug="" class="category-drawer__item js-category-filter">
                              <div class="category-drawer__custom-radio <?= empty($cat_slug) ? 'is-active' : ''; ?>">
                                    <svg class="check-icon" style="<?= empty($cat_slug) ? '' : 'display:none;' ?>" xmlns="http://www.w3.org/2000/svg" width="11" height="8" viewBox="0 0 11 8" fill="none">
                                          <path d="M10.3474 0.649902L3.68069 7.31657L0.650391 4.28627" stroke="white" stroke-width="1.3" stroke-linecap="round" stroke-linejoin="round" />
                                    </svg>
                              </div>
                              <span class="category-drawer__label <?= empty($cat_slug) ? 'is-active' : ''; ?>">Tất cả</span>
                        </a>

                        <?= okhub_img('icons/line-1239-2', array('class' => 'category-drawer__line')) ?>

                        <?php foreach ($list_categories as $index => $item):
                              $is_active = ($cat_slug == $item->slug);
                              $is_last = ($index === array_key_last($list_categories));
                        ?>
                              <a href="#" data-slug="<?= $item->slug; ?>" class="category-drawer__item js-category-filter">
                                    <div class="category-drawer__custom-radio <?= $is_active ? 'is-active' : ''; ?>">
                                          <svg class="check-icon" style="<?= $is_active ? '' : 'display:none;' ?>" xmlns="http://www.w3.org/2000/svg" width="11" height="8" viewBox="0 0 11 8" fill="none">
                                                <path d="M10.3474 0.649902L3.68069 7.31657L0.650391 4.28627" stroke="white" stroke-width="1.3" stroke-linecap="round" stroke-linejoin="round" />
                                          </svg>
                                    </div>
                                    <span class="category-drawer__label <?= $is_active ? 'is-active' : ''; ?>">
                                          <?= esc_html($item->name); ?>
                                    </span>
                              </a>

                              <?php if (!$is_last): ?>
                                    <?= okhub_img('icons/line-1239-2', array('class' => 'category-drawer__line')) ?>
                              <?php endif; ?>

                        <?php endforeach; ?>
                  </div>
            </div>
      </div>


      <div id="ajax-content-replace" style="position: relative; min-height: 400px;">
            <?php if ($query->have_posts()) : ?>
                  <div class="list">
                        <?php while ($query->have_posts()) : $query->the_post();
                              $current_cats = get_the_category();
                              $first_cat = !empty($current_cats) ? $current_cats[0]->name : 'Tin tức';
                              $thumbnail_id = get_post_thumbnail_id();
                        ?>
                              <div class="post-card img-zoom-on-hover">
                                    <div class="post-card__media">
                                          <a href="<?php the_permalink(); ?>">
                                                <?php if ($thumbnail_id) : ?>
                                                      <?= wp_get_attachment_image($thumbnail_id, 'full', false, array('loading' => 'lazy', 'decoding' => 'async', 'class' => 'post-card__image zoom-image')) ?>
                                                <?php else : ?>
                                                      <?= okhub_img('common/placeholder', array('class' => 'post-card__image zoom-image')) ?>
                                                <?php endif; ?>
                                                <img class="post-card__layer" src="<?= get_template_directory_uri(); ?>/assets/images/layer_image.svg" alt="Layer Overlay" />
                                          </a>
                                    </div>

                                    <div class="post-card__content">
                                          <div class="post-card__meta">
                                                <span class="post-card__tag post-card__tag--red"><?= esc_html($first_cat); ?></span>
                                                <time datetime="<?php echo get_the_date('c'); ?>" class="post-card__date"><?php echo get_the_date('d/m/Y'); ?></time>
                                          </div>

                                          <h3 class="post-card__title">
                                                <a href="<?php the_permalink(); ?>"><?php the_title(); ?></a>
                                          </h3>
                                    </div>
                              </div>
                        <?php endwhile;
                        wp_reset_postdata(); ?>
                  </div>
            <?php else : ?>
                  <p class="title__notfound">Không tìm thấy bài viết nào phù hợp.</p>
            <?php endif; ?>

            <?php if ($query->max_num_pages > 1) : ?>
                  <nav class="pagination" aria-label="Page navigation">
                        <ul class="pagination__list">
                              <?php
                              $pages = paginate_links(array(
                                    'total'        => $query->max_num_pages,
                                    'current'      => $paged,
                                    'format'       => '?paged=%#%',
                                    'add_args'     => array('categories' => $cat_slug),
                                    'prev_text'    => '<svg width="7" height="11" viewBox="0 0 7 11" fill="none"><path d="M5.70605 0.353516L0.706873 5.3527L5.70605 10.3519" stroke="#1D1D1D" /></svg>',
                                    'next_text'    => '<svg width="7" height="11" viewBox="0 0 7 11" fill="none"><path d="M0.353516 0.353516L5.3527 5.3527L0.353516 10.3519" stroke="#1D1D1D" /></svg>',
                                    'type'         => 'array',
                                    'prev_next'    => true,
                              ));

                              if (is_array($pages)) {
                                    foreach ($pages as $page) {
                                          if (strpos($page, 'prev') !== false || strpos($page, 'next') !== false) {
                                                $page = str_replace('page-numbers', 'pagination__link pagination__link--btn', $page);
                                                $page = str_replace('prev', '', $page);
                                                $page = str_replace('next', '', $page);

                                                echo '<li class="pagination__item pagination__item--control">' . $page . '</li>';
                                          } elseif (strpos($page, 'dots') !== false) {
                                                echo '<li class="pagination__item pagination__item--dots" aria-hidden="true">';
                                                echo '<span class="pagination__dot"></span><span class="pagination__dot"></span><span class="pagination__dot"></span>';
                                                echo '</li>';
                                          } elseif (strpos($page, 'current') !== false) {
                                                $page = str_replace('page-numbers current', 'pagination__link pagination__link--active', $page);
                                                echo '<li class="pagination__item">' . $page . '</li>';
                                          } else {
                                                $page = str_replace('page-numbers', 'pagination__link', $page);
                                                echo '<li class="pagination__item">' . $page . '</li>';
                                          }
                                    }
                              }
                              ?>
                        </ul>
                  </nav>
            <?php endif; ?>
      </div>
</section>