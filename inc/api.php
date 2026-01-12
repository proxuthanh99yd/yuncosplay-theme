<?php

define('FALLBACK_IMAGE_URL', '/wp-content/uploads/2025/10/placeholder.webp');

add_action(
    'rest_api_init',
    function () {
        register_rest_route('api/v1', '/get-all/(?P<post_type>[a-zA-Z0-9-_]+)', array(
            'methods' => 'GET',
            'callback' => 'getAll',
            'permission_callback' => '__return_true',
        ));
        //         register_rest_route('api/v1', '/update-publish-dates', array(
        //             'methods' => 'GET',
        //             'callback' => 'updatePublishDates',
        //             'permission_callback' => '__return_true',
        //         ));
    }
);

function updatePublishDates($request)
{
    $post_type = sanitize_key($request['post_type'] ?? 'san-pham');
    $orderby = sanitize_key($request['orderby'] ?? 'date');
    $order = in_array(strtoupper($request['order'] ?? 'DESC'), ['ASC', 'DESC']) ? strtoupper($request['order']) : 'DESC';

    // Validate post type exists
    if (!post_type_exists($post_type)) {
        return new WP_Error('invalid_post_type', 'Invalid post type', ['status' => 400]);
    }

    // Get all posts of the specified post type
    $args = [
        'post_type' => $post_type,
        'post_status' => 'publish',
        'posts_per_page' => -1, // Get all posts
        'orderby' => $orderby,
        'order' => $order,
        'fields' => 'ids', // Only get IDs for performance
    ];

    $posts = get_posts($args);

    if (empty($posts)) {
        return [
            'success' => true,
            'message' => 'No posts found to update',
            'updated_count' => 0,
            'post_type' => $post_type
        ];
    }

    $current_date = current_time('Y-m-d H:i:s');
    $updated_count = 0;

    // Update publish dates starting from current date
    foreach ($posts as $index => $post_id) {
        $days_to_subtract = $index; // First post = 0 days, second = 1 day, etc.
        $new_date = date('Y-m-d H:i:s', strtotime($current_date . " -{$days_to_subtract} days"));

        $result = wp_update_post([
            'ID' => $post_id,
            'post_date' => $new_date,
            'post_date_gmt' => get_gmt_from_date($new_date),
        ]);

        if (!is_wp_error($result) && $result !== 0) {
            $updated_count++;
        }
    }

    return [
        'success' => true,
        'message' => "Updated publish dates for {$updated_count} posts",
        'updated_count' => $updated_count,
        'post_type' => $post_type,
        'total_posts' => count($posts),
        'orderby' => $orderby,
        'order' => $order
    ];
}


/**
 * Validate and sanitize request parameters
 */
function validate_request_params($request)
{
    $params = [
        'post_type' => sanitize_key($request['post_type'] ?? 'post'),
        'limit' => max(1, min(100, (int)($request['limit'] ?? 10))), // Limit between 1-100
        'page' => max(1, (int)($request['paged'] ?? 1)),
        'taxonomies' => !empty($request['tax']) ? array_filter(array_map('sanitize_key', explode(',', $request['tax']))) : [],
        'search' => sanitize_text_field($request['s'] ?? ''),
        'order' => in_array(strtoupper($request['order'] ?? 'DESC'), ['ASC', 'DESC']) ? strtoupper($request['order'] ?? 'DESC') : 'DESC',
        'orderby' => sanitize_key($request['orderby'] ?? 'date'),
        'exclude' => !empty($request['exclude']) ? array_filter(array_map('intval', explode(',', $request['exclude']))) : [],
        'is_popular' => isset($request['is_popular']) ? filter_var($request['is_popular'], FILTER_VALIDATE_BOOLEAN, FILTER_NULL_ON_FAILURE) : null
    ];

    // Validate post type exists
    if (!post_type_exists($params['post_type'])) {
        return new WP_Error('invalid_post_type', 'Invalid post type', ['status' => 400]);
    }

    return $params;
}

/**
 * Extend search query to include nha_san_xuat meta field
 */
function extend_search_to_nha_san_xuat($search, $wp_query)
{
    global $wpdb, $wp_query_search_term;

    if (empty($wp_query_search_term)) {
        return $search;
    }

    // Remove the filter after first use to avoid affecting other queries
    remove_filter('posts_search', 'extend_search_to_nha_san_xuat', 10);

    // Sanitize search term
    $search_term = '%' . $wpdb->esc_like($wp_query_search_term) . '%';

    // Add OR condition to search in nha_san_xuat meta field
    $search .= $wpdb->prepare(
        " OR EXISTS (
            SELECT 1 FROM {$wpdb->postmeta}
            WHERE {$wpdb->postmeta}.post_id = {$wpdb->posts}.ID
            AND {$wpdb->postmeta}.meta_key = 'nha_san_xuat'
            AND {$wpdb->postmeta}.meta_value LIKE %s
        )",
        $search_term
    );

    // Clear global variable
    $wp_query_search_term = null;

    return $search;
}

