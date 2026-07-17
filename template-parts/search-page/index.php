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

// Blog card overlay (same as blog-item-v2)
$blog_overlay_id  = 9833;
$blog_overlay_url = wp_get_attachment_image_url($blog_overlay_id, 'full') ?: '';

// Breadcrumb text
$breadcrumb_current = $is_product ? 'Danh sách sản phẩm' : 'Danh sách tin tức';

// Page title
$page_title = 'kết quả tìm kiếm';
?>

<!-- Breadcrumb -->
<nav class="sp-breadcrumb <?= $is_product ? 'product-page' : 'blog-page'; ?>" aria-label="Breadcrumb">
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
<section class="sp-title <?= $is_product ? 'product-page' : 'blog-page'; ?>">
	<div class="sp-title__inner">
		<h1 class="sp-title__heading"><?= esc_html($page_title); ?></h1>
	</div>

</section>

<section class="sp-tabs">
	<div class="sp-tab <?= $is_product ? 'active' : '' ?>" data-value="product">Sản phẩm liên quan</div>
	<div class="sp-tab <?= $is_product ? '' : 'active' ?>" data-value="blog">Tin tức liên quan</div>
</section>


<div class="sp-results-wrapper" data-type="<?= $is_product ? 'product' : 'blog'; ?>">
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
			<div
				class="sp-product-grid"
				id="sp-product-grid"
				data-total-pages="<?= esc_attr($total_pages); ?>"
				data-current-page="<?= esc_attr($paged); ?>"
				data-search="<?= esc_attr($search_query); ?>"
				data-limit="<?= esc_attr((string) $per_page); ?>"
				data-bg-url="<?= esc_attr($background_content_url); ?>">
				<?php if ($products_query->have_posts()) : ?>
					<?php while ($products_query->have_posts()) : $products_query->the_post(); ?>
						<?php get_template_part('template-parts/components/product/index'); ?>
					<?php endwhile; ?>
					<?php wp_reset_postdata(); ?>
				<?php else : ?>
					<p class="sp-results__empty">Không tìm thấy sản phẩm nào.</p>
				<?php endif; ?>
			</div>

			<!--Infinite Scroll Sentinel -->
			<div class="sp-product-sentinel" id="sp-product-sentinel"></div>

			<!--Loading Spinner (Infinite Loading) -->
			<div class="sp-product-loader" id="sp-product-loader">
				<div class="sp-product-loader__spinner"></div>
				<span class="sp-product-loader__text">Loading</span>
			</div>
		</div>
	</section>


	<?php
	// === BLOG SEARCH RESULTS ===
	$per_page = 12; // 4 columns x 3 rows per page section
	$paged    = 1;

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
	?>

	<section class="sp-results sp-results--blog">
		<div class="sp-results__inner">
			<div
				class="sp-blog-grid"
				id="sp-blog-grid"
				data-total-pages="<?= esc_attr($total_pages); ?>"
				data-current-page="<?= esc_attr($paged); ?>"
				data-search="<?= esc_attr($search_query); ?>"
				data-limit="<?= esc_attr((string) $per_page); ?>"
				data-overlay-url="<?= esc_attr($blog_overlay_url); ?>">
				<?php if ($blog_query->have_posts()) : ?>
					<?php while ($blog_query->have_posts()) : $blog_query->the_post(); ?>
						<?php get_template_part('template-parts/components/blog-item-v2/index'); ?>
					<?php endwhile; ?>
					<?php wp_reset_postdata(); ?>
				<?php else : ?>
					<p class="sp-results__empty">Không tìm thấy bài viết nào.</p>
				<?php endif; ?>
			</div>

			<div class="sp-blog-sentinel" id="sp-blog-sentinel"></div>

			<div class="sp-blog-loader" id="sp-blog-loader">
				<div class="sp-blog-loader__spinner"></div>
				<span class="sp-blog-loader__text">Loading</span>
			</div>
		</div>
	</section>
</div>