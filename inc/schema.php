<?php

add_filter('rank_math/json_ld', 'cosplay_override_rank_math_schema', 9999, 2);

function cosplay_override_rank_math_schema($data, $json_ld)
{
    if (is_singular('post')) {
        return cosplay_remove_invalid_schema_nodes(cosplay_override_rank_math_blog_schema($data));
    }

    if (is_singular('product')) {
        return cosplay_remove_invalid_schema_nodes(cosplay_override_rank_math_product_schema($data));
    }

    if (is_front_page()) {
        return cosplay_remove_invalid_schema_nodes(cosplay_override_rank_math_home_schema($data));
    }

    if (cosplay_is_about_page()) {
        return cosplay_remove_invalid_schema_nodes(cosplay_override_rank_math_about_schema($data));
    }

    if (cosplay_is_contact_page()) {
        return cosplay_remove_invalid_schema_nodes(cosplay_override_rank_math_contact_schema($data));
    }

    if (cosplay_is_faq_page()) {
        return cosplay_remove_invalid_schema_nodes(cosplay_override_rank_math_faq_schema($data));
    }

    if (cosplay_is_blog_listing_page()) {
        return cosplay_remove_invalid_schema_nodes(cosplay_override_rank_math_blog_listing_schema($data));
    }

    if (is_search()) {
        return cosplay_remove_invalid_schema_nodes(cosplay_override_rank_math_search_schema($data));
    }

    if (cosplay_is_product_archive_page()) {
        return cosplay_remove_invalid_schema_nodes(cosplay_override_rank_math_product_archive_schema($data));
    }

    return cosplay_remove_invalid_schema_nodes($data);
}

function cosplay_override_rank_math_blog_schema($data)
{
    $post_id = get_queried_object_id();

    $data = cosplay_remove_schema_types($data, ['Article', 'BlogPosting', 'Organization', 'LocalBusiness', 'WebSite', 'BreadcrumbList']);
    $data['Organization'] = cosplay_get_organization_schema($post_id);
    $data['WebSite']      = cosplay_get_website_schema();
    $data['Article']      = cosplay_get_article_schema($post_id);
    $data['Breadcrumb']   = cosplay_get_blog_breadcrumb_schema($post_id);

    return $data;
}

function cosplay_override_rank_math_product_schema($data)
{
    $product = function_exists('wc_get_product') ? wc_get_product(get_queried_object_id()) : false;

    if (! $product) {
        return $data;
    }

    $data = cosplay_remove_schema_types($data, ['Product', 'Offer', 'AggregateOffer', 'Organization', 'LocalBusiness', 'WebSite', 'BreadcrumbList']);
    $data['Organization'] = cosplay_get_organization_schema($product->get_id());
    $data['WebSite']      = cosplay_get_website_schema();
    $data['Product']      = cosplay_get_product_schema($product);
    $data['Breadcrumb']   = cosplay_get_product_breadcrumb_schema($product);

    return $data;
}

function cosplay_override_rank_math_home_schema($data)
{
    $post_id = (int) get_option('page_on_front');
    $url     = home_url('/');

    $data = cosplay_remove_schema_types($data, ['WebSite', 'WebPage', 'Organization', 'LocalBusiness', 'BreadcrumbList']);
    $data['Organization'] = cosplay_get_organization_schema($post_id);
    $data['WebSite']      = cosplay_get_website_schema();
    $data['WebPage']      = cosplay_get_webpage_schema($post_id, 'WebPage', get_bloginfo('name'), cosplay_get_post_schema_description($post_id, get_bloginfo('description')), $url);
    $data['Breadcrumb']   = cosplay_get_simple_breadcrumb_schema($url, [
        ['Trang chủ', $url],
    ]);

    return $data;
}