/**
 * Build WP_Query arguments based on validated parameters
 */
function build_query_args($params, $request)
{
    $args = [
        'post_type' => $params['post_type'],
        'posts_per_page' => $params['limit'],
        'paged' => $params['page'],
        'order' => $params['order'],
        'post_status' => 'publish',
        'no_found_rows' => false, // We need found_posts for pagination
        'update_post_meta_cache' => false, // Optimize performance
        'update_post_term_cache' => false
    ];

    // Handle is_popular meta query
    $meta_queries = [];
    if ($params['is_popular'] !== null) {
        if ($params['is_popular']) {
            // Only get popular posts (is_popular = 1)
            $meta_queries[] = [
                'key' => 'is_popular',
                'value' => 1,
                'compare' => '='
            ];
        } else {
            // Only get non-popular posts (is_popular != 1 or not exists)
            $meta_queries[] = [
                'relation' => 'OR',
                [
                    'key' => 'is_popular',
                    'value' => 1,
                    'compare' => '!='
                ],
                [
                    'key' => 'is_popular',
                    'compare' => 'NOT EXISTS'
                ]
            ];
        }
    }

    // Handle orderby
    $default_orderby = ['date', 'title', 'name', 'author', 'rand', 'ID', 'post__in', 'modified', 'id'];
    if (in_array($params['orderby'], $default_orderby)) {
        $args['orderby'] = ($params['orderby'] === 'id') ? 'ID' : $params['orderby'];
    } else {
        // Meta query for custom fields
        $args['meta_key'] = $params['orderby'];

        // Check if field is numeric (for price fields)
        $numeric_fields = ['gia_ban_le', 'gia_ban_theo_du_an'];
        if (in_array($params['orderby'], $numeric_fields)) {
            // For price fields, use meta_value_num but handle non-numeric values
            $args['orderby'] = 'meta_value_num';
            $args['meta_type'] = 'NUMERIC';
        } else {
            $args['orderby'] = 'meta_value';
        }

        $meta_queries[] = [
            'relation' => 'OR',
            [
                'key' => $params['orderby'],
                'compare' => 'EXISTS'
            ],
            [
                'key' => $params['orderby'],
                'compare' => 'NOT EXISTS'
            ]
        ];
    }

    // Merge all meta queries
    if (!empty($meta_queries)) {
        if (count($meta_queries) === 1) {
            $args['meta_query'] = [$meta_queries[0]];
        } else {
            $args['meta_query'] = [
                'relation' => 'AND'
            ];
            foreach ($meta_queries as $meta_query) {
                $args['meta_query'][] = $meta_query;
            }
        }
    }

    // Handle search
    if (!empty($params['search'])) {
        $args['s'] = $params['search'];
        $args['search_columns'] = ['post_title', 'post_content'];

        // If post_type is san-pham, also search in nha_san_xuat field
        if ($params['post_type'] === 'san-pham') {
            // Add filter hook to extend search to meta field
            add_filter('posts_search', 'extend_search_to_nha_san_xuat', 10, 2);

            // Store search term for filter hook
            global $wp_query_search_term;
            $wp_query_search_term = $params['search'];
        }
    }

    // Handle taxonomies
    if (!empty($params['taxonomies'])) {
        $args['tax_query'] = [];
        foreach ($params['taxonomies'] as $taxonomy) {
            if (isset($request[$taxonomy]) && !empty($request[$taxonomy])) {
                $terms = array_filter(array_map('sanitize_text_field', explode(',', $request[$taxonomy])));
                if ($terms) {
                    $args['tax_query'][] = [
                        'taxonomy' => $taxonomy,
                        'field' => 'slug',
                        'terms' => $terms,
                    ];
                }
            }
        }
        if (count($args['tax_query']) > 1) {
            $args['tax_query']['relation'] = 'AND';
        }
    }

    // Handle exclusions
    if (!empty($params['exclude'])) {
        $args['post__not_in'] = $params['exclude'];
    }

    return $args;
}


/**
 * Process posts and format response data
 */
function process_posts_data($query, $post_type)
{
    $response_mapping = [
        'du-an' => 'get_projects',
        'san-pham' => 'get_products',
        'post' => 'get_custom_posts',
    ];

    $data = [];
    while ($query->have_posts()) {
        $query->the_post();
        if (isset($response_mapping[$post_type])) {
            $callback = $response_mapping[$post_type];
            $data[] = $callback(get_post());
        }
    }
    wp_reset_postdata();

    return $data;
}

/**
 * Main getAll function - simplified version without cache
 */
