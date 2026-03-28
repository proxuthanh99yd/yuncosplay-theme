<?php

define('FALLBACK_BLOG_IMAGE_URL', '/wp-content/uploads/2025/10/placeholder.webp');

add_action('rest_api_init', function () {
	register_rest_route('api/v1', '/blogs', array(
		'methods' => 'GET',
		'callback' => 'rest_blogs_api',
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
				'default' => 10,
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

function rest_blogs_api($request) {
	$search = sanitize_text_field($request->get_param('search') ?? '');
	$limit = absint($request->get_param('limit') ?? 10);
	$page = absint($request->get_param('page') ?? 1);

	if ($limit <= 0) $limit = 10;
	if ($limit > 100) $limit = 100;
	if ($page <= 0) $page = 1;

	$args = array(
		'post_type' => 'post',
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

		$where_filter = function ($where, $wp_query) use ($wpdb, $search_like) {
			if (!is_object($wp_query)) return $where;
			if ($wp_query->get('post_type') !== 'post') return $where;
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
			$post_id = $post->ID;

			$thumbnail_id = get_post_thumbnail_id($post_id);
			$thumbnail_url = $thumbnail_id
				? wp_get_attachment_image_url($thumbnail_id, 'full')
				: (defined('FALLBACK_IMAGE_URL') ? FALLBACK_IMAGE_URL : FALLBACK_BLOG_IMAGE_URL);

			$post_categories = get_the_category($post_id);
			$category_name = '';
			if (!empty($post_categories) && isset($post_categories[0]) && isset($post_categories[0]->name)) {
				$category_name = $post_categories[0]->name;
			}

			$items[] = array(
				'id' => $post_id,
				'type' => 'post',
				'title' => get_the_title($post_id),
				'url' => get_permalink($post_id),
				'excerpt' => get_the_excerpt($post_id),
				'date' => get_the_date('c', $post_id),
				'thumbnail' => $thumbnail_url,
				'category' => $category_name,
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

