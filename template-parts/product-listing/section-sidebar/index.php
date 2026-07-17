<?php

/**
 * Product Listing — Sidebar Filter
 *
 * @param array $args {
 *     @type WP_Term[] $categories       Mảng top-level product categories
 *     @type string    $current_category  Slug của category đang active
 * }
 */

$categories       = $args['categories'] ?? [];
$current_category = $args['current_category'] ?? '';

// Xác định current_category là cha hay con
// Nếu là danh mục con → tìm ra slug cha để active card cha + mở panel
$active_parent_slug = '';
$active_child_slug  = '';

if (!empty($current_category)) {
    $current_term = get_term_by('slug', $current_category, 'product_cat');
    if ($current_term && !is_wp_error($current_term)) {
        if ($current_term->parent === 0) {
            // Đang ở danh mục cha
            $active_parent_slug = $current_category;
        } else {
            // Đang ở danh mục con → tìm cha
            $active_child_slug  = $current_category;
            $parent_term = get_term($current_term->parent, 'product_cat');
            if ($parent_term && !is_wp_error($parent_term)) {
                $active_parent_slug = $parent_term->slug;
            }
        }
    }
}

// Tạo mảng tất cả cards: "Tất cả sản phẩm" + danh mục cha
$all_cards = [];

// Card "Tất cả sản phẩm" — luôn đứng đầu
$all_cards[] = [
    'type'           => 'all',
    'slug'           => '',
    'name'           => __('Tất cả sản phẩm', 'okhub-theme'),
    'thumbnail_id'   => 0,
    'is_active'      => empty($current_category),
    'has_children'   => false,
    'subcategories'  => [],
];

if (!empty($categories) && !is_wp_error($categories)) {
    foreach ($categories as $cat) {
        $subcategories = get_terms([
            'taxonomy'   => 'product_cat',
            'hide_empty' => false,
            'parent'     => $cat->term_id,
            'orderby'    => 'name',
            'order'      => 'ASC',
        ]);

        $has_children = !empty($subcategories) && !is_wp_error($subcategories);

        $all_cards[] = [
            'type'           => 'category',
            'slug'           => $cat->slug,
            'name'           => $cat->name,
            'thumbnail_id'   => get_term_meta($cat->term_id, 'thumbnail_id', true),
            'is_active'      => ($active_parent_slug === $cat->slug),
            'has_children'   => $has_children,
            'subcategories'  => $has_children ? $subcategories : [],
        ];
    }
}

// Chia thành hàng 3 cards
$rows = array_chunk($all_cards, 3);
?>

