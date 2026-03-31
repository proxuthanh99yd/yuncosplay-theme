<?php
/**
 * Section Content — Product Listing
 * Content header (title + description + expand) + product grid with infinite scroll
 *
 * @param array $args {
 *   @type string $category_name        Category display name
 *   @type string $category_description Category description (may contain HTML)
 *   @type string $category_slug        Category slug for filtering
 * }
 */

$category_name        = !empty($args['category_name']) ? $args['category_name'] : '';
$category_description = !empty($args['category_description']) ? $args['category_description'] : '';
$category_slug        = !empty($args['category_slug']) ? $args['category_slug'] : '';

// WP_Query cho initial products
$paged    = 1;
$per_page = 12;

$query_args = [
	'post_type'      => 'product',
	'post_status'    => 'publish',
	'posts_per_page' => $per_page,
	'paged'          => $paged,
	'orderby'        => 'date',
	'order'          => 'DESC',
];

if (!empty($category_slug)) {
	$query_args['tax_query'] = [[
		'taxonomy' => 'product_cat',
		'field'    => 'slug',
		'terms'    => $category_slug,
	]];
}

$products_query = new WP_Query($query_args);
$total_pages    = $products_query->max_num_pages;

$has_description = !empty(trim(wp_strip_all_tags($category_description)));
?>

<section class="pl-content">
	<!-- Page Title (visible on mobile outside header, desktop inside header) -->
	<h1 class="pl-content__title">Danh sách sản phẩm</h1>

	<!-- Mobile Filter Button -->
	<div class="pl-filter-btn-wrap" id="pl-filter-btn-wrap">
		<button class="pl-filter-btn" type="button" data-action="open-drawer">
			<svg class="pl-filter-btn__icon" width="20" height="20" viewBox="0 0 20 20" fill="none" xmlns="http://www.w3.org/2000/svg">
				<path d="M2.5 3.333h15M5.833 6.667h8.334M4.167 10h11.666M6.667 13.333h6.666M8.333 16.667h3.334" stroke="#F7F4EC" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/>
			</svg>
			<span class="pl-filter-btn__text">Lọc sản phẩm</span>
		</button>
	</div>

	<!-- Content Header (desktop: full header with title; mobile: description only) -->
	<div class="pl-content__header">
		<h1 class="pl-content__title pl-content__title--desktop">Danh sách sản phẩm</h1>

		<div class="pl-content__divider"></div>

		<?php if (!empty($category_name)) : ?>
			<h2 class="pl-content__subtitle"><?php echo esc_html($category_name); ?></h2>
		<?php endif; ?>

		<?php if ($has_description) : ?>
			<div class="pl-content__desc" id="pl-desc">
				<?php echo wp_kses_post($category_description); ?>
			</div>

			<button
				class="pl-content__expand-btn"
				id="pl-expand-btn"
				type="button"
				aria-expanded="false"
				aria-controls="pl-desc"
			>
				<svg width="18" height="18" viewBox="0 0 18 18" fill="none" xmlns="http://www.w3.org/2000/svg">
					<path d="M4.5 6.75L9 11.25L13.5 6.75" stroke="#CB5140" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/>
				</svg>
			</button>
		<?php endif; ?>
	</div>

	<!-- Selected Filters (mobile only) -->
	<div class="pl-selected-filters" id="pl-selected-filters">
		<!-- JS will populate filter chips here -->
	</div>

	<!-- Product Grid -->
	<div
		class="pl-content__grid"
		id="pl-grid"
		data-total-pages="<?php echo esc_attr($total_pages); ?>"
		data-current-page="<?php echo esc_attr($paged); ?>"
		data-category="<?php echo esc_attr($category_slug); ?>"
	>
		<?php if ($products_query->have_posts()) : ?>
			<?php while ($products_query->have_posts()) : $products_query->the_post(); ?>
				<?php get_template_part('template-parts/components/product/index'); ?>
			<?php endwhile; ?>
			<?php wp_reset_postdata(); ?>
		<?php else : ?>
			<p class="pl-content__empty">Không tìm thấy sản phẩm nào.</p>
		<?php endif; ?>
	</div>

	<!-- Infinite Scroll Sentinel -->
	<div class="pl-content__sentinel" id="pl-sentinel"></div>

	<!-- Loading Spinner -->
	<div class="pl-content__loader" id="pl-loader">
		<div class="pl-content__spinner"></div>
		<span class="pl-content__loader-text">Đang tải sản phẩm</span>
	</div>
</section>
