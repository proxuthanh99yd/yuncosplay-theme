<?php
/**
 * Search Results Page
 *
 * Handles both product and blog search results.
 * - Product search: triggered from header search or WooCommerce product search
 * - Blog search: standard WordPress search
 */

get_header();
get_template_part('template-parts/search-page/index');
get_footer();
