<?php
/**
 * The Template for displaying product archives (shop page, product categories)
 *
 * @see     https://woocommerce.com/document/template-structure/
 * @package WooCommerce\Templates
 * @version 3.4.0
 */

if (! defined('ABSPATH')) {
	exit;
}

get_header('shop');
get_template_part('template-parts/product-listing/index');
get_footer('shop');