function cosplay_override_rank_math_about_schema($data)
{
    $post_id = get_queried_object_id();
    $url     = get_permalink($post_id);

    $data = cosplay_remove_schema_types($data, ['Article', 'BlogPosting', 'WebPage', 'AboutPage', 'Organization', 'LocalBusiness', 'WebSite', 'BreadcrumbList']);
    $data['Organization'] = cosplay_get_organization_schema($post_id);
    $data['WebSite']      = cosplay_get_website_schema();
    $data['AboutPage']    = cosplay_get_webpage_schema($post_id, 'AboutPage');
    $data['Breadcrumb']   = cosplay_get_simple_breadcrumb_schema($url, [
        ['Trang chủ', home_url('/')],
        [get_the_title($post_id), $url],
    ]);

    return $data;
}

function cosplay_override_rank_math_contact_schema($data)
{
    $post_id = get_queried_object_id();
    $url     = get_permalink($post_id);

    $data = cosplay_remove_schema_types($data, ['Article', 'BlogPosting', 'WebPage', 'ContactPage', 'Organization', 'LocalBusiness', 'WebSite', 'BreadcrumbList']);
    $data['Organization'] = cosplay_get_organization_schema($post_id);
    $data['WebSite']      = cosplay_get_website_schema();
    $data['ContactPage']  = cosplay_get_webpage_schema($post_id, 'ContactPage');
    $data['Breadcrumb']   = cosplay_get_simple_breadcrumb_schema($url, [
        ['Trang chủ', home_url('/')],
        [get_the_title($post_id), $url],
    ]);

    return $data;
}

function cosplay_override_rank_math_faq_schema($data)
{
    $post_id     = get_queried_object_id();
    $url         = get_permalink($post_id);
    $main_entity = cosplay_get_faq_main_entity($post_id);

    $data = cosplay_remove_schema_types($data, ['Article', 'BlogPosting', 'WebPage', 'FAQPage', 'Organization', 'LocalBusiness', 'WebSite', 'BreadcrumbList']);
    $data['Organization']    = cosplay_get_organization_schema($post_id);
    $data['WebSite']         = cosplay_get_website_schema();
    $data['CustomFAQPage']   = cosplay_get_webpage_schema($post_id, $main_entity ? 'FAQPage' : 'WebPage');

    if ($main_entity) {
        $data['CustomFAQPage']['mainEntity'] = $main_entity;
    }

    $data['Breadcrumb'] = cosplay_get_simple_breadcrumb_schema($url, [
        ['Trang chủ', home_url('/')],
        [get_the_title($post_id), $url],
    ]);

    return $data;
}

function cosplay_override_rank_math_blog_listing_schema($data)
{
    $post_id = cosplay_get_blog_page_id() ?: get_queried_object_id();
    $url     = $post_id ? get_permalink($post_id) : home_url('/blogs/');
    $title   = $post_id ? get_the_title($post_id) : 'Danh sách bài viết';
    $posts   = get_posts([
        'post_type'              => 'post',
        'post_status'            => 'publish',
        'posts_per_page'         => 10,
        'ignore_sticky_posts'    => true,
        'no_found_rows'          => true,
        'update_post_meta_cache' => false,
        'update_post_term_cache' => false,
    ]);

    $data = cosplay_remove_schema_types($data, ['Article', 'BlogPosting', 'WebPage', 'CollectionPage', 'Blog', 'ItemList', 'Organization', 'LocalBusiness', 'WebSite', 'BreadcrumbList']);
    $data['Organization']   = cosplay_get_organization_schema($post_id);
    $data['WebSite']        = cosplay_get_website_schema();
    $data['CollectionPage'] = cosplay_get_webpage_schema($post_id, 'CollectionPage', $title, cosplay_get_post_schema_description($post_id), $url);
    $data['ItemList']       = cosplay_get_post_item_list_schema($url . '#itemlist', $posts);
    $data['Breadcrumb']     = cosplay_get_simple_breadcrumb_schema($url, [
        ['Trang chủ', home_url('/')],
        [$title, $url],
    ]);

    if ($data['ItemList']) {
        $data['CollectionPage']['mainEntity'] = ['@id' => $url . '#itemlist'];
    }

    return $data;
}

