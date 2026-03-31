<?php

/**
 * Section: Related Products
 * Figma node: 473:5761
 */

global $product;

if (!$product) {
    $product = wc_get_product(get_the_ID());
}

if (!$product) return;

$product_id = $product->get_id();

// Lấy related products từ WooCommerce
$related_ids = wc_get_related_products($product_id, 8);

if (empty($related_ids)) return;

$related_products = wc_get_products([
    'include'  => $related_ids,
    'limit'    => 8,
    'status'   => 'publish',
    'orderby'  => 'rand',
]);

if (empty($related_products)) return;
?>

<section class="related-products">
    <div class="related-products__header">
        <h2 class="related-products__title">Sản phẩm liên quan</h2>
    </div>

    <div class="related-products__slider-wrap">
        <div class="swiper related-products__swiper">
            <div class="swiper-wrapper">
                <?php foreach ($related_products as $related_product) :
                    $GLOBALS['post'] = get_post($related_product->get_id());
                    setup_postdata($GLOBALS['post']);
                ?>
                    <div class="swiper-slide">
                        <?php get_template_part('template-parts/components/product/index'); ?>
                    </div>
                <?php endforeach;
                wp_reset_postdata();
                ?>
            </div>
        </div>

        <!-- Navigation arrows -->
        <button class="related-products__nav related-products__nav--prev" type="button" aria-label="Sản phẩm trước">
            <svg width="14" height="12" viewBox="0 0 14 12" fill="none" xmlns="http://www.w3.org/2000/svg">
                <path d="M6.5 1L1 6L6.5 11M1 6H13" stroke="#680103" stroke-width="1.5" stroke-linecap="round"
                    stroke-linejoin="round" />
            </svg>
        </button>
        <button class="related-products__nav related-products__nav--next" type="button" aria-label="Sản phẩm tiếp theo">
            <svg width="14" height="12" viewBox="0 0 14 12" fill="none" xmlns="http://www.w3.org/2000/svg">
                <path d="M7.5 1L13 6L7.5 11M13 6H1" stroke="#680103" stroke-width="1.5" stroke-linecap="round"
                    stroke-linejoin="round" />
            </svg>
        </button>
    </div>
</section>