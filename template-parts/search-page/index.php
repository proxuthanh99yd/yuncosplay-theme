<?php
/**
 * Search Page - Main Template
 *
 * Detects whether this is a product search (post_type=product) or blog search,
 * then renders the appropriate results section.
 *
 * Figma reference: "Trang tìm kiếm sản phẩm" + "Trang tìm kiếm blog"
 */

$search_query = get_search_query();
$post_type    = isset($_GET['post_type']) ? sanitize_text_field($_GET['post_type']) : 'post';
$is_product   = ($post_type === 'product');

// Product card background image URL (for JS-rendered cards)
$background_content_id  = 9885;
$background_content_url = wp_get_attachment_image_url($background_content_id, 'full') ?: '';

// Breadcrumb text
$breadcrumb_current = $is_product ? 'Danh sách sản phẩm' : 'Danh sách tin tức';

// Page title
$page_title = 'kết quả tìm kiếm';
?>

<!-- Breadcrumb -->
<nav class="sp-breadcrumb" aria-label="Breadcrumb">
	<div class="sp-breadcrumb__inner">
		<ol class="sp-breadcrumb__list">
			<li class="sp-breadcrumb__item">
				<a href="<?= esc_url(home_url('/')); ?>" class="sp-breadcrumb__link">Trang chủ</a>
			</li>
			<li class="sp-breadcrumb__separator" aria-hidden="true"></li>
			<li class="sp-breadcrumb__item">
				<span class="sp-breadcrumb__current"><?= esc_html($breadcrumb_current); ?></span>
			</li>
		</ol>
	</div>
</nav>

<!-- Page Title -->
<section class="sp-title">
	<div class="sp-title__inner">
		<h1 class="sp-title__heading"><?= esc_html($page_title); ?></h1>
	</div>
</section>

<?php if ($is_product) : ?>
	<?php
	// === PRODUCT SEARCH RESULTS ===
	$per_page = 15; // 5 columns x 3 rows
	$paged    = 1;

	$query_args = [
		'post_type'      => 'product',
		'post_status'    => 'publish',
		'posts_per_page' => $per_page,
		'paged'          => $paged,
		'orderby'        => 'date',
		'order'          => 'DESC',
	];

	// Title-only search
	if (!empty($search_query)) {
		global $wpdb;
		$search_like = '%' . $wpdb->esc_like($search_query) . '%';

		$where_filter = function ($where, $wp_query) use ($wpdb, $search_like) {
			if (!is_object($wp_query)) return $where;
			if ($wp_query->get('post_type') !== 'product') return $where;
			return $where . $wpdb->prepare(" AND {$wpdb->posts}.post_title LIKE %s ", $search_like);
		};

		add_filter('posts_where', $where_filter, 10, 2);
	}

	$products_query = new WP_Query($query_args);

	if (isset($where_filter)) {
		remove_filter('posts_where', $where_filter, 10);
	}

	$total_pages = $products_query->max_num_pages;
	?>

	<section class="sp-results sp-results--product">
		<div class="sp-results__inner">
			<!-- Product Grid -->
			<div
				class="sp-product-grid"
				id="sp-product-grid"
				data-total-pages="<?= esc_attr($total_pages); ?>"
				data-current-page="<?= esc_attr($paged); ?>"
				data-search="<?= esc_attr($search_query); ?>"
				data-bg-url="<?= esc_attr($background_content_url); ?>"
			>
				<?php if ($products_query->have_posts()) : ?>
					<?php while ($products_query->have_posts()) : $products_query->the_post(); ?>
						<?php get_template_part('template-parts/components/product/index'); ?>
					<?php endwhile; ?>
					<?php wp_reset_postdata(); ?>
				<?php else : ?>
					<p class="sp-results__empty">Không tìm thấy sản phẩm nào.</p>
				<?php endif; ?>
			</div>

			<!-- Infinite Scroll Sentinel -->
			<div class="sp-product-sentinel" id="sp-product-sentinel"></div>

			<!-- Loading Spinner (Infinite Loading) -->
			<div class="sp-product-loader" id="sp-product-loader">
				<div class="sp-product-loader__spinner"></div>
				<span class="sp-product-loader__text">Load kiểu Infinite loading</span>
			</div>
		</div>
	</section>

