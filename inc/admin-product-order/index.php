<?php

/**
 * Admin — Thứ tự hiển thị sản phẩm (order_index)
 *
 * Cho phép nhân viên "đánh số thứ tự từng SP" để chọn SP nào lên đầu trang danh mục.
 * Ngữ nghĩa: số nhỏ hiển thị trước (1 = đầu tiên); để trống = không ghim (xếp sau
 * theo SP mới nhất). Khớp với okhub_product_order_clauses() ở inc/helpers.php.
 *
 * Gồm 3 điểm chạm, cùng ghi 1 meta 'order_index':
 *   1. Cột "Thứ tự" (có ô nhập, auto-save AJAX, sort được) ở màn Products → All Products.
 *   2. Field "Thứ tự hiển thị" trong panel Product data khi sửa 1 SP.
 *   3. Handler AJAX lưu chung + enqueue asset (chỉ màn danh sách SP).
 *
 * Phạm vi: toàn shop (global) — 1 SP 1 số, áp dụng ở mọi danh mục nó thuộc về.
 *
 * @package OKHUB
 */

if (!defined('ABSPATH')) {
    exit;
}

const OKHUB_ORDER_INDEX_META = 'order_index';
const OKHUB_ORDER_INDEX_COL  = 'okhub_order_index';

/**
 * Đọc order_index của 1 SP. Trả về '' nếu chưa ghim (trống hoặc <= 0).
 *
 * @param int $product_id
 * @return string Chuỗi số nguyên >= 1, hoặc '' khi chưa ghim.
 */
function okhub_get_order_index($product_id)
{
    $raw = get_post_meta((int) $product_id, OKHUB_ORDER_INDEX_META, true);
    if ($raw === '' || $raw === null) {
        return '';
    }
    $val = (int) $raw;
    return $val >= 1 ? (string) $val : '';
}

/**
 * Ghi order_index. Trống/<=0 -> xoá meta (coi như bỏ ghim) để DB gọn.
 *
 * @param int        $product_id
 * @param int|string $value
 * @return string Giá trị đã lưu ('' nếu bỏ ghim).
 */
function okhub_set_order_index($product_id, $value)
{
    $product_id = (int) $product_id;
    $val = (int) $value;

    if ($val < 1) {
        delete_post_meta($product_id, OKHUB_ORDER_INDEX_META);
        return '';
    }

    update_post_meta($product_id, OKHUB_ORDER_INDEX_META, $val);
    return (string) $val;
}

/* -------------------------------------------------------------------------
 * 1. Cột "Thứ tự" trong danh sách sản phẩm
 * ---------------------------------------------------------------------- */

/**
 * Chèn cột "Thứ tự" ngay sau cột tên SP.
 */
add_filter('manage_edit-product_columns', function ($columns) {
    $new = [];
    foreach ($columns as $key => $label) {
        $new[$key] = $label;
        if ($key === 'name') {
            $new[OKHUB_ORDER_INDEX_COL] = 'Thứ tự';
        }
    }
    // Fallback nếu không có cột 'name' (đề phòng WooCommerce đổi key).
    if (!isset($new[OKHUB_ORDER_INDEX_COL])) {
        $new[OKHUB_ORDER_INDEX_COL] = 'Thứ tự';
    }
    return $new;
});

/**
 * Render ô nhập số trong cột. Auto-save qua JS khi đổi giá trị.
 */
add_action('manage_product_posts_custom_column', function ($column, $post_id) {
    if ($column !== OKHUB_ORDER_INDEX_COL) {
        return;
    }
    $value = okhub_get_order_index($post_id);
    printf(
        '<input type="number" class="okhub-oi-input" min="1" step="1" inputmode="numeric"'
            . ' data-id="%d" value="%s" placeholder="—" aria-label="Thứ tự hiển thị" />'
            . '<span class="okhub-oi-status" aria-hidden="true"></span>',
        (int) $post_id,
        esc_attr($value)
    );
}, 10, 2);

/**
 * Cho phép sort theo cột.
 */
add_filter('manage_edit-product_sortable_columns', function ($columns) {
    $columns[OKHUB_ORDER_INDEX_COL] = OKHUB_ORDER_INDEX_COL;
    return $columns;
});

/**
 * Khi bấm sort cột "Thứ tự": SP đã ghip (order_index >= 1) đứng nhóm trên, sắp theo
 * số; SP chưa ghim xuống dưới. Dùng LEFT JOIN để không loại SP thiếu meta.
 */