function getAll($request)
{
    // Validate parameters
    $params = validate_request_params($request);
    if (is_wp_error($params)) {
        return $params;
    }

    // Build query arguments
    $args = build_query_args($params, $request);

    // Execute query directly
    $query = new WP_Query($args);

    if ($query->have_posts()) {
        $result = [
            'success' => true,
            'total' => $query->found_posts,
            'totalPages' => $query->max_num_pages,
            'page' => $args['paged'],
            'limit' => $args['posts_per_page'],
            'data' => process_posts_data($query, $params['post_type']),
            'args' => $args,
            'debug' => [
                'is_popular_param' => $params['is_popular'],
                'meta_query' => $args['meta_query'] ?? null,
                'raw_request' => $request,
                'validated_params' => $params
            ]
        ];
    } else {
        $result = [
            'success' => true,
            'total' => 0,
            'totalPages' => 0,
            'page' => $args['paged'],
            'limit' => $args['posts_per_page'],
            'data' => [],
            'args' => $args,
            'debug' => [
                'is_popular_param' => $params['is_popular'],
                'meta_query' => $args['meta_query'] ?? null,
                'raw_request' => $request,
                'validated_params' => $params
            ]
        ];
    }

    wp_reset_postdata();
    return $result;
}

function get_projects($post)
{
    $generals = get_field('tong_quan_du_an');

    if (is_array($generals)) {
        foreach ($generals as &$item) {
            if (!empty($item['icon'])) {
                $url = wp_get_attachment_image_url($item['icon'], 'full');
                $item['icon'] = $url ?: null; // nếu false thì thành null
            } else {
                $item['icon'] = null;
            }
        }
        unset($item); // tránh lỗi reference
    }

    return [
        'id' => $post->ID,
        'title' => $post->post_title,
        'category' => get_the_terms($post->ID, 'project-categories'),
        'excerpt' => $post->post_excerpt,
        'permalink' => get_permalink($post->ID),
        'thumbnail' => get_the_post_thumbnail_url($post->ID, 'full') ?: FALLBACK_IMAGE_URL,
        'generals' => $generals,
        'position' => $position_res,
        'time' => $time_res,
        'product' => $product_res,
    ];
}

function get_products($post)
{
    $categories = get_the_terms($post->ID, 'categories');
    $category_name = $categories && !is_wp_error($categories) ? $categories[0]->name : '';

    $link_contact = [
        'title' => '',
        'url' =>  '',
        'target' => '_self',
    ];

    foreach ($categories as $category) {
        $product_categories_fields_contact_link = get_field('product_categories_fields_contact_link', $category);
        if ($product_categories_fields_contact_link) {
            $link_contact['title'] = isset($product_categories_fields_contact_link['title']) ? $product_categories_fields_contact_link['title'] : '';
            $link_contact['url'] = isset($product_categories_fields_contact_link['url']) ? $product_categories_fields_contact_link['url'] : '';
            $link_contact['target'] = isset($product_categories_fields_contact_link['target']) ? $product_categories_fields_contact_link['target'] : '_self';
            break;
        }
    }
    $lien_he_bao_gia = get_field('lien_he_bao_gia', $post->ID) ?: '';
    if ($lien_he_bao_gia) {
        $link_contact['title'] = isset($lien_he_bao_gia['title']) ? $lien_he_bao_gia['title'] : '';
        $link_contact['url'] = isset($lien_he_bao_gia['url']) ? $lien_he_bao_gia['url'] : '';
        $link_contact['target'] = isset($lien_he_bao_gia['target']) ? $lien_he_bao_gia['target'] : '_self';
    }

    return [
        'id' => $post->ID,
        'title' => $post->post_title,
        'category' => $category_name,
        'excerpt' => $post->post_excerpt,
        'permalink' => get_permalink($post->ID),
        'thumbnail' => get_the_post_thumbnail_url($post->ID, 'full') ?: FALLBACK_IMAGE_URL,
        'ma_san_pham' => get_field('ma_san_pham', $post->ID) ?: '',
        'nha_san_xuat' => get_field('nha_san_xuat', $post->ID) ?: '',
        'gia_ban_le' => get_field('gia_ban_le', $post->ID) ?: 'Liên hệ',
        'gia_ban_theo_du_an' => get_field('gia_ban_theo_du_an', $post->ID) ?: 'Liên hệ',
        'link_contact' => $link_contact,
    ];
}

function get_custom_posts($post)
{
    $categories = get_the_terms($post->ID, 'category');
    $category_name = $categories && !is_wp_error($categories) ? $categories[0]->name : '';

    return [
        'id' => $post->ID,
        'title' => $post->post_title,
        'excerpt' => has_excerpt($post->ID) ? get_the_excerpt($post->ID) : wp_trim_words(strip_tags($post->post_content), 30, '...'),
        'permalink' => get_permalink($post->ID),
        'thumbnail' => get_the_post_thumbnail_url($post->ID, 'full') ?: FALLBACK_IMAGE_URL,
        'category' => $category_name,
        'date' => get_the_date('Y-m-d', $post->ID),
        'author' => get_the_author_meta('display_name', $post->post_author),
    ];
}