function cosplay_override_rank_math_product_archive_schema($data)
{
    $context = cosplay_get_product_archive_context();
    $posts   = cosplay_get_current_archive_posts('product', 12);

    $data = cosplay_remove_schema_types($data, ['Article', 'BlogPosting', 'WebPage', 'CollectionPage', 'ItemList', 'Product', 'Offer', 'AggregateOffer', 'Organization', 'LocalBusiness', 'WebSite', 'BreadcrumbList']);
    $data['Organization']   = cosplay_get_organization_schema($context['post_id']);
    $data['WebSite']        = cosplay_get_website_schema();
    $data['CollectionPage'] = cosplay_get_webpage_schema($context['post_id'], 'CollectionPage', $context['name'], $context['description'], $context['url']);
    $data['ItemList']       = cosplay_get_post_item_list_schema($context['url'] . '#itemlist', $posts);
    $data['Breadcrumb']     = cosplay_get_simple_breadcrumb_schema($context['url'], $context['breadcrumbs']);

    if ($data['ItemList']) {
        $data['CollectionPage']['mainEntity'] = ['@id' => $context['url'] . '#itemlist'];
    }

    return $data;
}

function cosplay_override_rank_math_search_schema($data)
{
    $search_query = get_search_query(false);
    $post_type    = isset($_GET['post_type']) ? sanitize_key(wp_unslash($_GET['post_type'])) : 'product';
    $post_type    = in_array($post_type, ['post', 'product'], true) ? $post_type : 'product';
    $url          = cosplay_get_current_url(['s', 'post_type']);
    $title        = $search_query ? sprintf('Kết quả tìm kiếm: %s', $search_query) : 'Kết quả tìm kiếm';
    $posts        = $search_query ? cosplay_get_search_posts($post_type, $search_query, 10) : [];

    $data = cosplay_remove_schema_types($data, ['Article', 'BlogPosting', 'WebPage', 'SearchResultsPage', 'CollectionPage', 'ItemList', 'Organization', 'LocalBusiness', 'WebSite', 'BreadcrumbList']);
    $data['Organization']      = cosplay_get_organization_schema();
    $data['WebSite']           = cosplay_get_website_schema();
    $data['SearchResultsPage'] = cosplay_get_webpage_schema(0, 'SearchResultsPage', $title, '', $url);
    $data['ItemList']          = cosplay_get_post_item_list_schema($url . '#itemlist', $posts);
    $data['Breadcrumb']        = cosplay_get_simple_breadcrumb_schema($url, [
        ['Trang chủ', home_url('/')],
        ['Kết quả tìm kiếm', $url],
    ]);

    if ($data['ItemList']) {
        $data['SearchResultsPage']['mainEntity'] = ['@id' => $url . '#itemlist'];
    }

    return $data;
}

function cosplay_is_about_page()
{
    return is_page_template('about-us-page.php') || is_page('about-us') || is_page('gioi-thieu');
}

function cosplay_is_contact_page()
{
    return is_page_template('page-contact.php') || is_page('contact') || is_page('lien-he');
}

function cosplay_is_faq_page()
{
    return is_page_template('faqs.php') || is_page('faqs') || is_page('faq');
}

function cosplay_is_blog_listing_page()
{
    $blog_page_id = cosplay_get_blog_page_id();

    return is_page_template('blogs.php') || is_home() || ($blog_page_id && is_page($blog_page_id));
}

function cosplay_is_product_archive_page()
{
    return function_exists('is_shop') && (is_shop() || is_product_category() || is_product_tag());
}

function cosplay_remove_schema_types($data, $types)
{
    foreach ($data as $key => $node) {
        if (cosplay_schema_node_has_type($node, $types)) {
            unset($data[$key]);
        }
    }

    return $data;
}

function cosplay_schema_node_has_type($node, $types)
{
    if (! is_array($node) || ! isset($node['@type'])) {
        return false;
    }

    $node_types = (array) $node['@type'];

    return (bool) array_intersect($node_types, $types);
}

