<?php

define('FALLBACK_IMAGE_URL', '/wp-content/uploads/2025/10/placeholder.webp');

/**
 * Đăng ký route tìm kiếm: /wp-json/api/v1/search
 */
add_action('rest_api_init', function () {
	register_rest_route('api/v1', '/search', array(
		'methods' => 'GET',
		'callback' => 'rest_search_api',
		'permission_callback' => '__return_true',
		'args' => array(
			'keyword' => array(
				'required' => true,
				'type' => 'string',
				'sanitize_callback' => 'sanitize_text_field',
			),
		),
	));

	// get all posts
	register_rest_route('api/v1', '/get-all/(?P<post_type>[a-zA-Z0-9-_]+)', [
		'methods'             => 'GET',
		'callback'            => 'getAll',
		'permission_callback' => '__return_true',
	]);
});

/**
 * API tìm kiếm: posts, taxonomy destination, post type hotels-and-resorts
 * Trả về tối đa 4 kết quả
 */
function rest_search_api($request)
{
	$keyword = $request->get_param('keyword');

	if (empty($keyword)) {
		return new WP_Error('empty_keyword', 'Từ khóa là bắt buộc', array('status' => 400));
	}

	$results = array();

	// Tìm kiếm trong posts
	$posts_query = new WP_Query(array(
		's' => $keyword,
		'post_type' => 'post',
		'post_status' => 'publish',
		'posts_per_page' => 4,
		'orderby' => 'relevance',
	));

	if ($posts_query->have_posts()) {
		foreach ($posts_query->posts as $post) {
			$thumbnail_id = get_post_thumbnail_id($post->ID);
			$thumbnail_url = $thumbnail_id ? wp_get_attachment_image_url($thumbnail_id, 'medium') : FALLBACK_IMAGE_URL;

			$results[] = array(
				'id' => $post->ID,
				'type' => 'post',
				'title' => get_the_title($post->ID),
				'url' => get_permalink($post->ID),
				'excerpt' => get_the_excerpt($post->ID),
				'thumbnail' => $thumbnail_url,
			);
		}
	}

	// Tìm kiếm trong taxonomy destination (chỉ khi chưa đủ 4 kết quả)
	if (count($results) < 4 && taxonomy_exists('destination')) {
		$terms = get_terms(array(
			'taxonomy' => 'destination',
			'search' => $keyword,
			'hide_empty' => false,
			'number' => 4 - count($results),
		));

		if (!is_wp_error($terms) && !empty($terms)) {
			foreach ($terms as $term) {
				$thumbnail_id = get_term_meta($term->term_id, 'thumbnail_id', true);
				$thumbnail_url = $thumbnail_id ? wp_get_attachment_image_url($thumbnail_id, 'medium') : FALLBACK_IMAGE_URL;

				$results[] = array(
					'id' => $term->term_id,
					'type' => 'destination',
					'title' => $term->name,
					'url' => get_term_link($term),
					'excerpt' => $term->description,
					'thumbnail' => $thumbnail_url,
				);
			}
		}
	}

	// Tìm kiếm trong post type hotels-and-resorts (chỉ khi chưa đủ 4 kết quả)
	if (count($results) < 4 && post_type_exists('hotels-and-resorts')) {
		$hotels_query = new WP_Query(array(
			's' => $keyword,
			'post_type' => 'hotels-and-resorts',
			'post_status' => 'publish',
			'posts_per_page' => 4 - count($results),
			'orderby' => 'relevance',
		));

		if ($hotels_query->have_posts()) {
			foreach ($hotels_query->posts as $hotel) {
				$thumbnail_id = get_post_thumbnail_id($hotel->ID);
				$thumbnail_url = $thumbnail_id ? wp_get_attachment_image_url($thumbnail_id, 'medium') : FALLBACK_IMAGE_URL;

				$results[] = array(
					'id' => $hotel->ID,
					'type' => 'hotels-and-resorts',
					'title' => get_the_title($hotel->ID),
					'url' => get_permalink($hotel->ID),
					'excerpt' => get_the_excerpt($hotel->ID),
					'thumbnail' => $thumbnail_url,
				);
			}
		}
	}

	// Giới hạn tối đa 4 kết quả
	$results = array_slice($results, 0, 4);

	return new WP_REST_Response(array(
		'success' => true,
		'data' => $results,
		'total' => count($results),
	), 200);
}

