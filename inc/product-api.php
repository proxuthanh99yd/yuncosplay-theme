<?php

/**
 * REST API: Products list
 * Endpoint: /wp-json/api/v1/products?search=...&limit=...&page=...
 */

add_action('rest_api_init', function () {
	register_rest_route('api/v1', '/products', array(
		'methods' => 'GET',
		'callback' => 'rest_products_api',
		'permission_callback' => '__return_true',
		'args' => array(
			'search' => array(
				'required' => false,
				'type' => 'string',
				'sanitize_callback' => 'sanitize_text_field',
			),
			'limit' => array(
				'required' => false,
				'type' => 'integer',
				'default' => 12,
				'sanitize_callback' => 'absint',
			),
			'page' => array(
				'required' => false,
				'type' => 'integer',
				'default' => 1,
				'sanitize_callback' => 'absint',
			),
			'category' => array(
				'required' => false,
				'type' => 'string',
				'sanitize_callback' => 'sanitize_text_field',
			),
			// Bọc floatval trong closure: REST gọi sanitize_callback với 3 tham số
			// ($value, $request, $key), mà floatval là hàm internal chỉ nhận đúng
			// 1 tham số -> ArgumentCountError (fatal, response rỗng) ở PHP 8.
			'min_price' => array(
				'required' => false,
				'type' => 'number',
				'sanitize_callback' => function ($value) {
					return floatval($value);
				},
			),
			'max_price' => array(
				'required' => false,
				'type' => 'number',
				'sanitize_callback' => function ($value) {
					return floatval($value);
				},
			),
		),
	));

	// Product categories endpoint
	register_rest_route('api/v1', '/product-categories', array(
		'methods' => 'GET',
		'callback' => 'rest_product_categories_api',
		'permission_callback' => '__return_true',
		'args' => array(
			'parent' => array(
				'required' => false,
				'type' => 'integer',
				'default' => 0,
				'sanitize_callback' => 'absint',
			),
		),
	));
});