function cosplay_get_logo_url()
{
    $custom_logo_id = get_theme_mod('custom_logo');

    if (! $custom_logo_id) {
        return '';
    }

    return wp_get_attachment_image_url($custom_logo_id, 'full') ?: '';
}

function cosplay_get_featured_image_url($post_id)
{
    $thumbnail_id = get_post_thumbnail_id($post_id);

    if (! $thumbnail_id) {
        return '';
    }

    return wp_get_attachment_image_url($thumbnail_id, 'full') ?: '';
}

function cosplay_get_current_url($allowed_query_args = [])
{
    global $wp;

    $path = isset($wp->request) ? $wp->request : '';
    $url  = home_url($path ? '/' . $path . '/' : '/');

    if ($allowed_query_args && ! empty($_GET)) {
        $query_args = [];

        foreach ($allowed_query_args as $key) {
            if (isset($_GET[$key])) {
                $query_args[$key] = sanitize_text_field(wp_unslash($_GET[$key]));
            }
        }

        if ($query_args) {
            $url = add_query_arg($query_args, $url);
        }
    }

    return esc_url_raw($url);
}

function cosplay_get_post_schema_description($post_id, $fallback = '')
{
    if (! $post_id) {
        return $fallback;
    }

    $description = get_the_excerpt($post_id);

    if (! $description) {
        $description = wp_trim_words(wp_strip_all_tags(get_post_field('post_content', $post_id)), 30);
    }

    return $description ?: $fallback;
}

function cosplay_get_term_schema_description($term)
{
    if (! $term || is_wp_error($term)) {
        return '';
    }

    return wp_strip_all_tags(term_description($term));
}

function cosplay_get_organization_schema($post_id = 0)
{
    $business_data = cosplay_get_business_schema_data();
    $business_image_url = $business_data['image'] ?: cosplay_get_logo_url();
    $schema             = [
        '@type'      => ['Organization', 'LocalBusiness', 'Store'],
        '@id'        => home_url('/#organization'),
        'name'       => $business_data['name'] ?: get_bloginfo('name'),
        'url'        => home_url('/'),
        'logo'       => $business_image_url,
        'image'      => $business_image_url,
        'priceRange' => $business_data['price_range'] ?: '$$',
    ];

    if (! empty($business_data['phone'])) {
        $schema['telephone'] = $business_data['phone'];
    }

    if (! empty($business_data['address'])) {
        $schema['address'] = $business_data['address'];
    }

    if (! empty($business_data['opening_hours'])) {
        $schema['openingHours'] = $business_data['opening_hours'];
    }

    if (! empty($business_data['area_served'])) {
        $schema['areaServed'] = $business_data['area_served'];
    }

    if (! empty($business_data['map_url'])) {
        $schema['hasMap'] = $business_data['map_url'];
    }

    if (! empty($business_data['geo'])) {
        $schema['geo'] = $business_data['geo'];
    }

    if (! empty($business_data['same_as'])) {
        $schema['sameAs'] = $business_data['same_as'];
    }

    return cosplay_clean_schema_array($schema);
}

function cosplay_get_website_schema()
{
    return cosplay_clean_schema_array([
        '@type'          => 'WebSite',
        '@id'            => home_url('/#website'),
        'url'            => home_url('/'),
        'name'           => get_bloginfo('name'),
        'description'    => get_bloginfo('description'),
        'publisher'      => ['@id' => home_url('/#organization')],
        'potentialAction' => [
            '@type'       => 'SearchAction',
            'target'      => home_url('/?s={search_term_string}'),
            'query-input' => 'required name=search_term_string',
        ],
    ]);
}