<!-- Mobile Filter Drawer Overlay -->
<div class="pl-filter-drawer" id="pl-filter-drawer" data-lenis-prevent>
    <div class="pl-filter-drawer__header">
        <span class="pl-filter-drawer__title">Bộ lọc sản phẩm</span>
        <button class="pl-filter-drawer__close" type="button" data-action="close-drawer" aria-label="Đóng bộ lọc">
            <svg width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                <path d="M18 6L6 18M6 6l12 12" stroke="#1C1C1C" stroke-width="1.5" stroke-linecap="round"
                    stroke-linejoin="round" />
            </svg>
        </button>
    </div>
    <div class="pl-filter-drawer__body">

        <aside id="sidebar-filter" class="pl-sidebar">
            <div class="pl-sidebar__filter-box">

                <!-- ========================= Price Range ========================= -->
                <div class="pl-sidebar__price-section">
                    <div class="pl-sidebar__section-header">
                        <div class="pl-sidebar__section-header-left">
                            <span class="pl-sidebar__section-icon">
                                <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 18 18"
                                    fill="none">
                                    <g clip-path="url(#clip0_2444_6822)">
                                        <path
                                            d="M9 18C6.59602 18 4.33589 17.0639 2.63602 15.364C0.936141 13.6641 0 11.404 0 9C0 6.59602 0.936176 4.33593 2.63602 2.63602C4.33586 0.936106 6.59602 0 9 0C11.404 0 13.6641 0.936141 15.364 2.63602C17.0639 4.33589 18 6.59602 18 9C18 11.404 17.0638 13.6641 15.364 15.364C13.6641 17.0639 11.404 18 9 18ZM9 1.125C4.65771 1.125 1.125 4.65771 1.125 9C1.125 13.3423 4.65771 16.875 9 16.875C13.3423 16.875 16.875 13.3423 16.875 9C16.875 4.65771 13.3423 1.125 9 1.125Z"
                                            fill="#1D1D1D" fill-opacity="0.4" />
                                        <path
                                            d="M9 8.4375C8.22459 8.4375 7.59375 7.80666 7.59375 7.03125C7.59375 6.25584 8.22459 5.625 9 5.625C9.77541 5.625 10.4062 6.25584 10.4062 7.03125C10.4062 7.34189 10.6581 7.59375 10.9688 7.59375C11.2794 7.59375 11.5312 7.34189 11.5312 7.03125C11.5312 5.82887 10.6883 4.82034 9.5625 4.56377V3.9375C9.5625 3.62686 9.31068 3.375 9 3.375C8.68932 3.375 8.4375 3.62686 8.4375 3.9375V4.56377C7.31173 4.82034 6.46875 5.82887 6.46875 7.03125C6.46875 8.42699 7.60426 9.5625 9 9.5625C9.77541 9.5625 10.4062 10.1933 10.4062 10.9688C10.4062 11.7442 9.77541 12.375 9 12.375C8.22459 12.375 7.59375 11.7442 7.59375 10.9688C7.59375 10.6581 7.34193 10.4062 7.03125 10.4062C6.72057 10.4062 6.46875 10.6581 6.46875 10.9688C6.46875 12.1711 7.31173 13.1797 8.4375 13.4362V14.0625C8.4375 14.3731 8.68932 14.625 9 14.625C9.31068 14.625 9.5625 14.3731 9.5625 14.0625V13.4362C10.6883 13.1797 11.5312 12.1711 11.5312 10.9688C11.5312 9.57301 10.3957 8.4375 9 8.4375Z"
                                            fill="#1D1D1D" fill-opacity="0.4" />
                                    </g>
                                    <defs>
                                        <clipPath id="clip0_2444_6822">
                                            <rect width="18" height="18" fill="white" />
                                        </clipPath>
                                    </defs>
                                </svg>
                            </span>
                            <span class="pl-sidebar__section-label">Khoảng giá</span>
                        </div>
                    </div>

                    <div class="pl-sidebar__slider-wrap">
                        <div id="pl-price-slider" data-min="100000" data-max="10000000" data-step="10000"></div>

                        <div class="pl-sidebar__slider-labels">
                            <span class="pl-sidebar__slider-label" data-role="min-label">100.000đ</span>
                            <span class="pl-sidebar__slider-label" data-role="max-label">10.000.000đ</span>
                        </div>
                    </div>
                </div>

                <!-- ========================= Separator ========================= -->
                <div class="pl-sidebar__separator"></div>

                <!-- ========================= Category Section ========================= -->
                <div class="pl-sidebar__category-section" data-lenis-prevent>
                    <div class="pl-sidebar__section-header pl-sidebar__section-header--between">
                        <div class="pl-sidebar__section-header-left">
                            <span class="pl-sidebar__section-icon">
                                <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 18 18"
                                    fill="none">
                                    <path
                                        d="M1.5 1.5H2.805C3.615 1.5 4.2525 2.1975 4.185 3L3.5625 10.47C3.4575 11.6925 4.42499 12.7425 5.65499 12.7425H13.6425C14.7225 12.7425 15.6675 11.8575 15.75 10.785L16.155 5.16C16.245 3.915 15.3 2.9025 14.0475 2.9025H4.36501"
                                        stroke="#1D1D1D" stroke-opacity="0.4" stroke-width="1.5" stroke-miterlimit="10"
                                        stroke-linecap="round" stroke-linejoin="round" />
                                    <path
                                        d="M12.1875 16.5C12.7053 16.5 13.125 16.0803 13.125 15.5625C13.125 15.0447 12.7053 14.625 12.1875 14.625C11.6697 14.625 11.25 15.0447 11.25 15.5625C11.25 16.0803 11.6697 16.5 12.1875 16.5Z"
                                        stroke="#1D1D1D" stroke-opacity="0.4" stroke-width="1.5" stroke-miterlimit="10"
                                        stroke-linecap="round" stroke-linejoin="round" />
                                    <path
                                        d="M6.1875 16.5C6.70527 16.5 7.125 16.0803 7.125 15.5625C7.125 15.0447 6.70527 14.625 6.1875 14.625C5.66973 14.625 5.25 15.0447 5.25 15.5625C5.25 16.0803 5.66973 16.5 6.1875 16.5Z"
                                        stroke="#1D1D1D" stroke-opacity="0.4" stroke-width="1.5" stroke-miterlimit="10"
                                        stroke-linecap="round" stroke-linejoin="round" />
                                    <path d="M6.75 6H15.75" stroke="#1D1D1D" stroke-opacity="0.4" stroke-width="1.5"
                                        stroke-miterlimit="10" stroke-linecap="round" stroke-linejoin="round" />
                                </svg>
                            </span>
                            <span class="pl-sidebar__section-label">Chọn danh mục</span>
                        </div>
                        <button class="pl-sidebar__clear-btn" type="button" data-action="clear-categories">
                            Xoá lựa chọn
                        </button>
                    </div>

                    <!-- Category Grid — chia theo hàng, mỗi hàng 3 cards + triangle + filter panel -->
                    <?php foreach ($rows as $row_index => $row_cards) :
                        // Tìm xem hàng này có card nào đang active và có subcategories không
                        $active_card_in_row = null;
                        foreach ($row_cards as $card) {
                            if ($card['is_active'] && $card['has_children']) {
                                $active_card_in_row = $card;
                                break;
                            }
                        }

                        // Thu thập tất cả slugs có subcategories trong hàng này
                        $row_slugs_with_children = [];
                        foreach ($row_cards as $card) {
                            if ($card['has_children']) {
                                $row_slugs_with_children[] = $card['slug'];
                            }
                        }
                    ?>

                        <!-- Row <?= $row_index + 1; ?> -->
                        <div class="pl-sidebar__category-row" data-row-index="<?= esc_attr($row_index); ?>">
                            <div class="pl-sidebar__category-row-cards">
                                <?php foreach ($row_cards as $card) :
                                    $active_class = $card['is_active'] ? ' pl-sidebar__category-card--active' : '';
                                ?>
                                    <div class="pl-sidebar__category-card<?= $active_class; ?>"
                                        data-category-slug="<?= esc_attr($card['slug']); ?>"
                                        data-has-children="<?= $card['has_children'] ? 'true' : 'false'; ?>" role="button"
                                        tabindex="0">
                                        <div
                                            class="pl-sidebar__category-thumb<?= ($card['type'] === 'all' || empty($card['thumbnail_id'])) ? ' pl-sidebar__category-thumb--empty' : ''; ?>">
                                            <?php if ($card['type'] !== 'all' && !empty($card['thumbnail_id'])) : ?>
                                                <?= wp_get_attachment_image($card['thumbnail_id'], 'thumbnail', false, [
                                                    'class'   => 'pl-sidebar__category-img',
                                                    'alt'     => esc_attr($card['name']),
                                                    'loading' => 'lazy',
                                                ]); ?>
                                            <?php else : ?>
                                                <svg width="24" height="24" viewBox="0 0 18 18" fill="none"
                                                    xmlns="http://www.w3.org/2000/svg">
                                                    <path d="M2 4.5H16M4.5 9H13.5M7 13.5H11" stroke="#1C1C1C" stroke-opacity="0.4"
                                                        stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" />
                                                </svg>
                                            <?php endif; ?>
                                        </div>
                                        <span class="pl-sidebar__category-name"><?= esc_html($card['name']); ?></span>
                                    </div>
                                <?php endforeach; ?>
                            </div>

                            <!-- Triangle + Filter Panels cho hàng này -->
                            <?php if (!empty($row_slugs_with_children)) : ?>
                                <div
                                    class="pl-sidebar__triangle-row<?= $active_card_in_row ? ' pl-sidebar__triangle-row--visible' : ''; ?>">
                                    <div class="pl-sidebar__triangle"></div>
                                </div>

                                <?php foreach ($row_cards as $card) :
                                    if (!$card['has_children']) continue;

                                    $panel_open_class = ($card['is_active']) ? ' pl-sidebar__filter-panel--open' : '';
                                ?>
                                    <div class="pl-sidebar__filter-panel<?= $panel_open_class; ?>"
                                        data-panel-for="<?= esc_attr($card['slug']); ?>">
                                        <div class="pl-sidebar__filter-row">
                                            <?php
                                                // Chip "Tất cả" — active khi đang chọn danh mục cha (không phải con)
                                                $is_all_active = ($active_parent_slug === $card['slug'] && empty($active_child_slug));
                                                $all_chip_class = $is_all_active ? ' pl-sidebar__chip--active' : '';
                                            ?>
                                            <button class="pl-sidebar__chip<?= $all_chip_class; ?>" type="button"
                                                data-filter-value="<?= esc_attr($card['slug']); ?>"
                                                data-category-slug="<?= esc_attr($card['slug']); ?>"
                                                data-chip-role="all">
                                                <span class="pl-sidebar__chip-icon">
                                                    <?php if ($is_all_active) : ?>
                                                        <svg width="18" height="18" viewBox="0 0 18 18" fill="none" xmlns="http://www.w3.org/2000/svg">
                                                            <path fill-rule="evenodd" clip-rule="evenodd" d="M5.25 1.5C3.17893 1.5 1.5 3.17893 1.5 5.25V12.75C1.5 14.8211 3.17893 16.5 5.25 16.5H12.75C14.8211 16.5 16.5 14.8211 16.5 12.75V5.25C16.5 3.17893 14.8211 1.5 12.75 1.5H5.25ZM5.625 2.625C3.96815 2.625 2.625 3.96815 2.625 5.625V12.375C2.625 14.0319 3.96815 15.375 5.625 15.375H12.375C14.0319 15.375 15.375 14.0319 15.375 12.375V5.625C15.375 3.96815 14.0319 2.625 12.375 2.625H5.625Z" fill="#CB5140" />
                                                            <path d="M13.5 5.625L7.3125 11.8125L4.5 9" stroke="#CB5140" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" />
                                                        </svg>
                                                    <?php else : ?>
                                                        <svg width="18" height="18" viewBox="0 0 18 18" fill="none" xmlns="http://www.w3.org/2000/svg">
                                                            <path fill-rule="evenodd" clip-rule="evenodd" d="M5.25 1.5C3.17893 1.5 1.5 3.17893 1.5 5.25V12.75C1.5 14.8211 3.17893 16.5 5.25 16.5H12.75C14.8211 16.5 16.5 14.8211 16.5 12.75V5.25C16.5 3.17893 14.8211 1.5 12.75 1.5H5.25ZM5.625 2.625C3.96815 2.625 2.625 3.96815 2.625 5.625V12.375C2.625 14.0319 3.96815 15.375 5.625 15.375H12.375C14.0319 15.375 15.375 14.0319 15.375 12.375V5.625C15.375 3.96815 14.0319 2.625 12.375 2.625H5.625Z" fill="#1D1D1D" />
                                                        </svg>
                                                    <?php endif; ?>
                                                </span>
                                                <span class="pl-sidebar__chip-label">Tất cả</span>
                                            </button>
                                            <?php foreach ($card['subcategories'] as $sub) :
                                                $is_sub_active = ($active_child_slug === $sub->slug);
                                                $chip_class    = $is_sub_active ? ' pl-sidebar__chip--active' : '';
                                            ?>
                                                <button class="pl-sidebar__chip<?= $chip_class; ?>" type="button"
                                                    data-filter-value="<?= esc_attr($sub->slug); ?>"
                                                    data-category-slug="<?= esc_attr($sub->slug); ?>">
                                                    <span class="pl-sidebar__chip-icon">
                                                        <?php if ($is_sub_active) : ?>
                                                            <svg width="18" height="18" viewBox="0 0 18 18" fill="none" xmlns="http://www.w3.org/2000/svg">
                                                                <path fill-rule="evenodd" clip-rule="evenodd" d="M5.25 1.5C3.17893 1.5 1.5 3.17893 1.5 5.25V12.75C1.5 14.8211 3.17893 16.5 5.25 16.5H12.75C14.8211 16.5 16.5 14.8211 16.5 12.75V5.25C16.5 3.17893 14.8211 1.5 12.75 1.5H5.25ZM5.625 2.625C3.96815 2.625 2.625 3.96815 2.625 5.625V12.375C2.625 14.0319 3.96815 15.375 5.625 15.375H12.375C14.0319 15.375 15.375 14.0319 15.375 12.375V5.625C15.375 3.96815 14.0319 2.625 12.375 2.625H5.625Z" fill="#CB5140" />
                                                                <path d="M13.5 5.625L7.3125 11.8125L4.5 9" stroke="#CB5140" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" />
                                                            </svg>
                                                        <?php else : ?>
                                                            <svg width="18" height="18" viewBox="0 0 18 18" fill="none" xmlns="http://www.w3.org/2000/svg">
                                                                <path fill-rule="evenodd" clip-rule="evenodd" d="M5.25 1.5C3.17893 1.5 1.5 3.17893 1.5 5.25V12.75C1.5 14.8211 3.17893 16.5 5.25 16.5H12.75C14.8211 16.5 16.5 14.8211 16.5 12.75V5.25C16.5 3.17893 14.8211 1.5 12.75 1.5H5.25ZM5.625 2.625C3.96815 2.625 2.625 3.96815 2.625 5.625V12.375C2.625 14.0319 3.96815 15.375 5.625 15.375H12.375C14.0319 15.375 15.375 14.0319 15.375 12.375V5.625C15.375 3.96815 14.0319 2.625 12.375 2.625H5.625Z" fill="#1D1D1D" />
                                                            </svg>
                                                        <?php endif; ?>
                                                    </span>
                                                    <span class="pl-sidebar__chip-label"><?= esc_html($sub->name); ?></span>
                                                </button>
                                            <?php endforeach; ?>
                                        </div>
                                    </div>
                                <?php endforeach; ?>
                            <?php endif; ?>
                        </div>

                    <?php endforeach; ?>

                </div>
                <!-- /.pl-sidebar__category-section -->

            </div>
            <!-- /.pl-sidebar__filter-box -->
        </aside>

    </div>
    <!-- /.pl-filter-drawer__body -->
</div>
<!-- /.pl-filter-drawer -->