<?php

/**
 * Product Listing Page
 * Renders breadcrumb + main container (sidebar + content)
 */

$current_term = get_queried_object();
$is_category = is_product_category();

$category_name = '';
$category_description = '';
$category_slug = '';

if ($is_category && $current_term) {
	$category_name = $current_term->name;
	$category_description = term_description($current_term->term_id);
	$category_slug = $current_term->slug;
}

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
		<svg class="pl-filter-btn__icon" xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 20 20" fill="none">
			<path d="M2.79154 1.66675H10.2082C10.8249 1.66675 11.3332 2.17509 11.3332 2.79175V4.02507C11.3332 4.47507 11.0499 5.03342 10.7749 5.31675L8.35826 7.45008C8.02492 7.73342 7.79989 8.29174 7.79989 8.74174V11.1584C7.79989 11.4918 7.5749 11.9418 7.29156 12.1168L6.50823 12.6251C5.7749 13.0751 4.76654 12.5667 4.76654 11.6667V8.69174C4.76654 8.30008 4.54156 7.79176 4.31656 7.50842L2.18323 5.25841C1.89989 4.97508 1.67491 4.47507 1.67491 4.13341V2.84175C1.66657 2.17508 2.17488 1.66675 2.79154 1.66675Z" stroke="#F6F3EA" stroke-width="1.5" stroke-miterlimit="10" stroke-linecap="round" stroke-linejoin="round" />
			<path d="M1.66675 10V12.5C1.66675 16.6667 3.33341 18.3334 7.50008 18.3334H12.5001C16.6667 18.3334 18.3334 16.6667 18.3334 12.5V7.50004C18.3334 4.90004 17.6834 3.2667 16.1751 2.4167C15.7501 2.17504 14.9001 1.9917 14.1251 1.8667" stroke="#F6F3EA" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" />
			<path d="M10.8333 10.8333H14.9999" stroke="#F6F3EA" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" />
			<path d="M9.16675 14.1667H15.0001" stroke="#F6F3EA" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" />
		</svg>
		<span class="pl-filter-btn__text">Lọc sản phẩm</span>
	</button>
</div>