function cosplay_get_webpage_schema($post_id, $type, $name = '', $description = '', $url = '')
{
    $url         = $url ?: ($post_id ? get_permalink($post_id) : cosplay_get_current_url());
    $name        = $name ?: ($post_id ? get_the_title($post_id) : '');
    $description = $description ?: cosplay_get_post_schema_description($post_id);

    return cosplay_clean_schema_array([
        '@type'             => $type,
        '@id'               => $url . '#webpage',
        'url'               => $url,
        'name'              => $name,
        'description'       => $description,
        'isPartOf'          => ['@id' => home_url('/#website')],
        'about'             => ['@id' => home_url('/#organization')],
        'primaryImageOfPage' => cosplay_get_featured_image_url($post_id),
    ]);
}

function cosplay_get_simple_breadcrumb_schema($url, $items)
{
    $list_items = [];

    foreach ($items as $index => $item) {
        $list_items[] = cosplay_get_breadcrumb_list_item($index + 1, $item[0], $item[1]);
    }

    return cosplay_clean_schema_array([
        '@type'           => 'BreadcrumbList',
        '@id'             => $url . '#breadcrumb',
        'itemListElement' => $list_items,
    ]);
}

function cosplay_get_post_item_list_schema($id, $posts)
{
    if (! $posts) {
        return [];
    }

    $items = [];

    foreach (array_values($posts) as $index => $post) {
        $post_id = is_object($post) ? (int) $post->ID : (int) $post;

        if (! $post_id) {
            continue;
        }

        $items[] = cosplay_clean_schema_array([
            '@type'    => 'ListItem',
            'position' => $index + 1,
            'url'      => get_permalink($post_id),
            'name'     => get_the_title($post_id),
        ]);
    }

    if (! $items) {
        return [];
    }

    return cosplay_clean_schema_array([
        '@type'           => 'ItemList',
        '@id'             => $id,
        'itemListElement' => $items,
    ]);
}

function cosplay_get_business_schema_data()
{
    if (! function_exists('get_field')) {
        return [];
    }

    $address = get_field('business_address', 'option');
    $geo     = get_field('business_geo', 'option');

    return cosplay_clean_schema_array([
        'name'          => wp_strip_all_tags((string) get_field('business_name', 'option')),
        'phone'         => wp_strip_all_tags((string) get_field('business_phone', 'option')),
        'image'         => cosplay_get_business_image_url(),
        'address'       => cosplay_get_business_address_schema($address),
        'opening_hours' => cosplay_get_business_opening_hours_schema(get_field('business_opening_hours', 'option')),
        'price_range'   => wp_strip_all_tags((string) get_field('business_price_range', 'option')),
        'area_served'   => wp_strip_all_tags((string) get_field('business_area_served', 'option')),
        'same_as'       => cosplay_get_business_social_urls(get_field('business_socials', 'option')),
        'map_url'       => esc_url_raw((string) get_field('business_map_url', 'option')),
        'geo'           => cosplay_get_business_geo_schema($geo),
    ]);
}

function cosplay_get_business_image_url()
{
    $image = function_exists('get_field') ? get_field('business_image', 'option') : 0;

    if (is_array($image)) {
        return esc_url_raw($image['url'] ?? '');
    }

    if ($image) {
        return wp_get_attachment_image_url((int) $image, 'full') ?: '';
    }

    return '';
}

function cosplay_get_business_address_schema($address)
{
    if (! is_array($address)) {
        return [];
    }

    return cosplay_clean_schema_array([
        '@type'           => 'PostalAddress',
        'streetAddress'   => wp_strip_all_tags((string) ($address['street_address'] ?? '')),
        'addressLocality' => wp_strip_all_tags((string) ($address['address_locality'] ?? '')),
        'addressRegion'   => wp_strip_all_tags((string) ($address['address_region'] ?? '')),
        'postalCode'      => wp_strip_all_tags((string) ($address['postal_code'] ?? '')),
        'addressCountry'  => wp_strip_all_tags((string) ($address['address_country'] ?? '')),
    ]);
}

