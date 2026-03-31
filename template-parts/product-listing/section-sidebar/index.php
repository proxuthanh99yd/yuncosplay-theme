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
            'is_active'      => ($current_category === $cat->slug),
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
                <path d="M18 6L6 18M6 6l12 12" stroke="#1C1C1C" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/>
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
                        <svg width="18" height="18" viewBox="0 0 18 18" fill="none" xmlns="http://www.w3.org/2000/svg">
                            <path d="M2 4.5H16M4.5 9H13.5M7 13.5H11" stroke="#1C1C1C" stroke-opacity="0.4" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/>
                        </svg>
                    </span>
                    <span class="pl-sidebar__section-label">Khoảng giá</span>
                </div>
            </div>

            <div class="pl-sidebar__slider-wrap">
                <div id="pl-price-slider"
                     data-min="100000"
                     data-max="10000000"
                     data-step="10000"></div>

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
                        <svg width="18" height="18" viewBox="0 0 18 18" fill="none" xmlns="http://www.w3.org/2000/svg">
                            <path d="M1.5 1.5H3L3.6 4.5M3.6 4.5H16.5L13.5 10.5H5.1M3.6 4.5L5.1 10.5M5.1 10.5L3.3 12.3C2.91 12.69 3.19 13.5 3.74 13.5H13.5M13.5 15.75C13.0858 15.75 12.75 16.0858 12.75 16.5C12.75 16.9142 13.0858 17.25 13.5 17.25C13.9142 17.25 14.25 16.9142 14.25 16.5C14.25 16.0858 13.9142 15.75 13.5 15.75ZM5.25 15.75C4.83579 15.75 4.5 16.0858 4.5 16.5C4.5 16.9142 4.83579 17.25 5.25 17.25C5.66421 17.25 6 16.9142 6 16.5C6 16.0858 5.66421 15.75 5.25 15.75Z" stroke="#1C1C1C" stroke-opacity="0.4" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/>
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
                                 data-has-children="<?= $card['has_children'] ? 'true' : 'false'; ?>"
                                 role="button"
                                 tabindex="0">
                                <div class="pl-sidebar__category-thumb<?= ($card['type'] === 'all' || empty($card['thumbnail_id'])) ? ' pl-sidebar__category-thumb--empty' : ''; ?>">
                                    <?php if ($card['type'] !== 'all' && !empty($card['thumbnail_id'])) : ?>
                                        <?= wp_get_attachment_image($card['thumbnail_id'], 'thumbnail', false, [
                                            'class'   => 'pl-sidebar__category-img',
                                            'alt'     => esc_attr($card['name']),
                                            'loading' => 'lazy',
                                        ]); ?>
                                    <?php else : ?>
                                        <svg width="24" height="24" viewBox="0 0 18 18" fill="none" xmlns="http://www.w3.org/2000/svg">
                                            <path d="M2 4.5H16M4.5 9H13.5M7 13.5H11" stroke="#1C1C1C" stroke-opacity="0.4" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/>
                                        </svg>
                                    <?php endif; ?>
                                </div>
                                <span class="pl-sidebar__category-name"><?= esc_html($card['name']); ?></span>
                            </div>
                        <?php endforeach; ?>
                    </div>

                    <!-- Triangle + Filter Panels cho hàng này -->
                    <?php if (!empty($row_slugs_with_children)) : ?>
                        <div class="pl-sidebar__triangle-row<?= $active_card_in_row ? ' pl-sidebar__triangle-row--visible' : ''; ?>">
                            <div class="pl-sidebar__triangle"></div>
                        </div>

                        <?php foreach ($row_cards as $card) :
                            if (!$card['has_children']) continue;

                            $panel_open_class = ($card['is_active']) ? ' pl-sidebar__filter-panel--open' : '';
                        ?>
                            <div class="pl-sidebar__filter-panel<?= $panel_open_class; ?>"
                                 data-panel-for="<?= esc_attr($card['slug']); ?>">
                                <div class="pl-sidebar__filter-row">
                                    <?php foreach ($card['subcategories'] as $sub) :
                                        $is_sub_active = ($current_category === $sub->slug);
                                        $chip_class    = $is_sub_active ? ' pl-sidebar__chip--active' : '';
                                    ?>
                                        <button class="pl-sidebar__chip<?= $chip_class; ?>"
                                                type="button"
                                                data-filter-value="<?= esc_attr($sub->slug); ?>"
                                                data-category-slug="<?= esc_attr($sub->slug); ?>">
                                            <span class="pl-sidebar__chip-icon">
                                                <?php if ($is_sub_active) : ?>
                                                    <svg width="18" height="18" viewBox="0 0 18 18" fill="none" xmlns="http://www.w3.org/2000/svg">
                                                        <rect width="18" height="18" rx="4" fill="#CB5140"/>
                                                        <path d="M4 9L7.5 12.5L14 6" stroke="white" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                                                    </svg>
                                                <?php else : ?>
                                                    <svg width="18" height="18" viewBox="0 0 18 18" fill="none" xmlns="http://www.w3.org/2000/svg">
                                                        <rect x="0.5" y="0.5" width="17" height="17" rx="3.5" stroke="#1C1C1C"/>
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
