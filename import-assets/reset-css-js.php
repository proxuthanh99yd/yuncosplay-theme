<?php

/** Remove global inline styles */
remove_action('wp_enqueue_scripts', 'wp_enqueue_global_styles');
remove_action('wp_footer', 'wp_enqueue_global_styles', 1);
remove_action('wp_body_open', 'wp_global_styles_render_svg_filters');

add_action('wp_enqueue_scripts', function () {
    wp_dequeue_style('wp-block-library');
    wp_dequeue_style('wp-block-library-theme');
    wp_dequeue_style('classic-theme-styles');
    wp_dequeue_style('wp-block-search');
    wp_dequeue_style('wp-block-search');
    wp_dequeue_style('flatsome-main');
});
add_filter('should_load_separate_core_block_assets', '__return_true');

// Loại bỏ Query String trong WordPress

// function remove_query_strings()
// {
//     if (!is_admin()) {
//         add_filter('script_loader_src', 'remove_query_strings_split', 15);
//         add_filter('style_loader_src', 'remove_query_strings_split', 15);
//     }
// }

// function remove_query_strings_split($src)
// {
//     $output = preg_split("/(&ver|\?ver)/", $src);
//     return $output[0];
// }

// add_action('init', 'remove_query_strings');


function custom_dequeue_styles()
{
    wp_dequeue_style('contact-form-7');
    // wp_dequeue_script('contact-form-7');
    wp_dequeue_style('wp-emoji-styles');
    wp_dequeue_style('dashicons');
    wp_deregister_style('dashicons');
}

add_action('wp_enqueue_scripts', 'custom_dequeue_styles', 1000);

// REMOVE WP EMOJI
remove_action('wp_head', 'print_emoji_detection_script', 7);
remove_action('wp_print_styles', 'print_emoji_styles');
remove_action('admin_print_scripts', 'print_emoji_detection_script');
remove_action('admin_print_styles', 'print_emoji_styles');


// Disable the admin bar for all users except administrators
add_action('after_setup_theme', 'remove_admin_bar');
function remove_admin_bar()
{
    show_admin_bar(false);
}
