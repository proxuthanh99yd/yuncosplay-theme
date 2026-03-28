<?php 
$search_overlay_id = 9843;
$blog_thumbnail_overlay_id = 9833;
$product_args = [
	'post_type'      => 'product',
	'post_status'    => 'publish',
	'posts_per_page' => 8, 
    'orderby'        => 'date',   
	'order'          => 'DESC',  
];

$product_query = new WP_Query($product_args);

$blog_args = [
	'post_type'      => 'post',
	'post_status'    => 'publish',
	'posts_per_page' => 10,
	'orderby'        => 'date',
	'order'          => 'DESC',
];

$blog_query = new WP_Query($blog_args);

?>

<div class="header-search">
    <div class="header-search__searchbar-wrapper">
        <div class="header-search__searchbar-input-wrapper">
            <input type="text" name="search" placeholder="Nhập từ khoá tìm kiếm" class="header-search__searchbar-input" />
        </div>
        <button class="header-search__searchbar-close-btn">
            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none">
            <path d="M5.00098 5L19 18.9991" stroke="#1D1D1D" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/>
            <path d="M4.99996 18.9991L18.999 5" stroke="#1D1D1D" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/>
            </svg>
        </button>
    </div>
    <div class="header-search__result-list-wrapper">
        <?= wp_get_attachment_image($search_overlay_id, 'full', false, array('loading' => 'lazy', 'decoding' => 'async', 'class' => 'header-search__result-list-overlay')) ?>
        <div class="header-search__result-list">
            <p class="header-search__result-list__title">
                Kết quả tìm kiếm
            </p>
            <div class="header-search__result-list__nav">
                <button data-search-result-type="product" class="header-search__result-list__nav-btn header-search__result-list__nav-btn--active">
                    <span class="header-search__result-list__nav-btn__text">Sản phẩm liên quan</span>
                </button>
                <button data-search-result-type="blog" class="header-search__result-list__nav-btn">
                    <span class="header-search__result-list__nav-btn__text">Tin tức liên quan</span>
                </button>
            </div>
            <div data-lenis-prevent class="header-search__result-list__grid-wrapper">
                <div data-search-result-type="product" class="header-search__result-list__grid header-search__result-list__grid--active">
                <?php if ($product_query->have_posts()): ?>
                <?php while($product_query->have_posts()): $product_query->the_post(); ?>
                    <?php get_template_part('template-parts/components/product/index'); ?>
                <?php endwhile; ?>
                <?php endif; ?>
                <?php wp_reset_postdata(); ?>
                </div>
                <div data-search-result-type="blog" class="header-search__result-list__grid">
                    <?php if ($blog_query->have_posts()): ?>
                    <?php while($blog_query->have_posts()): $blog_query->the_post(); ?>
                        <?php get_template_part('template-parts/components/blog-item-v2/index'); ?>
                    <?php endwhile; ?>
                    <?php endif; ?>
                    <?php wp_reset_postdata(); ?>
                </div>
                <div class="header-search__result-list__grid-observer"></div>
            </div>
        </div>
        <div style="display: none;" class="header-search__result-empty">
            <p class="header-search__result-empty-text">Không có kết quả tìm kiếm phù hợp</p>
            <button class="header-search__result-empty-btn">
                <?php get_template_part('template-parts/components/animated-button/index', null, array('text' => 'Quay lại')); ?>
            </button>
        </div>
    </div>
</div>
<template id="product-search-item-mobile">
    <a href="" class="product">
        <div class="product__img-wrapper">
            <video src="" playsinline muted loop preload="none" class="product__video"></video>
            <img src="" alt="" class="product__img" loading="lazy" decoding="async">
            <h3 class="product__title"></h3>
        </div>
        <div class="product__content">
            <div class="product__rent">
            <span class="product__rent-label">Giá thuê (1 ngày)</span>
            <span class="product__rent-price"></span>
            </div>
            <div class="product__price"></div>
        </div>
    </a>
</template>
<template id="blog-search-item-mobile">
    <article class="blog-item-v2">
        <a href="" class="blog-item-v2__link">
            <div class="blog-item-v2__thumbnail">
                <?= wp_get_attachment_image($blog_thumbnail_overlay_id, 'full', false, ['loading' => 'lazy', 'decoding' => 'async', 'class' => 'blog-item-v2__thumbnail-overlay']); ?>
                <img src="" alt="" class="blog-item-v2__thumbnail-image" loading="lazy" decoding="async">
            </div>
            <div class="blog-item-v2__content">
                <div class="blog-item-v2__meta">
                    <p class="blog-item-v2__category">
                        <span class="blog-item-v2__category-text"></span>
                    </p>
                    <p class="blog-item-v2__date"></p>
                </div>
                <h3 class="blog-item-v2__title"></h3>
            </div>
        </a>
    </article>
</template>