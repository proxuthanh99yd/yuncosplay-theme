<?php
$home_products = get_field("home_products");
$product_title = isset($home_products['title']) ? $home_products['title'] : '';
$product_subtitle = isset($home_products['subtitle']) ? $home_products['subtitle'] : '';
$product_desc = isset($home_products['desc']) ? $home_products['desc'] : '';
$product_page_link = isset($home_products['link']) ? $home_products['link'] : [];
$product_ids = $home_products['products'] ?? [];
$args = [
	'post_type'      => 'product',
	'post_status'    => 'publish',
	'posts_per_page' => 8, // hoặc giới hạn số lượng
    'orderby'        => 'date',   // Sắp xếp theo ngày tạo
    'post__in'       => $product_ids,
	'order'          => 'DESC',  // Mới nhất trước
];

$product_query = new WP_Query($args);

?>


<section id="products" class="h-products">
    <div class="h-products__container">
        <div class="h-products__top">
            <div class="h-products__title-container">
                <h3 class="h-products__subtitle"><?= $product_subtitle; ?></h3>
                <h2 class="h-products__title"><?= $product_title; ?></h2>
            </div>
            <p class="h-products__desc"><?= $product_desc; ?></p>
        </div>
        <div class="h-products__content">
            <?php if ($product_query->have_posts()): ?>
            <?php while($product_query->have_posts()): $product_query->the_post(); ?>
            <?php get_template_part('template-parts/components/product/index'); ?>
            <?php endwhile; ?>
            <?php wp_reset_postdata(); ?>
            <?php endif ?>
        </div>
        <div class="h-products__footer">
            <?php if(!empty($product_page_link) && !empty($product_page_link['url'])): ?>
            <?php 
        $product_page_link_url = $product_page_link['url'];
        $product_page_link_text = $product_page_link['title'] ?? 'Xem tất cả';
        $product_page_link_target = $product_page_link['target'] ?? '_self';
      ?>
            <a href="<?= $product_page_link_url ?>" target="<?= $product_page_link_target ?>"
                class="h-products__footer-link">
                <?php get_template_part('template-parts/components/animated-button/index', null, ['text' => $product_page_link_text]); ?>
            </a>
            <?php endif; ?>
        </div>
    </div>
</section>