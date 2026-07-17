<?php

/**
 * The template for displaying all single posts
 */
get_header();
$post_id  = get_the_ID();
$taxonomy = 'service_category'; // đổi lại đúng taxonomy slug của bạn nếu là service_category

if (has_term('dich-vu-chup-anh', $taxonomy, $post_id)) {

    get_template_part('template-parts/service-take-photo-page/index');

} elseif (has_term('dich-vu-pg-pb', $taxonomy, $post_id)) {

    get_template_part('template-parts/service-pgpb/index');

} elseif (has_term('dich-vu-makeup', $taxonomy, $post_id)) {

    get_template_part('template-parts/service-makeup/index');

} else {

    print_r("Không có dịch vụ");

}
get_footer();