function cosplay_get_business_opening_hours_schema($rows)
{
    if (! is_array($rows)) {
        return [];
    }

    $opening_hours = [];

    foreach ($rows as $row) {
        $days   = $row['days'] ?? [];
        $opens  = $row['opens'] ?? '';
        $closes = $row['closes'] ?? '';

        if (! is_array($days)) {
            $days = $days ? [$days] : [];
        }

        $days = array_values(array_intersect($days, ['Mo', 'Tu', 'We', 'Th', 'Fr', 'Sa', 'Su']));

        if (! $days || ! preg_match('/^\\d{2}:\\d{2}$/', $opens) || ! preg_match('/^\\d{2}:\\d{2}$/', $closes)) {
            continue;
        }

        $opening_hours[] = implode(',', $days) . ' ' . $opens . '-' . $closes;
    }

    return $opening_hours;
}

function cosplay_get_business_social_urls($socials)
{
    if (! is_array($socials)) {
        return [];
    }

    $urls = [];

    foreach ($socials as $social) {
        $url = $social['url'] ?? '';

        if ($url) {
            $urls[] = esc_url_raw($url);
        }
    }

    return array_values(array_unique($urls));
}

function cosplay_get_business_geo_schema($geo)
{
    if (! is_array($geo) || empty($geo['lat']) || empty($geo['lng'])) {
        return [];
    }

    return cosplay_clean_schema_array([
        '@type'     => 'GeoCoordinates',
        'latitude'  => (float) $geo['lat'],
        'longitude' => (float) $geo['lng'],
    ]);
}

function cosplay_get_faq_main_entity($post_id)
{
    if (! function_exists('get_field')) {
        return [];
    }

    $faq_section = get_field('Question', $post_id);
    $questions   = $faq_section['list_question'] ?? [];
    $entities    = [];
    $seen        = [];

    if (! is_array($questions)) {
        return [];
    }

    foreach ($questions as $item) {
        $question = wp_strip_all_tags($item['question'] ?? '');
        $answer   = html_entity_decode(wp_strip_all_tags($item['answers'] ?? ''), ENT_QUOTES, get_bloginfo('charset'));
        $key      = md5($question . '|' . $answer);

        if (! $question || ! $answer || isset($seen[$key])) {
            continue;
        }

        $seen[$key] = true;
        $entities[] = [
            '@type'          => 'Question',
            'name'           => $question,
            'acceptedAnswer' => [
                '@type' => 'Answer',
                'text'  => $answer,
            ],
        ];
    }

    return $entities;
}

function cosplay_get_search_posts($post_type, $search_query, $limit = 10)
{
    add_filter('posts_search', 'cosplay_schema_title_only_search', 10, 2);

    $posts = get_posts([
        'post_type'              => $post_type,
        'post_status'            => 'publish',
        's'                      => $search_query,
        'posts_per_page'         => $limit,
        'ignore_sticky_posts'    => true,
        'no_found_rows'          => true,
        'update_post_meta_cache' => false,
        'update_post_term_cache' => false,
    ]);

    remove_filter('posts_search', 'cosplay_schema_title_only_search', 10);

    return $posts;
}

function cosplay_schema_title_only_search($search, $wp_query)
{
    global $wpdb;

    $term = $wp_query->get('s');

    if (! $term) {
        return $search;
    }

    return $wpdb->prepare(" AND {$wpdb->posts}.post_title LIKE %s ", '%' . $wpdb->esc_like($term) . '%');
}

function cosplay_get_product_archive_context()
{
    $shop_page_id = cosplay_get_product_listing_page_id();
    $shop_url     = $shop_page_id ? get_permalink($shop_page_id) : (function_exists('wc_get_page_permalink') ? wc_get_page_permalink('shop') : home_url('/shop/'));
    $shop_name    = 'Danh sách sản phẩm';
    $breadcrumbs  = [
        ['Trang chủ', home_url('/')],
        [$shop_name, $shop_url],
    ];

    if ((function_exists('is_product_category') && is_product_category()) || (function_exists('is_product_tag') && is_product_tag())) {
        $term = get_queried_object();

        if ($term && ! is_wp_error($term)) {
            $term_url      = get_term_link($term);
            $term_url      = is_wp_error($term_url) ? $shop_url : $term_url;
            $breadcrumbs[] = [$term->name, $term_url];

            return [
                'post_id'     => 0,
                'name'        => $term->name,
                'description' => cosplay_get_term_schema_description($term),
                'url'         => $term_url,
                'breadcrumbs' => $breadcrumbs,
            ];
        }
    }

    return [
        'post_id'     => $shop_page_id,
        'name'        => $shop_page_id ? get_the_title($shop_page_id) : $shop_name,
        'description' => $shop_page_id ? cosplay_get_post_schema_description($shop_page_id) : '',
        'url'         => $shop_url,
        'breadcrumbs' => $breadcrumbs,
    ];
}