function rest_products_api($request) {
	global $wpdb;

	$search = sanitize_text_field($request->get_param('search') ?? '');
	$limit = absint($request->get_param('limit') ?? 12);
	$page = absint($request->get_param('page') ?? 1);
	$category = sanitize_text_field($request->get_param('category') ?? '');
	$min_price = $request->get_param('price_min');
	if ($min_price === null || $min_price === '') {
	$min_price = $request->get_param('min_price');
	}
	$max_price = $request->get_param('price_max');
	if ($max_price === null || $max_price === '') {
	$max_price = $request->get_param('max_price');
	}

	if ($limit <= 0) {
		$limit = 12;
	}
	if ($limit > 100) {
		$limit = 100;
	}
	if ($page <= 0) {
		$page = 1;
	}

	$args = array(
		'post_type'      => 'product',
		'post_status'    => 'publish',
		'posts_per_page' => $limit,
		'paged'          => $page,
	);

	$order_clauses = okhub_product_order_clauses();

	// Category filter
	if (!empty($category)) {
		$args['tax_query'] = array(
			array(
				'taxonomy' => 'product_cat',
				'field'    => 'slug',
				'terms'    => explode(',', $category),
			),
		);
	}

	// Price range filter (using meta_query on _price)
	if (
		($min_price !== null && $min_price !== '') ||
		($max_price !== null && $max_price !== '')
	) {
		$price_meta = array('relation' => 'AND');
		if ($min_price !== null && $min_price !== '' && $min_price > 0) {
			$price_meta[] = array(
				'key'     => '_regular_price',
				'value'   => floatval($min_price),
				'compare' => '>=',
				'type'    => 'NUMERIC',
			);
		}
		if ($max_price !== null && $max_price !== '' && $max_price > 0) {
			$price_meta[] = array(
				'key'     => '_regular_price',
				'value'   => floatval($max_price),
				'compare' => '<=',
				'type'    => 'NUMERIC',
			);
		}
		$args['meta_query'] = $price_meta;
	}

	$where_filter = null;
	if (!empty($search)) {
		$search_like = '%' . $wpdb->esc_like($search) . '%';

		// Chỉ tìm theo tiêu đề sản phẩm (post_title)
		$where_filter = function ($where, $wp_query) use ($wpdb, $search_like) {
			if (!is_object($wp_query)) return $where;
			if ($wp_query->get('post_type') !== 'product') return $where;
			return $where . $wpdb->prepare(" AND {$wpdb->posts}.post_title LIKE %s ", $search_like);
		};

		add_filter('posts_where', $where_filter, 10, 2);
	}

	add_filter('posts_clauses', $order_clauses, 10, 2);
	$query = new WP_Query($args);
	remove_filter('posts_clauses', $order_clauses, 10);
	if ($where_filter) {
		remove_filter('posts_where', $where_filter, 10);
	}
	$items = array();

	if ($query->have_posts()) {
		foreach ($query->posts as $post) {
			$product_id = $post->ID;
			$thumbnail_id = get_post_thumbnail_id($product_id);
			$thumbnail_url = $thumbnail_id ? wp_get_attachment_image_url($thumbnail_id, 'full') : (defined('FALLBACK_IMAGE_URL') ? FALLBACK_IMAGE_URL : null);
			$video_url = function_exists('get_field') ? (get_field('video', $product_id) ?: null) : null;

			$price = null;
			$regular_price = null;
			$sale_price = null;
			$currency = null;
			$sale_price_formatted = null;
			$rent_price_raw = null;
			$rent_price_number = 0;
			$rent_price_formatted = "0";

			if (function_exists('wc_get_product')) {
				$product = wc_get_product($product_id);
				if ($product) {
					$price = $product->get_price();
					$regular_price = $product->get_regular_price();
					$sale_price = $product->get_sale_price();
					$currency = function_exists('get_woocommerce_currency') ? get_woocommerce_currency() : null;
					$rent_price_raw = $product->get_regular_price();
					$rent_price_number = is_numeric($rent_price_raw) ? (float) $rent_price_raw : 0;
					$rent_price_formatted = number_format($rent_price_number, 0, ".", ".");
				}
			}
			
			// Sale price: from _sale_price_custom (same as PHP render)
			$sale_price_custom_raw = get_post_meta($product_id, '_sale_price_custom', true);
			$sale_price_formatted = number_format((float) ($sale_price_custom_raw ?: 0), 0, ".", ".");

			$items[] = array(
				'id' => $product_id,
				'type' => 'product',
				'title' => get_the_title($product_id),
				'url' => get_permalink($product_id),
				'thumbnail' => $thumbnail_url,
				'video' => $video_url,
				'rent_price' => array(
					'value' => $rent_price_number,
					'formatted' => $rent_price_formatted,
				),
				'price' => array(
					'currency' => $currency,
					'value' => $price,
					'regular' => $regular_price,
					'sale' => $sale_price,
					'formatted' => $sale_price_formatted,
				),
			);
		}
	}

	return new WP_REST_Response(array(
		'success' => true,
		'data' => $items,
		'pagination' => array(
			'total' => (int) $query->found_posts,
			'total_pages' => (int) $query->max_num_pages,
			'page' => $page,
			'limit' => $limit,
		),
		'query' => array(
			'search' => $search,
			'category' => $category,
			'min_price' => $min_price,
			'max_price' => $max_price,
		),
	), 200);
}


/**
 * REST API: Product categories list
 * Endpoint: /wp-json/api/v1/product-categories?parent=0
 */
function rest_product_categories_api($request) {
	$parent = absint($request->get_param('parent') ?? 0);

	$terms = get_terms(array(
		'taxonomy'   => 'product_cat',
		'hide_empty' => false,
		'parent'     => $parent,
		'orderby'    => 'name',
		'order'      => 'ASC',
	));

	$items = array();

	if (!is_wp_error($terms)) {
		foreach ($terms as $term) {
			$thumbnail_id = get_term_meta($term->term_id, 'thumbnail_id', true);
			$thumbnail_url = $thumbnail_id ? wp_get_attachment_image_url($thumbnail_id, 'thumbnail') : null;

			$children = get_terms(array(
				'taxonomy'   => 'product_cat',
				'hide_empty' => false,
				'parent'     => $term->term_id,
				'fields'     => 'ids',
			));

			$items[] = array(
				'id'          => $term->term_id,
				'name'        => $term->name,
				'slug'        => $term->slug,
				'description' => $term->description,
				'count'       => $term->count,
				'thumbnail'   => $thumbnail_url,
				'has_children'=> !is_wp_error($children) && count($children) > 0,
				'children'    => !is_wp_error($children) ? array_values($children) : [],
			);
		}
	}

	return new WP_REST_Response(array(
		'success' => true,
		'data'    => $items,
	), 200);
}