function rest_mega_menu_journeys_api($request)
{
	$taxonomy = sanitize_key($request->get_param('taxonomy'));
	$term_slug = $request->get_param('term_slug');
	$posts_per_page = absint($request->get_param('posts_per_page'));
	$post_type = sanitize_key($request->get_param('post_type'));

	$allowed = array(
		'destination' => array('post_types' => array('any')),
		'holiday-type' => array('post_types' => array('any')),
		'category' => array('post_types' => array('post')),
	);

	if (empty($taxonomy) || empty($term_slug) || !isset($allowed[$taxonomy])) {
		return new WP_Error('invalid_params', 'Invalid taxonomy or term', array('status' => 400));
	}

	if (empty($post_type)) {
		$post_type = $allowed[$taxonomy]['post_types'][0];
	}

	if (!in_array($post_type, $allowed[$taxonomy]['post_types'], true)) {
		return new WP_Error('invalid_post_type', 'Invalid post type', array('status' => 400));
	}

	if ($posts_per_page <= 0) {
		$posts_per_page = 2;
	}

	if ($posts_per_page > 10) {
		$posts_per_page = 10;
	}

	$term = get_term_by('slug', $term_slug, $taxonomy);
	if (!$term || is_wp_error($term)) {
		return new WP_Error('term_not_found', 'Term not found', array('status' => 404));
	}

	$tours = get_posts([
		'post_type' => $post_type,
		'posts_per_page' => $posts_per_page,
		'tax_query' => [
			[
				'taxonomy' => $taxonomy,
				'field' => 'term_id',
				'terms' => $term->term_id,
			],
		],
		'orderby' => 'date',
		'order' => 'DESC',
	]);

	ob_start();
	if (!empty($tours)) {
		foreach ($tours as $tour) {
			$tour_id = is_object($tour) ? $tour->ID : $tour;
			$tour_title = get_the_title($tour_id);
			$tour_url = get_permalink($tour_id);
			$tour_thumb_id = get_post_thumbnail_id($tour_id) ?: 1916;
?>
			<div class="header-mega-menu__journeys-item-wrapper" data-term="<?= esc_attr($term_slug) ?>">
				<a class="header-mega-menu__journeys-item" href="<?= esc_url($tour_url) ?>">
					<?= wp_get_attachment_image($tour_thumb_id, 'full', false, array('class' => '')) ?>
					<div class="header-mega-menu__journeys-item-content">
						<span class="header-mega-menu__journeys-item-title">
							<?= esc_html($tour_title); ?>
						</span>
					</div>
				</a>
			</div>
<?php
		}
	}
	$html = ob_get_clean();

	return new WP_REST_Response(array(
		'success' => true,
		'data' => array(
			'html' => $html,
		),
	), 200);
}

/**
 * Generic get-all endpoint with fallback response for ANY post type
 * /wp-json/api/v1/get-all/{post_type}?limit=10&paged=1&tax=destination,category&destination=laos&orderby=modified
 */