function cosplay_get_current_archive_posts($post_type, $limit = 12)
{
    global $wp_query;

    if (! empty($wp_query->posts)) {
        return array_slice($wp_query->posts, 0, $limit);
    }

    return get_posts([
        'post_type'              => $post_type,
        'post_status'            => 'publish',
        'posts_per_page'         => $limit,
        'no_found_rows'          => true,
        'update_post_meta_cache' => false,
        'update_post_term_cache' => false,
    ]);
}

function cosplay_get_article_schema($post_id)
{
    $author_id   = (int) get_post_field('post_author', $post_id);
    $description = cosplay_get_post_schema_description($post_id);
    $publisher   = [
        '@type' => 'Organization',
        'name'  => get_bloginfo('name'),
    ];
    $logo_url    = cosplay_get_logo_url();

    if ($logo_url) {
        $publisher['logo'] = [
            '@type' => 'ImageObject',
            'url'   => $logo_url,
        ];
    }

    return cosplay_clean_schema_array([
        '@type'         => 'Article',
        '@id'           => get_permalink($post_id) . '#article',
        'headline'      => get_the_title($post_id),
        'description'   => $description,
        'url'           => get_permalink($post_id),
        'datePublished' => get_the_date('c', $post_id),
        'dateModified'  => get_the_modified_date('c', $post_id),
        'author'        => [
            '@type' => 'Person',
            'name'  => get_the_author_meta('display_name', $author_id),
        ],
        'publisher'     => $publisher,
        'image'         => cosplay_get_featured_image_url($post_id),
    ]);
}

function cosplay_get_product_schema($product)
{
    $product_id  = $product->get_id();
    $description = wp_strip_all_tags($product->get_short_description());

    if (! $description) {
        $description = wp_strip_all_tags($product->get_description());
    }

    return cosplay_clean_schema_array([
        '@type'       => 'Product',
        '@id'         => get_permalink($product_id) . '#product',
        'name'        => $product->get_name(),
        'description' => $description,
        'sku'         => $product->get_sku(),
        'url'         => get_permalink($product_id),
        'image'       => cosplay_get_featured_image_url($product_id),
        'brand'       => [
            '@type' => 'Brand',
            'name'  => get_bloginfo('name'),
        ],
        'offers'      => cosplay_get_product_offer_schema($product),
    ]);
}

function cosplay_get_product_offer_schema($product)
{
    if ($product->is_type('variable')) {
        return cosplay_get_variable_product_offer_schema($product);
    }

    $product_id = $product->get_id();
    $price      = cosplay_get_product_schema_price($product);

    return cosplay_clean_schema_array([
        '@type'         => 'Offer',
        '@id'           => get_permalink($product_id) . '#offer',
        'url'           => get_permalink($product_id),
        'priceCurrency' => function_exists('get_woocommerce_currency') ? get_woocommerce_currency() : '',
        'price'         => $price,
        'availability'  => cosplay_get_product_schema_availability($product),
        'businessFunction' => 'https://schema.org/LeaseOut',
    ]);
}

