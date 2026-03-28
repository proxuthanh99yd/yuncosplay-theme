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
		),
	));
});

function rest_products_api($request) {
	$search = sanitize_text_field($request->get_param('search') ?? '');
	$limit = absint($request->get_param('limit') ?? 12);
	$page = absint($request->get_param('page') ?? 1);

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
		'post_type' => 'product',
		'post_status' => 'publish',
		'posts_per_page' => $limit,
		'paged' => $page,
		'orderby' => 'date',
		'order' => 'DESC',
	);

	$where_filter = null;
	if (!empty($search)) {
		global $wpdb;
		$search_like = '%' . $wpdb->esc_like($search) . '%';

		// Chỉ tìm theo tiêu đề sản phẩm (post_title)
		$where_filter = function ($where, $wp_query) use ($wpdb, $search_like) {
			if (!is_object($wp_query)) return $where;
			if ($wp_query->get('post_type') !== 'product') return $where;
			return $where . $wpdb->prepare(" AND {$wpdb->posts}.post_title LIKE %s ", $search_like);
		};

		add_filter('posts_where', $where_filter, 10, 2);
	}

	$query = new WP_Query($args);
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
			$rent_price_raw = function_exists('get_field') ? get_field('rent_price', $product_id) : null;
			$rent_price_number = is_numeric($rent_price_raw) ? (float) $rent_price_raw : 0;
			$rent_price_formatted = number_format($rent_price_number, 0, ".", ".");

			$price = null;
			$regular_price = null;
			$sale_price = null;
			$currency = null;
			$sale_price_formatted = null;

			if (function_exists('wc_get_product')) {
				$product = wc_get_product($product_id);
				if ($product) {
					$price = $product->get_price();
					$regular_price = $product->get_regular_price();
					$sale_price = $product->get_sale_price();
					$currency = function_exists('get_woocommerce_currency') ? get_woocommerce_currency() : null;
					$sale_price_formatted = number_format((float) ($product->get_price() ?: 0), 0, ".", ".");
				}
			}

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
		),
	), 200);
}

