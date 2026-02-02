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
        'post' => '<p></p>',
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
add_filter('big_image_size_threshold', '__return_false');

function validate_phone_number_cf7($result, $tag) {
  if ($tag->name !== 'phone-number') {
    return $result;
  }

  $phone = trim($_POST['phone-number'] ?? '');

  /**
   * Chấp nhận:
   *  - Phone numbers with digits, spaces, hyphens, plus, parentheses, 8-20 characters
   */
  $pattern = '/^[\d\s\-\+\(\)]{8,20}$/';

  if (!preg_match($pattern, $phone)) {
    $result->invalidate($tag, __('Invalid phone number', 'textdomain'));
  }

  return $result;
}

add_filter('wpcf7_validate_tel*', 'validate_phone_number_cf7', 10, 2);
add_filter('wpcf7_validate_tel', 'validate_phone_number_cf7', 10, 2);