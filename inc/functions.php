<?php

use Detection\Exception\MobileDetectException;
use Detection\MobileDetectStandalone;

require_once 'Mobile-Detect/standalone/autoloader.php';
require_once 'Mobile-Detect/src/MobileDetectStandalone.php';
$detection = new MobileDetectStandalone();

define('IS_MOBILE', $detection->isMobile() && !$detection->isTablet());

function get_full_content($post_id)
{
    $post = get_post($post_id);
    if (!$post) return '';
    return apply_filters('the_content', $post->post_content);
}


function ration_add_featured_image_html($html)
{
    $screen = get_current_screen();

    $post = [
        'post' => '<p>Khuyến khích sử dụng ảnh tỉ lệ (347x245).</p>',
        'san-pham' => '<p>Khuyến khích sử dụng ảnh tỉ lệ (349x278).</p>',
		'du-an' => '<p>Khuyến khích sử dụng ảnh tỉ lệ (480x512).</p>',
		'phan-hoi' => '<p>Khuyến khích sử dụng ảnh tỉ lệ (100x100).</p>',
    ];

    $page = [
        // page ID => thông báo
    ];

    $post_type = get_post_type();

    if (array_key_exists($post_type, $post)) {
        $html .= $post[$post_type];
    } elseif (is_admin() && ($screen->id == 'page')) {
        global $post;
        $id = $post->ID;
        if (array_key_exists($id, $page)) {
            $html .= $page[$id];
        }
    }

    return $html;
}
add_filter('admin_post_thumbnail_html', 'ration_add_featured_image_html');