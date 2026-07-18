<?php 
// Icon + nền "không kết quả" → file tĩnh theme (okhub_img). Logo vẫn từ CMS.

// Field Header
$header = function_exists('get_field') ? get_field('header', 'option') : [];
$header_logo = is_array($header) && isset($header['logo_image']) ? $header['logo_image'] : null;
$header_contact = is_array($header) && isset($header['contact']) && is_array($header['contact']) ? $header['contact'] : [];
$contact_now = $header_contact['contact_now'] ?? null;

$product_args = [
	'post_type'      => 'product',
	'post_status'    => 'publish',
	'posts_per_page' => 8, // hoặc giới hạn số lượng
    'orderby'        => 'date',   // Sắp xếp theo ngày tạo
	'order'          => 'ASC',   // Mới nhất trước
];

$product_query = new WP_Query($product_args);

$blog_args = [
	'post_type'      => 'post',
	'post_status'    => 'publish',
	'posts_per_page' => 10,
	'orderby'        => 'date',
	'order'          => 'ASC',
];

$blog_query = new WP_Query($blog_args);
?>

<div data-mega-menu-content="mega-menu-search" class="header__mega-menu-search">
    <div class="header__mega-menu-navbar">
        <div class="header__mega-menu-navbar-left">
            <a href="/" class="header__logo">
                <?php echo wp_get_attachment_image($header_logo, 'full', false, array( 'class' => '')) ?>
            </a>
        </div>
        <div class="header__mega-menu-navbar-middle">
            <div class="header__mega-menu-navbar__searchbar">
                <input type="text" name="search-product" class="header__mega-menu-navbar__searchbar-input" placeholder="Cao bồi, Cướp biển hải tặc"/>
                <button class="header__mega-menu-navbar__searchbar-btn">
                    <?php echo okhub_img('icons/search-normal') ?>
                </button>
            </div>
        </div>
        <div class="header__mega-menu-navbar-right">
            <?php if(!empty($contact_now) && !empty($contact_now['url'])) : ?>
            <a href="<?= $contact_now['url'] ?>" target="<?= $contact_now['target'] ?? '_self'; ?>" class="header__contact-btn">
                <?php get_template_part('template-parts/components/animated-button/index', null, ['text' => $contact_now['title'] ?? '']); ?>
            </a>
            <?php endif; ?>
        </div>
    </div>
    <div class="header__mega-menu-search-content">
        <div data-lenis-prevent class="header__mega-menu-search__result">
            <div class="header__mega-menu-search__result-top">
                <p class="header__mega-menu-search__result-title">
                Kết quả tìm kiếm 
                </p>
                <button type="button" class="header__mega-menu-search__result-contact">
                    <span class="header__mega-menu-search__result-contact-icon">
                        <?php echo okhub_img('icons/arrow') ?>
                    </span>
                    <span class="header__mega-menu-search__result-contact-text">
                        Xem thêm
                    </span>
                </button>
            </div>
            <div class="header__mega-menu-search__result-bottom">
                <div class="header__mega-menu-search__result-list">
                    <?php if ($product_query->have_posts()): ?>
                    <?php while($product_query->have_posts()): $product_query->the_post(); ?>
                    <?php get_template_part('template-parts/components/product/index'); ?>
                    <?php endwhile; ?>
                    <?php endif; ?>
                    <?php wp_reset_postdata(); ?>
                </div>
                <div class="header__mega-menu-search__product-no-result">
                    <p class="header__mega-menu-search__product-no-result__text">
                        Không có kết quả tìm kiếm phù hợp
                    </p>
                </div>
            </div>
        </div>
        <div data-lenis-prevent class="header__mega-menu-search__related-blog">
            <div class="header__mega-menu-search__related-blog-top">
                <p class="header__mega-menu-search__related-blog__title">
                    Tin liên quan
                </p>
                <button type="button" class="header__mega-menu-search__related-blog__contact-link">
                    <span class="header__mega-menu-search__related-blog__contact-link-icon">
                        <?php echo okhub_img('icons/arrow') ?>
                    </span>
                    <span class="header__mega-menu-search__related-blog__contact-link-text">
                        Xem thêm
                    </span>
                </button>
            </div>
            <div class="header__mega-menu-search__related-blog-bottom">
                <div class="header__mega-menu-search__related-blog-list">
                    <?php if ($blog_query->have_posts()): ?>
                    <?php while($blog_query->have_posts()): $blog_query->the_post(); ?>
                        <?php get_template_part('template-parts/components/blog-item/index'); ?>
                    <?php endwhile; ?>
                    <?php endif; ?>
                    <?php wp_reset_postdata(); ?>
                </div>
                <div class="header__mega-menu-search__related-blog-no-result">
                    <p class="header__mega-menu-search__related-blog-no-result__text">
                        Không có kết quả tìm kiếm phù hợp
                    </p>
                </div>
            </div>
        </div>
        <div style="display: none;" class="header__mega-menu-search__no-result">
            <?php echo okhub_img('header/no-result-bg', array('class' => 'header__mega-menu-search__no-result__background')) ?>
            <div class="header__mega-menu-search__no-result__content">
                <p class="header__mega-menu-search__no-result__title">
                Không có kết quả tìm kiếm phù hợp
                </p>
                <a href="/" class="header__mega-menu-search__no-result__link">
                    <?php get_template_part('template-parts/components/animated-button/index', null, ['text' => 'Quay lại']); ?>
                </a>
            </div>
        </div>
    </div>
</div>


<template id="product-search-result-item">
    <article class="product">
        <a href="" class="product__link">
            <div class="product__img-wrapper">
                <video src="" playsinline muted loop preload="none" class="product__video"></video>
                <img src="" alt="" class="product__img">
                <div class="product__title-wrapper">
                    <h3 class="product__title"></h3>
                </div>
            </div>
            <div class="product__content">
                <div class="product__content-background">
                    <?= okhub_img('common/rental-price-container', array('class' => 'product__content-background-img')); ?>
                </div>
                <div class="product__rent">
                    <span class="product__rent-label">Giá thuê</span>
                    <p class="product__rent-price">
                        <span class="product__rent-price-value"></span>
                        <span class="product__rent-price-time">/Ngày</span>
                    </p>
                </div>
                <div class="product__price">
                    <span class="product__price-label">Giá bán:</span>
                    <p class="product__price-value"></p>
                </div>
                <div class="product__price-mb"></div>
            </div>
        </a>
    </article>
</template>

<template id="blog-search-result-item">
    <article class="blog-item">
        <a href="" class="blog-item__link" aria-label=""></a>
        <div class="blog-item__thumbnail">
            <img src="" alt="" loading="lazy" decoding="async">
        </div>
        <div class="blog-item__content">
            <p class="blog-item__category"></p>
            <h3 class="blog-item__title"></h3>
        </div>
    </article>
</template>