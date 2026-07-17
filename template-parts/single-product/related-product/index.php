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

$sliderpreview = 4;
$isShowNavigation = count($related_products) > $sliderpreview;

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
        <?php if ($isShowNavigation): ?>
            <button class="related-products__nav related-products__nav--prev" type="button" aria-label="Sản phẩm trước">
                <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 20 20" fill="none">
                    <path d="M3.55762 10.0004L8.91992 15.3617L10.2939 15.3197L8.14355 13.1693C7.4445 12.4713 6.80521 11.8461 6.22461 11.2943L5.42383 10.5326L6.52832 10.5277L15.415 10.4857L15.3672 9.42419L6.44629 9.46716L5.32324 9.47302L6.13867 8.70056C6.42226 8.43187 6.72109 8.14414 7.03516 7.83728L8.02344 6.85876L10.252 4.63123L8.9707 4.58923L3.55762 10.0004Z" fill="#680103" stroke="#680103" stroke-width="0.8888"/>
                </svg>
            </button>
            <button class="related-products__nav related-products__nav--next" type="button" aria-label="Sản phẩm tiếp theo">
                <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 20 20" fill="none">
                    <path d="M16.4424 10.0004L11.0801 15.3617L9.70605 15.3197L11.8564 13.1693C12.5555 12.4713 13.1948 11.8461 13.7754 11.2943L14.5762 10.5326L13.4717 10.5277L4.58496 10.4857L4.63281 9.42419L13.5537 9.46716L14.6768 9.47302L13.8613 8.70056C13.5777 8.43187 13.2789 8.14414 12.9648 7.83728L11.9766 6.85876L9.74805 4.63123L11.0293 4.58923L16.4424 10.0004Z" fill="#680103" stroke="#680103" stroke-width="0.8888"/>
                </svg>
            </button>
        <?php endif; ?>
        
        <!-- Pagination mobile -->
        <div class="related-products__pagination"></div>
        
    </div>
</section>