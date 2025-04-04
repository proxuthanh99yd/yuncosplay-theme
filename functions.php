<?php
require get_theme_file_path('/inc/functions.php');
require get_theme_file_path('/inc/helpers.php');
require get_theme_file_path('/import-assets/reset-css-js.php');
require get_theme_file_path('/import-assets/import-css-js.php');
require get_theme_file_path('/inc/ajax.php');
require get_theme_file_path('/inc/api.php');
require get_theme_file_path('/inc/shortcodes.php');

function change_image_domain($attributes, $attachment_id, $size)
{
    // Define the new domain you want to use
    $new_domain = 'https://horizonvietnamtravel.okhub-tech.com';

    // Get the current image URL
    $image_url = $attributes['src'];

    // Parse the image URL to manipulate it
    $parsed_url = parse_url($image_url);

    // If the image has a valid domain (host), replace it with the new domain
    if (isset($parsed_url['host'])) {
        $new_url = str_replace($parsed_url['host'], parse_url($new_domain, PHP_URL_HOST), $image_url);
        $attributes['src'] = $new_url;
    }

    return $attributes;
}

add_filter('wp_get_attachment_image_attributes', 'change_image_domain', 10, 3);
