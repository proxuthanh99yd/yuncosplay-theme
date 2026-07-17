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

        <li class="pl-breadcrumb__separator" aria-hidden="true">
            <svg xmlns="http://www.w3.org/2000/svg" width="5" height="5" viewBox="0 0 5 5" fill="none">
                <circle cx="2.5" cy="2.5" r="2.5" fill="#1D1D1D" fill-opacity="0.4" />
            </svg>
        </li>

        <?php if ($category_name) : ?>
            <li class="pl-breadcrumb__item">
                <a href="<?= esc_url($shop_url); ?>" class="pl-breadcrumb__link">Danh sách sản phẩm</a>
            </li>

            <li class="pl-breadcrumb__separator" aria-hidden="true">
                <svg xmlns="http://www.w3.org/2000/svg" width="5" height="5" viewBox="0 0 5 5" fill="none">
                    <circle cx="2.5" cy="2.5" r="2.5" fill="#1D1D1D" fill-opacity="0.4" />
                </svg>
            </li>

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