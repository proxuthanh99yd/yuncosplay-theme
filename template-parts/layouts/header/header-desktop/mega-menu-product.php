<?php
$image_mask_gradient_2_id = 245;
$icon_arrow_right_id = 69;

$parent_categories = get_terms([
    'taxonomy'   => 'product_cat',
    'hide_empty' => false,
    'parent'     => 0,
    'orderby'    => 'name',
    'order'      => 'ASC',
]);

$all_categories = get_terms([
    'taxonomy'   => 'product_cat',
    'hide_empty' => false,
]);

$children_by_parent = [];
$fallback_thumbnail_id = 9763;
foreach ($all_categories as $term) {
    $thumbnail_id = (int) get_term_meta($term->term_id, 'thumbnail_id', true) ?? $fallback_thumbnail_id;
    $children_by_parent[ $term->parent ][] = [
        'name'         => $term->name,
        'link'         => get_term_link($term),
        'thumbnail_id' => $thumbnail_id,
    ];
}

if (! is_array($parent_categories)) {
    $parent_categories = [];
}
?>

<div data-mega-menu-content="mega-menu-product" class="header__mega-menu-product header__mega-menu-item">
    <div class="header__mega-menu-product-wrapper">
        <button class="header__mega-menu-product__parent-categories-swiper-prev">
            <?php echo wp_get_attachment_image($icon_arrow_right_id, 'full', false, array( 'class' => '')) ?>
        </button>
        <button class="header__mega-menu-product__parent-categories-swiper-next">
            <?php echo wp_get_attachment_image($icon_arrow_right_id, 'full', false, array( 'class' => '')) ?>
        </button>
        <div class="swiper header__mega-menu-product__parent-categories-swiper">
            <div class="swiper-wrapper header__mega-menu-product__parent-categories-swiper-wrapper">
                <?php foreach ($parent_categories as $parent) : ?>
                    <div class="swiper-slide header__mega-menu-product__parent-categories-swiper-slide">
                        <p class="header__mega-menu-product__parent-category-title">
                            <?php echo esc_html($parent->name); ?>
                        </p>
                    </div>
                <?php endforeach; ?>
            </div>
        </div>
        <div class="header__mega-menu-product__child-categories-swiper-container">
            <?php echo wp_get_attachment_image($image_mask_gradient_2_id, 'full', false, array( 'class' => 'header__mega-menu-product__child-categories-swiper-mask')) ?>
            <div class="header__mega-menu-product__child-categories-swiper-scrollbar">
                <div class="header__mega-menu-product__child-categories-swiper-scrollbar-inner"></div>
            </div>
            <div data-lenis-prevent class="swiper header__mega-menu-product__child-categories-swiper">
                <div class="swiper-wrapper header__mega-menu-product__child-categories-swiper-wrapper">
                    <?php foreach ($parent_categories as $parent) : ?>
                        <?php
                        $children = isset($children_by_parent[ $parent->term_id ]) ? $children_by_parent[ $parent->term_id ] : [];
                        ?>
                        <div class="swiper-slide header__mega-menu-product__child-categories-swiper-slide">
                            <div class="header__mega-menu-product__child-category-list">
                                <?php foreach ($children as $child) : ?>
                                    <a href="<?php echo esc_url($child['link']); ?>" class="header__mega-menu-product__child-category-item">
                                        <div class="header__mega-menu-product__child-category-item__thumbnail">
                                            <?php
                                            if (! empty($child['thumbnail_id'])) {
                                                echo wp_get_attachment_image($child['thumbnail_id'], 'full', false, array('class' => ''));
                                            } else {
                                                echo '<span class="header__mega-menu-product__child-category-item__thumbnail-placeholder"></span>';
                                            }
                                            ?>
                                        </div>
                                        <div class="header__mega-menu-product__child-category-item__content">
                                            <p class="header__mega-menu-product__child-category-item__title">
                                                <?php echo esc_html($child['name']); ?>
                                            </p>
                                        </div>
                                    </a>
                                <?php endforeach; ?>
                            </div>
                        </div>
                    <?php endforeach; ?>
                </div>
            </div>
        </div>
        <?php
        $shop_url = function_exists('wc_get_page_id') ? get_permalink(wc_get_page_id('shop')) : home_url('/san-pham');
        ?>
        <div class="header__mega-menu-product__show-all">
            <a href="<?php echo esc_url($shop_url); ?>" class="animated-btn header__mega-menu-product__show-all-btn">
                <div class="animated-btn-wrapper">
                    <div class="animated-btn__content-hidden">
                        <div class="animated-btn__content-hidden-text">Xem tất cả sản phẩm</div>
                        <span class="animated-btn__content-hidden-icon">
                            <?php echo wp_get_attachment_image($icon_arrow_right_id, 'full', false, array( 'class' => 'animated-btn__icon')) ?>
                        </span>
                    </div>
                    <div class="animated-btn__content-visible">
                        <div class="animated-btn__content-visible-text">Xem tất cả sản phẩm</div>
                        <span class="animated-btn__content-visible-icon">
                            <?php echo wp_get_attachment_image($icon_arrow_right_id, 'full', false, array( 'class' => 'animated-btn__icon')) ?>
                        </span>
                    </div>
                </div>
            </a>
        </div>
    </div>
</div>