function cosplay_get_variable_product_offer_schema($product)
{
    $product_id = $product->get_id();
    $prices     = [];

    foreach ($product->get_children() as $variation_id) {
        $variation = function_exists('wc_get_product') ? wc_get_product($variation_id) : false;

        if (! $variation) {
            continue;
        }

        $price = cosplay_get_product_schema_price($variation);

        if ($price !== '') {
            $prices[] = (float) $price;
        }
    }

    if (! $prices) {
        return [];
    }

    return cosplay_clean_schema_array([
        '@type'         => 'AggregateOffer',
        '@id'           => get_permalink($product_id) . '#aggregate-offer',
        'url'           => get_permalink($product_id),
        'priceCurrency' => function_exists('get_woocommerce_currency') ? get_woocommerce_currency() : '',
        'lowPrice'      => cosplay_format_schema_price(min($prices)),
        'highPrice'     => cosplay_format_schema_price(max($prices)),
        'offerCount'    => count($prices),
        'availability'  => cosplay_get_product_schema_availability($product),
        'businessFunction' => 'https://schema.org/LeaseOut',
    ]);
}

function cosplay_get_product_schema_price($product)
{
    $prices = [
        $product->get_regular_price(),
        $product->get_price(),
        $product->get_sale_price(),
        get_post_meta($product->get_id(), '_sale_price_custom', true),
    ];

    foreach ($prices as $price) {
        if ($price !== '' && $price !== null) {
            return cosplay_format_schema_price($price);
        }
    }

    return '';
}

function cosplay_format_schema_price($price)
{
    return function_exists('wc_format_decimal') ? wc_format_decimal($price, wc_get_price_decimals()) : $price;
}

function cosplay_get_product_schema_availability($product)
{
    $map = [
        'instock'     => 'https://schema.org/InStock',
        'outofstock'  => 'https://schema.org/OutOfStock',
        'onbackorder' => 'https://schema.org/PreOrder',
    ];

    return $map[$product->get_stock_status()] ?? 'https://schema.org/InStock';
}

function cosplay_get_blog_breadcrumb_schema($post_id)
{
    $blog_page_id = cosplay_get_blog_page_id();
    $blog_url     = $blog_page_id ? get_permalink($blog_page_id) : home_url('/blogs/');

    return cosplay_get_simple_breadcrumb_schema(get_permalink($post_id), [
        ['Trang chủ', home_url('/')],
        ['Danh sách bài viết', $blog_url],
        [get_the_title($post_id), get_permalink($post_id)],
    ]);
}

function cosplay_get_product_breadcrumb_schema($product)
{
    $product_id         = $product->get_id();
    $product_listing_id = cosplay_get_product_listing_page_id();
    $product_listing_url = $product_listing_id ? get_permalink($product_listing_id) : home_url('/shop/');

    return cosplay_get_simple_breadcrumb_schema(get_permalink($product_id), [
        ['Trang chủ', home_url('/')],
        ['Danh sách sản phẩm', $product_listing_url],
        [$product->get_name(), get_permalink($product_id)],
    ]);
}

function cosplay_get_blog_page_id()
{
    $blog_page = get_page_by_path('blogs');

    return $blog_page ? (int) $blog_page->ID : 0;
}

function cosplay_get_product_listing_page_id()
{
    if (! function_exists('wc_get_page_id')) {
        return 0;
    }

    $page_id = (int) wc_get_page_id('shop');

    return $page_id > 0 ? $page_id : 0;
}

function cosplay_get_breadcrumb_list_item($position, $name, $url)
{
    return [
        '@type'    => 'ListItem',
        'position' => $position,
        'name'     => $name,
        'item'     => $url,
    ];
}

function cosplay_remove_invalid_schema_nodes($data)
{
    foreach ($data as $key => $node) {
        if (! is_array($node)) {
            continue;
        }

        if (! isset($node['@type']) || $node['@type'] === '' || $node['@type'] === []) {
            unset($data[$key]);
        }
    }

    return $data;
}

function cosplay_clean_schema_array($data)
{
    foreach ($data as $key => $value) {
        if (is_array($value)) {
            $value = cosplay_clean_schema_array($value);
        }

        if ($value === null || $value === '' || $value === []) {
            unset($data[$key]);
            continue;
        }

        $data[$key] = $value;
    }

    return $data;
}
