<?php 
function get_full_content($post_id)
{
    $post = get_post($post_id);
    if (!$post) return '';
    return apply_filters('the_content', $post->post_content);
}
