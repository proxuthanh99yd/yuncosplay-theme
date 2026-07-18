<?php

add_filter('rank_math/frontend/breadcrumb/html', function ($html, $crumbs, $args) {
    // Inline SVG separator
    $separator = '<span class="separator" aria-hidden="true">'
        . '<svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 16 16" fill="none">
            <path d="M5.93945 13.2788L10.2861 8.93208C10.7995 8.41875 10.7995 7.57875 10.2861 7.06542L5.93945 2.71875" stroke="white" stroke-opacity="0.4" stroke-width="1.5" stroke-miterlimit="10" stroke-linecap="round" stroke-linejoin="round"/>
            </svg>'
        . '</span>';

    // Bắt đầu breadcrumb wrapper
    $output = '<nav class="rank-math-breadcrumb" aria-label="Breadcrumb">';

    foreach ($crumbs as $i => $crumb) {
        $label = esc_html($crumb[0]);
        $url   = $crumb[1] ?? '';
        $title = $crumb[2] ?? $label;

        // Có URL và không phải phần tử cuối → link + separator
        if ($url && $i < count($crumbs) - 1) {
            $output .= '<a href="' . esc_url($url) . '" title="' . esc_attr($title) . '">' . $label . '</a>' . $separator;
        } else {
            // Phần tử cuối cùng (current page)
            $output .= '<span class="current">' . $label . '</span>';
        }
    }

    $output .= '</nav>';

    return $output;
}, 10, 3);



/**
 * Allow changing or removing the Breadcrumb items
 *
 * @param array       $crumbs The crumbs array.
 * @param Breadcrumbs $this   Current breadcrumb object.
 */
add_filter('rank_math/frontend/breadcrumb/items', function ($crumbs, $class) {
    if (is_singular('san-pham')) {
        $crumbs = get_product_crumbs($crumbs);
    }
    if (is_tax('categories')) {
        $crumbs = get_categories_crumbs($crumbs);
    }
    if (is_singular('du-an')) {
        $crumbs = get_du_an_crumbs($crumbs);
    }
    if (is_singular('post')) {
        $crumbs = get_post_crumbs($crumbs);
    }
    return $crumbs;
}, 10, 2);


/**
 * Trang cha breadcrumb — lấy động thay vì hardcode ID.
 * (ID cũ 448/629/72 đã trỏ nhầm sang attachment sau khi re-import media.)
 */
function okhub_shop_page_id()
{
    return function_exists('wc_get_page_id') ? wc_get_page_id('shop') : (int) get_option('woocommerce_shop_page_id');
}
function okhub_blog_page_id()
{
    $page = get_page_by_path('blogs');
    return $page ? $page->ID : (int) get_option('page_for_posts');
}

function get_product_crumbs($crumbs)
{
    $crumbs = [
        [
            0 => 'Trang chủ',
            1 => home_url(),
            'hide_in_schema' => '',
        ],
        [
            0 => get_the_title(okhub_shop_page_id()),
            1 => get_permalink(okhub_shop_page_id()),
            'hide_in_schema' => '',
        ],
    ];
    $crumbs[] = [
        0 => get_the_title(),
        1 => get_the_permalink(),
        'hide_in_schema' => '',
    ];
    return $crumbs;
}

function get_categories_crumbs($crumbs)
{

    $crumbs = [
        [
            0 => 'Trang chủ',
            1 => home_url(),
            'hide_in_schema' => '',
        ],
        [
            0 => get_the_title(okhub_shop_page_id()),
            1 => get_permalink(okhub_shop_page_id()),
            'hide_in_schema' => '',
        ],
        [
            0 => get_queried_object()->name,
            1 => get_term_link(get_queried_object()),
            'hide_in_schema' => '',
        ],
    ];
    return $crumbs;
}

function get_du_an_crumbs($crumbs)
{
    $crumbs = [
        [
            0 => 'Trang chủ',
            1 => home_url(),
            'hide_in_schema' => '',
        ],
        [
            0 => (get_post_type_object('du-an') ? get_post_type_object('du-an')->labels->name : 'Dự án'),
            1 => (get_post_type_archive_link('du-an') ?: home_url()),
            'hide_in_schema' => '',
        ],
    ];
    $crumbs[] = [
        0 => get_the_title(),
        1 => get_the_permalink(),
        'hide_in_schema' => '',
    ];
    return $crumbs;
}

function get_post_crumbs($crumbs)
{
    $crumbs = [
        [
            0 => 'Trang chủ',
            1 => home_url(),
            'hide_in_schema' => '',
        ],
        [
            0 => get_the_title(okhub_blog_page_id()),
            1 => get_permalink(okhub_blog_page_id()),
            'hide_in_schema' => '',
        ],
    ];
    $crumbs[] = [
        0 => get_the_title(),
        1 => get_the_permalink(),
        'hide_in_schema' => '',
    ];
    return $crumbs;
}