add_action('pre_get_posts', function ($query) {
    if (!is_admin() || !$query->is_main_query()) {
        return;
    }
    if ($query->get('post_type') !== 'product') {
        return;
    }
    if ($query->get('orderby') !== OKHUB_ORDER_INDEX_COL) {
        return;
    }

    $dir = strtoupper($query->get('order')) === 'DESC' ? 'DESC' : 'ASC';

    // Giới hạn đúng query đang chạy (so khớp object) để không đụng query phụ khác.
    add_filter('posts_clauses', function ($clauses, $wp_query) use ($dir, $query) {
        if ($wp_query !== $query) {
            return $clauses;
        }
        global $wpdb;
        $clauses['join'] .= " LEFT JOIN {$wpdb->postmeta} AS okhub_oi_admin"
            . " ON (okhub_oi_admin.post_id = {$wpdb->posts}.ID AND okhub_oi_admin.meta_key = '" . OKHUB_ORDER_INDEX_META . "')";
        $clauses['orderby'] =
            "CASE WHEN okhub_oi_admin.meta_value IS NULL OR CAST(okhub_oi_admin.meta_value AS SIGNED) < 1 THEN 1 ELSE 0 END ASC,"
            . " CAST(okhub_oi_admin.meta_value AS SIGNED) {$dir},"
            . " {$wpdb->posts}.post_date DESC";
        return $clauses;
    }, 10, 2);
});

/* -------------------------------------------------------------------------
 * 2. Field "Thứ tự hiển thị" trong panel Product data (sửa 1 SP)
 * ---------------------------------------------------------------------- */

add_action('woocommerce_product_options_advanced', function () {
    echo '<div class="options_group">';
    woocommerce_wp_text_input([
        'id'                => OKHUB_ORDER_INDEX_META,
        'label'             => 'Thứ tự hiển thị',
        'type'              => 'number',
        'custom_attributes' => ['step' => '1', 'min' => '1'],
        'desc_tip'          => true,
        'description'       => 'Số nhỏ hiển thị trước ở trang danh mục (1 = đầu tiên). '
            . 'Để trống = không ghim, xếp sau theo sản phẩm mới nhất.',
        'value'             => okhub_get_order_index(get_the_ID()),
    ]);
    echo '</div>';
});

add_action('woocommerce_admin_process_product_object', function ($product) {
    if (!isset($_POST[OKHUB_ORDER_INDEX_META])) {
        return;
    }
    okhub_set_order_index($product->get_id(), wp_unslash($_POST[OKHUB_ORDER_INDEX_META]));
});

/* -------------------------------------------------------------------------
 * 3. AJAX auto-save + enqueue asset
 * ---------------------------------------------------------------------- */

add_action('wp_ajax_okhub_save_order_index', function () {
    check_ajax_referer('okhub_order_index', 'nonce');

    if (!current_user_can('edit_products')) {
        wp_send_json_error('forbidden', 403);
    }

    $product_id = isset($_POST['product_id']) ? absint($_POST['product_id']) : 0;
    if (!$product_id || get_post_type($product_id) !== 'product') {
        wp_send_json_error('invalid product', 400);
    }

    $saved = okhub_set_order_index($product_id, $_POST['value'] ?? '');

    wp_send_json_success(['product_id' => $product_id, 'value' => $saved]);
});

add_action('admin_enqueue_scripts', function ($hook) {
    if ($hook !== 'edit.php') {
        return;
    }
    $screen = get_current_screen();
    if (!$screen || $screen->post_type !== 'product') {
        return;
    }

    $ver = defined('THEME_VERSION') ? THEME_VERSION : wp_get_theme()->get('Version');

    wp_enqueue_style(
        'okhub-admin-product-order',
        get_theme_file_uri('/inc/admin-product-order/assets/styles.css'),
        [],
        $ver
    );
    wp_enqueue_script(
        'okhub-admin-product-order',
        get_theme_file_uri('/inc/admin-product-order/assets/scripts.js'),
        [],
        $ver,
        true
    );
    wp_localize_script('okhub-admin-product-order', 'OKHUB_ORDER_INDEX', [
        'ajaxurl' => admin_url('admin-ajax.php'),
        'nonce'   => wp_create_nonce('okhub_order_index'),
    ]);
});