<?php else : ?>
	<?php
	// === BLOG SEARCH RESULTS ===
	$per_page = 12; // 4 columns x 3 rows per page section
	$paged    = get_query_var('paged') ? get_query_var('paged') : 1;

	$blog_args = [
		'post_type'      => 'post',
		'post_status'    => 'publish',
		'posts_per_page' => $per_page,
		'paged'          => $paged,
		'orderby'        => 'date',
		'order'          => 'DESC',
	];

	// Title-only search
	if (!empty($search_query)) {
		global $wpdb;
		$search_like = '%' . $wpdb->esc_like($search_query) . '%';

		$where_filter_blog = function ($where, $wp_query) use ($wpdb, $search_like) {
			if (!is_object($wp_query)) return $where;
			if ($wp_query->get('post_type') !== 'post') return $where;
			return $where . $wpdb->prepare(" AND {$wpdb->posts}.post_title LIKE %s ", $search_like);
		};

		add_filter('posts_where', $where_filter_blog, 10, 2);
	}

	$blog_query  = new WP_Query($blog_args);

	if (isset($where_filter_blog)) {
		remove_filter('posts_where', $where_filter_blog, 10);
	}

	$total_pages  = $blog_query->max_num_pages;
	$total_posts  = $blog_query->found_posts;
	?>

	<section class="sp-results sp-results--blog">
		<div class="sp-results__inner">
			<!-- Blog Grid -->
			<div class="sp-blog-grid" id="sp-blog-grid">
				<?php if ($blog_query->have_posts()) : ?>
					<?php while ($blog_query->have_posts()) : $blog_query->the_post(); ?>
						<?php get_template_part('template-parts/components/blog-item-v2/index'); ?>
					<?php endwhile; ?>
					<?php wp_reset_postdata(); ?>
				<?php else : ?>
					<p class="sp-results__empty">Không tìm thấy bài viết nào.</p>
				<?php endif; ?>
			</div>

			<!-- Pagination -->
			<?php if ($total_pages > 1) : ?>
				<nav class="sp-pagination" aria-label="Pagination">
					<?php if ($paged > 1) : ?>
						<a href="<?= esc_url(add_query_arg('paged', $paged - 1)); ?>" class="sp-pagination__arrow sp-pagination__arrow--prev" aria-label="Previous page">
							<svg width="10" height="10" viewBox="0 0 10 10" fill="none" xmlns="http://www.w3.org/2000/svg">
								<path d="M6.25 7.5L3.75 5L6.25 2.5" stroke="#1D1D1D" stroke-width="1.2" stroke-linecap="round" stroke-linejoin="round"/>
							</svg>
						</a>
					<?php endif; ?>

					<div class="sp-pagination__pages">
						<?php
						// Show page 1
						$page_class = ($paged === 1) ? 'sp-pagination__page sp-pagination__page--active' : 'sp-pagination__page';
						echo '<a href="' . esc_url(add_query_arg('paged', 1)) . '" class="' . $page_class . '">1</a>';

						if ($total_pages > 1) {
							// Show page 2
							$page_class = ($paged === 2) ? 'sp-pagination__page sp-pagination__page--active' : 'sp-pagination__page';
							echo '<a href="' . esc_url(add_query_arg('paged', 2)) . '" class="' . $page_class . '">2</a>';
						}

						if ($total_pages > 3) {
							// Ellipsis
							echo '<span class="sp-pagination__ellipsis"><span></span><span></span><span></span></span>';
						}

						if ($total_pages > 2) {
							// Show last page
							$page_class = ($paged === $total_pages) ? 'sp-pagination__page sp-pagination__page--active' : 'sp-pagination__page';
							echo '<a href="' . esc_url(add_query_arg('paged', $total_pages)) . '" class="' . $page_class . '">' . $total_pages . '</a>';
						}
						?>
					</div>

					<?php if ($paged < $total_pages) : ?>
						<a href="<?= esc_url(add_query_arg('paged', $paged + 1)); ?>" class="sp-pagination__arrow sp-pagination__arrow--next" aria-label="Next page">
							<svg width="10" height="10" viewBox="0 0 10 10" fill="none" xmlns="http://www.w3.org/2000/svg">
								<path d="M3.75 2.5L6.25 5L3.75 7.5" stroke="#1D1D1D" stroke-width="1.2" stroke-linecap="round" stroke-linejoin="round"/>
							</svg>
						</a>
					<?php endif; ?>
				</nav>
			<?php endif; ?>
		</div>
	</section>

<?php endif; ?>
