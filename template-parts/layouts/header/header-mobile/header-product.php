<?php
// Icon + thumbnail fallback → file tĩnh theme (okhub_img). Thumbnail danh mục vẫn từ CMS.

$parent_categories = [];
$children_by_parent = [];
if ( taxonomy_exists( 'product_cat' ) ) {
    $parent_categories = get_terms( [
        'taxonomy'   => 'product_cat',
        'hide_empty' => false,
        'parent'     => 0,
        'orderby'    => 'name',
        'order'      => 'ASC',
    ] );
    $all_categories = get_terms( [
        'taxonomy'   => 'product_cat',
        'hide_empty' => false,
    ] );
    if ( ! is_array( $parent_categories ) ) {
        $parent_categories = [];
    }
    if ( is_array( $all_categories ) ) {
        foreach ( $all_categories as $term ) {
            $thumbnail_id = (int) get_term_meta( $term->term_id, 'thumbnail_id', true );
            $children_by_parent[ $term->parent ][] = [
                'name'         => $term->name,
                'link'         => get_term_link( $term ),
                'thumbnail_id' => $thumbnail_id,
            ];
        }
    }
    // Danh mục cấp 1 (parent = 0) cho phần "Danh sách danh mục"
    $level1_categories = $parent_categories;
}
?>

<div class="header-product">
    <div class="header-product__header">
        <div class="header-product__title-wrapper" data-close="header-product">
            <span class="header-product__title-icon">
                <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 20 20" fill="none">
                <path d="M12.5 16.25L6.2498 9.85143L12.5 3.75" stroke="#1D1D1D" stroke-width="2"/>
                </svg>
            </span>
            <h2 class="header-product__title-text">Sản phẩm</h2>
        </div>
        <button class="header-product__btn-close" data-close="header-product">
            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none">
            <path d="M5.00098 5L19 18.9991" stroke="#1D1D1D" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/>
            <path d="M4.99996 18.9991L18.999 5" stroke="#1D1D1D" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/>
            </svg>
        </button>
    </div>
    <div class="header-product__content" data-lenis-prevent>
        <div class="header-product__category-mul-level">
            <div class="header-product__category-mul-level__parent">
                <div class="swiper header-product__category-mul-level__parent-swiper">
                    <div class="header-product__category-mul-level__parent-swiper-button-nav-wrapper">
                        <button class="header-product__category-mul-level__parent-swiper-button-nav header-product__category-mul-level__parent-swiper-button-nav--prev">
                            <?php echo okhub_img('icons/arrow') ?>
                        </button>
                        <button class="header-product__category-mul-level__parent-swiper-button-nav header-product__category-mul-level__parent-swiper-button-nav--next">
                            <?php echo okhub_img('icons/arrow') ?>
                        </button>
                    </div>
                    <div class="swiper-wrapper header-product__category-mul-level__parent-swiper-wrapper">
                        <?php foreach ( $parent_categories as $parent ) : ?>
                            <div class="swiper-slide header-product__category-mul-level__parent-swiper-slide">
                                <p class="header-product__category-mul-level__parent-item">
                                    <span class="header-product__category-mul-level__parent-item__text"><?php echo esc_html( $parent->name ); ?></span>
                                </p>
                            </div>
                        <?php endforeach; ?>
                    </div>
                </div>
            </div>
            <div class="header-product__category-mul-level__child">
                <div class="swiper header-product__category-mul-level__child-swiper">
                    <div class="swiper-wrapper header-product__category-mul-level__child-swiper-wrapper">
                        <?php foreach ( $parent_categories as $parent ) : ?>
                            <?php $children = isset( $children_by_parent[ $parent->term_id ] ) ? $children_by_parent[ $parent->term_id ] : []; ?>
                            <div class="swiper-slide header-product__category-mul-level__child-swiper-slide">
                                <?php foreach ( $children as $child ) : ?>
                                    <?php $child_thumb_id = ! empty( $child['thumbnail_id'] ) ? (int) $child['thumbnail_id'] : 0; ?>
                                    <a href="<?php echo esc_url( $child['link'] ); ?>" class="header-product__category-mul-level__child-item">
                                        <div class="header-product__category-mul-level__child-item__thumbnail">
                                            <?php echo $child_thumb_id ? wp_get_attachment_image( $child_thumb_id, 'full', false, array( 'class' => '' ) ) : okhub_img('common/thumb-fallback'); ?>
                                        </div>
                                        <div class="header-product__category-mul-level__child-item__content">
                                            <h3 class="header-product__category-mul-level__child-item__title"><?php echo esc_html( $child['name'] ); ?></h3>
                                        </div>
                                    </a>
                                <?php endforeach; ?>
                            </div>
                        <?php endforeach; ?>
                    </div>
                </div>
            </div>
        </div>
        <div class="header-product__category-single-level">
            <p class="header-product__category-single-level__title">Danh sách danh mục</p>
            <div class="header-product__category-single-level__category-list">
                <?php
                $list_categories = isset( $level1_categories ) && is_array( $level1_categories ) ? $level1_categories : [];
                foreach ( $list_categories as $term ) :
                    $thumb_id = (int) get_term_meta( $term->term_id, 'thumbnail_id', true );
                    $thumb_id = $thumb_id ?: 0;
                    $term_link = get_term_link( $term );
                    if ( is_wp_error( $term_link ) ) {
                        $term_link = '#';
                    }
                    ?>
                    <a href="<?php echo esc_url( $term_link ); ?>" class="header-product__category-single-level__category-item">
                        <div class="header-product__category-single-level__category-item__thumbnail">
                            <?php echo $thumb_id ? wp_get_attachment_image( $thumb_id, 'full', false, array( 'class' => '' ) ) : okhub_img('common/thumb-fallback'); ?>
                        </div>
                        <div class="header-product__category-single-level__category-item__title"><?php echo esc_html( $term->name ); ?></div>
                    </a>
                <?php endforeach; ?>
            </div>
            <a href="/" class="header-product__category-single-level__view-all">
                <?php get_template_part( 'template-parts/components/animated-button/index', null, array( 'text' => 'Xem tất cả sản phẩm' ) ); ?>
            </a>
        </div>
    </div>
</div>