function getAll($request)
{
	$post_type = isset($request['post_type']) ? sanitize_key($request['post_type']) : 'post';
	$limit     = isset($request['limit']) ? absint($request['limit']) : 10;
	$page      = isset($request['paged']) ? absint($request['paged']) : 1;

	$taxonomies = isset($request['tax']) ? explode(',', sanitize_text_field($request['tax'])) : array();
	$s        = isset($request['s']) ? sanitize_text_field($request['s']) : '';
	$order    = isset($request['order']) ? sanitize_text_field($request['order']) : 'DESC';
	$orderby  = isset($request['orderby']) ? sanitize_text_field($request['orderby']) : 'modified';
	$exclude  = isset($request['exclude']) ? explode(',', sanitize_text_field($request['exclude'])) : array();

	$args = array(
		'post_type'      => $post_type,
		'posts_per_page' => $limit > 0 ? $limit : 10,
		'paged'          => $page > 0 ? $page : 1,
		'order'          => in_array(strtoupper($order), array('ASC', 'DESC'), true) ? strtoupper($order) : 'DESC',
		'post_status'    => 'publish',
	);

	// orderby
	if (!empty($orderby)) {
		$default_orderby = array('date', 'title', 'name', 'author', 'rand', 'ID', 'post__in', 'modified', 'id');

		if (in_array($orderby, $default_orderby, true)) {
			$args['orderby'] = ($orderby === 'id') ? 'ID' : $orderby;
		} else {
			$args['meta_key'] = sanitize_key($orderby);
			$args['orderby']  = 'meta_value_num';
			$args['meta_query'] = array(
				'relation' => 'OR',
				array('key' => $args['meta_key'], 'compare' => 'EXISTS'),
				array('key' => $args['meta_key'], 'compare' => 'NOT EXISTS'),
			);
		}
	}

	// search
	if (!empty($s)) {
		$args['s'] = $s;
		$args['search_columns'] = array('post_title');
	}

	// tax_query (filter) - ✅ FIX
	if (!empty($taxonomies)) {
		$args['tax_query'] = array();

		foreach ($taxonomies as $tax) {
			$tax = sanitize_key(trim($tax));
			if (!$tax) continue;

			if (empty($request[$tax])) continue;

			$tax_slug = explode(',', sanitize_text_field($request[$tax]));
			$tax_slug = array_values(array_filter(array_map('sanitize_title', $tax_slug)));

			if (empty($tax_slug)) continue;

			/**
			 * ✅ Nếu truyền cả term cha + term con trong cùng taxonomy:
			 * - Lấy hết term con, bỏ term cha đi (tránh cha kéo toàn bộ con)
			 */
			$children_terms = array();
			foreach ($tax_slug as $slug) {
				$term = get_term_by('slug', $slug, $tax);
				if ($term && !is_wp_error($term) && (int)$term->parent > 0) {
					$children_terms[] = $slug;
				}
			}
			if (!empty($children_terms)) {
				$tax_slug = $children_terms; // ưu tiên term con
			}

			$args['tax_query'][] = array(
				'taxonomy'         => $tax,
				'field'            => 'slug',
				'terms'            => $tax_slug,
				'include_children' => false, // ✅ quan trọng: không kéo term con theo term cha
				'operator'         => 'IN',
			);
		}

		if (count($args['tax_query']) > 1) {
			$args['tax_query']['relation'] = 'AND';
		}
	}

	// exclude
	$exclude = array_filter(array_map('absint', $exclude));
	if (!empty($exclude)) {
		$args['post__not_in'] = $exclude;
	}

	$query = new WP_Query($args);

	$res = array(
		'success'    => true,
		'total'      => (int) $query->found_posts,
		'totalPages' => (int) $query->max_num_pages,
		'page'       => (int) ($args['paged']),
		'limit'      => (int) ($args['posts_per_page']),
		'data'       => array(),
		'args'       => $args,
	);

	$response_mapping = array(
		'products' => 'get_products',
		'awards'   => 'get_award',
		'post'     => 'get_post_2nd',
	);

	while ($query->have_posts()) {
		$query->the_post();

		$id = get_the_ID();
		$pt = get_post_type($id);

		$callback = $response_mapping[$pt] ?? null;

		if ($callback && is_callable($callback)) {
			$item = call_user_func($callback, get_post($id));
			if (!is_array($item)) $item = array();
			$item['id']   = $item['id']   ?? $id;
			$item['type'] = $item['type'] ?? $pt;
		} else {
			$thumb = get_the_post_thumbnail_url($id, 'full');

			$item = array(
				'id'        => $id,
				'type'      => $pt,
				'title'     => get_the_title($id),
				'slug'      => get_post_field('post_name', $id),
				'url'       => get_permalink($id),
				'excerpt'   => get_the_excerpt($id),
				'thumbnail' => $thumb ? $thumb : FALLBACK_IMAGE_URL,
				'date'      => get_the_date('c', $id),
				'modified'  => get_the_modified_date('c', $id),
			);
		}

		// Taxonomies của từng item
		$item['taxonomies'] = array();
		$taxes = get_object_taxonomies($pt, 'names');

		foreach ($taxes as $tax) {
			if (!taxonomy_exists($tax)) continue;

			$terms = get_the_terms($id, $tax);
			if (is_wp_error($terms) || empty($terms)) {
				$item['taxonomies'][$tax] = array();
				continue;
			}

			$item['taxonomies'][$tax] = array_map(function ($t) {
				return array(
					'id'     => (int) $t->term_id,
					'name'   => $t->name,
					'slug'   => $t->slug,
					'parent' => (int) $t->parent,
				);
			}, $terms);
		}

		$res['data'][] = $item;
	}

	wp_reset_postdata();
	return $res;
}

