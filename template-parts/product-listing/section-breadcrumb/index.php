<?php
/**
 * Product Listing - Breadcrumb Section
 *
 * @param array $args {
 *     @type string $category_name Tên category hiện tại (optional)
 * }
 */

$category_name = $args['category_name'] ?? '';
$shop_url      = get_permalink(wc_get_page_id('shop'));
?>

<nav class="pl-breadcrumb" aria-label="Breadcrumb">
    <ol class="pl-breadcrumb__list">
        <li class="pl-breadcrumb__item">
            <a href="<?= esc_url(home_url('/')); ?>" class="pl-breadcrumb__link">Trang chủ</a>
        </li>

        <li class="pl-breadcrumb__separator" aria-hidden="true">></li>

        <?php if ($category_name) : ?>
            <li class="pl-breadcrumb__item">
                <a href="<?= esc_url($shop_url); ?>" class="pl-breadcrumb__link">Danh sách sản phẩm</a>
            </li>

            <li class="pl-breadcrumb__separator" aria-hidden="true">></li>

            <li class="pl-breadcrumb__item">
                <span class="pl-breadcrumb__current"><?= esc_html($category_name); ?></span>
            </li>
        <?php else : ?>
            <li class="pl-breadcrumb__item">
                <span class="pl-breadcrumb__current">Danh sách sản phẩm</span>
            </li>
        <?php endif; ?>
    </ol>
</nav>
