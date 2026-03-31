<?php
/**
 * Product Listing Page
 * Renders breadcrumb + main container (sidebar + content)
 */

$current_term = get_queried_object();
$is_category = is_product_category();
$category_name = $is_category && $current_term ? $current_term->name : '';
$category_description = $is_category && $current_term ? term_description($current_term->term_id) : '';
$category_slug = $is_category && $current_term ? $current_term->slug : '';

// Get all top-level product categories for sidebar (exclude "Uncategorized")
$uncategorized_id = get_option('default_product_cat', 0);
$product_categories = get_terms([
	'taxonomy'   => 'product_cat',
	'hide_empty' => false,
	'parent'     => 0,
	'exclude'    => [$uncategorized_id],
	'orderby'    => 'name',
	'order'      => 'ASC',
]);
?>

<?php get_template_part('template-parts/product-listing/section-breadcrumb/index', null, [
	'category_name' => $category_name,
]); ?>

<div class="pl-main">
	<?php get_template_part('template-parts/product-listing/section-sidebar/index', null, [
		'categories'       => $product_categories,
		'current_category' => $category_slug,
	]); ?>

	<?php get_template_part('template-parts/product-listing/section-content/index', null, [
		'category_name'        => $category_name,
		'category_description' => $category_description,
		'category_slug'        => $category_slug,
	]); ?>
</div>

<!-- Sticky Bottom Bar (mobile only) -->
<div class="pl-sticky-bar" id="pl-sticky-bar">
	<button class="pl-filter-btn" type="button" data-action="open-drawer">
		<svg class="pl-filter-btn__icon" width="20" height="20" viewBox="0 0 20 20" fill="none" xmlns="http://www.w3.org/2000/svg">
			<path d="M2.5 3.333h15M5.833 6.667h8.334M4.167 10h11.666M6.667 13.333h6.666M8.333 16.667h3.334" stroke="#F7F4EC" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/>
		</svg>
		<span class="pl-filter-btn__text">Lọc sản phẩm</span>
	</button>
</div>