// ================================
// [TẠM] Chuẩn hóa số điện thoại khách hàng
// Ví dụ: "(097) 603-7935" -> "0976037935" (bỏ mọi ký tự không phải số)
//
// GET /wp-json/api/v1/normalize-phones?target=orders&page=1&dry=1
//   - target: orders | users (mặc định orders)
//   - page:   trang xử lý (mỗi trang 1000 bản ghi)
//   - dry:    1 = chỉ xem trước, không ghi (mặc định 0 = ghi thật)
//
// XÓA route này sau khi chạy xong migration.
// ================================
// add_action('rest_api_init', function () {
// 	register_rest_route('api/v1', '/normalize-phones', array(
// 		'methods'             => 'GET',
// 		'callback'            => 'cosplay_normalize_customer_phones',
// 		// 'permission_callback' => function () {
// 		// 	return current_user_can('manage_options');
// 		// },
// 	));
// });

/**
 * Chuẩn hóa số điện thoại về dạng nội địa VN.
 *  - Bỏ mọi ký tự không phải số: "(097) 603-7935" -> "0976037935"
 *  - Quy đổi đầu số quốc tế về 0: "+84 976 037 935" / "84976037935" / "0084976037935" -> "0976037935"
 */
function cosplay_normalize_phone_value($phone)
{
	// Bỏ mọi ký tự không phải số
	$digits = preg_replace('/\D+/', '', (string) $phone);

	if ($digits === '') {
		return '';
	}

	// Tiền tố quay số quốc tế dạng 00 (vd: 0084...) -> bỏ "00"
	if (strpos($digits, '00') === 0) {
		$digits = substr($digits, 2);
	}

	// +84 / 84xxxxxxxxx (84 + 9 số) -> 0xxxxxxxxx
	if (strpos($digits, '84') === 0 && strlen($digits) === 11) {
		$digits = '0' . substr($digits, 2);
	}

	return $digits;
}

/**
 * Callback endpoint tạm — chuẩn hóa billing phone theo từng trang.
 */
function cosplay_normalize_customer_phones($request)
{
	$target   = $request->get_param('target') === 'users' ? 'users' : 'orders';
	$page     = max(1, (int) $request->get_param('page'));
	$dry_run  = (int) $request->get_param('dry') === 1;
	$per_page = 1000;

	$updated = 0;
	$skipped = 0;
	$changes = array();
	$has_more = false;

	if ($target === 'orders') {
		if (!function_exists('wc_get_orders')) {
			return new WP_Error('no_woocommerce', 'WooCommerce chưa được kích hoạt.', array('status' => 400));
		}

		$orders = wc_get_orders(array(
			'limit'   => $per_page,
			'page'    => $page,
			'paginate' => false,
			'orderby' => 'ID',
			'order'   => 'ASC',
			'return'  => 'objects',
		));

		$has_more = count($orders) === $per_page;

		foreach ($orders as $order) {
			$old = $order->get_billing_phone();
			$new = cosplay_normalize_phone_value($old);

			if ($new === '' || $new === $old) {
				$skipped++;
				continue;
			}

			$changes[] = array('id' => $order->get_id(), 'old' => $old, 'new' => $new);

			if (!$dry_run) {
				$order->set_billing_phone($new);
				$order->save();
			}
			$updated++;
		}
	} else {
		$query = new WP_User_Query(array(
			'meta_key'     => 'billing_phone',
			'meta_compare' => 'EXISTS',
			'number'       => $per_page,
			'paged'        => $page,
			'orderby'      => 'ID',
			'order'        => 'ASC',
			'fields'       => array('ID'),
		));

		$users = $query->get_results();
		$has_more = count($users) === $per_page;

		foreach ($users as $user) {
			$uid = (int) $user->ID;
			$old = (string) get_user_meta($uid, 'billing_phone', true);
			$new = cosplay_normalize_phone_value($old);

			if ($new === '' || $new === $old) {
				$skipped++;
				continue;
			}

			$changes[] = array('id' => $uid, 'old' => $old, 'new' => $new);

			if (!$dry_run) {
				update_user_meta($uid, 'billing_phone', $new);
			}
			$updated++;
		}
	}

	return new WP_REST_Response(array(
		'success' => true,
		'target'  => $target,
		'page'    => $page,
		'dry_run' => $dry_run,
		'updated' => $updated,
		'skipped' => $skipped,
		'changes' => $changes,
		'next'    => $has_more
			? add_query_arg(
				array('target' => $target, 'page' => $page + 1, 'dry' => $dry_run ? 1 : 0),
				rest_url('api/v1/normalize-phones')
			)
			: null,
	), 200);
}
