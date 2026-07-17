# Tối ưu Order trong wp-admin — Chi tiết đơn & Tạo đơn thuê (one-screen / one-click)

## Context

Đây là shop cho thuê đồ cosplay. Order được tạo **tự động** qua `import-order.php`
(đồng bộ từ hệ thống Yun ngoài, `localhost:3000`) và mang nhiều meta đặc thù cho thuê:
ngày thuê/trả, số ngày, tiền cọc, phí tạm giữ (collateral), trạng thái lấy/trả, vận đơn…
HPOS đang bật (importer ghi thẳng vào bảng `wc_orders`).

Vì **khách tự thêm order** (không còn nhân viên nhập tay), màn hình sửa đơn WooCommerce
mặc định trong wp-admin trở nên thừa thãi (form nhập liệu nặng, meta `_yun_*` lộn xộn ở
Custom Fields, không thấy thông tin thuê ở đâu). Nhân viên giờ chỉ cần **xem nhanh + xử lý
1 chạm**. Mục tiêu: gom toàn bộ thông tin cần thiết vào **một panel gọn, một màn hình**, với
các thao tác one-click, và **trạng thái thuê chuyển từ select → radio**.

Hiện theme **chưa có** UI order nào ngoài WooCommerce mặc định (xác nhận qua grep:
chỉ `functions.php`, `inc/functions.php`, `import-order.php` đụng tới order; khối code cũ
hiển thị "số ngày thuê" trong admin đã bị comment ở `functions.php:255-484`).

## Mục tiêu (đã chốt) — 2 màn hình trong wp-admin

**Màn 1 — Chi tiết đơn** (màn sửa đơn WooCommerce, HPOS):
- **Hiển thị bắt buộc** (gom 1 panel): khách + liên hệ; **nguồn đơn**; ngày thuê/trả/số ngày;
  cọc & phí tạm giữ; sản phẩm + ảnh + tổng tiền.
- **One-click**: In / sao chép đơn; Gọi/nhắn khách; Đánh dấu cọc/phí đã thu;
  **Trạng thái đơn (WC)** + **Trạng thái giao nhận** — đều **dùng radio thay select**, lưu ngay.
- Phân biệt: **Trạng thái đơn** = lifecycle WooCommerce (pending → on-hold → processing →
  completed → cancelled); **Trạng thái giao nhận** = lấy/trả đồ (`_rental_status`).

**Màn 2 — Tạo đơn thuê mới** (trang admin riêng, 1 màn, tạo 1 chạm):
- Tìm khách theo **SĐT** (điền sẵn từ đơn cũ) hoặc nhập mới (tên, địa chỉ).
- Tìm & thêm **sản phẩm** + variation + số lượng.
- Chọn **ngày nhận/trả → tự tính số ngày** và tiền (đơn giá × SL × số ngày).
- Nhập **cọc / phí tạm giữ / ship / giảm giá / ghi chú**.
- Bấm **Tạo đơn** → tạo WC order đúng meta thuê rồi mở thẳng Màn 1.

## Kiến trúc & file

Tạo module admin mới theo directory-based convention, require trong `functions.php`
(nhóm "Feature-specific APIs", sau `inc/blog-api.php`):

```
inc/admin-order/
├── index.php              # bootstrap: require, enqueue, helper screen HPOS
├── detail.php             # metabox chi tiết + AJAX cập nhật trạng thái/cọc + in phiếu
├── create.php             # submenu "Tạo đơn thuê" + AJAX tìm khách + tạo đơn
├── order-builder.php      # okhub_build_rental_order() — dùng chung tạo tay & import
├── views/
│   ├── panel.php          # markup panel chi tiết
│   ├── create-form.php    # markup form tạo đơn
│   └── print.php          # HTML phiếu in gọn
└── assets/
    ├── detail.css / detail.js   # AJAX toggle/radio + call/copy/print
    └── create.css / create.js   # tìm SP/khách + tự tính tiền theo ngày
```

`functions.php` thêm: `require get_theme_file_path('/inc/admin-order/index.php');`

### 1. Phát hiện screen (HPOS-aware) — `inc/admin-order/index.php`

```php
function okhub_order_screen_id() {
    if ( class_exists('\Automattic\WooCommerce\Utilities\OrderUtil')
         && \Automattic\WooCommerce\Utilities\OrderUtil::custom_orders_table_usage_is_enabled() ) {
        return wc_get_page_screen_id('shop-order'); // 'woocommerce_page_wc-orders'
    }
    return 'shop_order';
}
```

### 2. Metabox gom thông tin (đặt lên đầu)

`add_meta_box('okhub-rental-panel', '🎭 Đơn thuê — Tổng quan', 'okhub_render_rental_panel',
okhub_order_screen_id(), 'normal', 'high')` trong action `add_meta_boxes`.

Callback chuẩn hoá object (HPOS truyền `WC_Order`, legacy truyền `WP_Post`):

```php
function okhub_render_rental_panel($post_or_order) {
    $order = $post_or_order instanceof WP_Post ? wc_get_order($post_or_order->ID) : $post_or_order;
    if ($order) include __DIR__ . '/views/panel.php';
}
```

### 3. Nội dung panel — `views/panel.php` (đọc từ order meta đã import)

- **Khách + liên hệ**: `$order->get_formatted_billing_full_name()`, `get_billing_phone()`,
  địa chỉ. Nút: **Gọi** (`tel:`), **Zalo** (`https://zalo.me/<phone>`), **Copy SĐT**.
- **Box "Trạng thái & nguồn"** — gom **3 nhóm radio** trong cùng 1 card, click là lưu ngay (AJAX):
  - *Trạng thái đơn (WC)*: Chờ xử lý `pending` → Tạm giữ `on-hold` → Đang xử lý `processing`
    → Hoàn thành `completed` → Đã hủy `cancelled` → gọi `$order->update_status()`.
  - *Trạng thái giao nhận*: 5 trạng thái `_rental_status` (`pending_pickup` → `picked_up`
    → `returned_complete` / `returned_missing` / `returned_damaged`, map `import-order.php:231-240`).
  - *Nguồn đơn*: radio **tự sinh từ data có sẵn** — các giá trị `_yun_source_order` distinct
    đang có trong đơn (helper `okhub_get_order_sources()`, **không hardcode**) → lưu meta `_yun_source_order`.
- **Thuê**: `_rental_start_date`, `_rental_end_date`, `_rental_days` (fallback `_yun_rental_*`).
- **Cọc & phí tạm giữ**: `_deposit_amount` + `_deposit_status` (paid/pending);
  `_collateral_amount` + `_collateral_status` (paid/unpaid). Toggle 1 chạm "Đã thu cọc" /
  "Đã hoàn phí" (AJAX).
- **Sản phẩm + ảnh + tổng**: loop `$order->get_items()`; ảnh dùng `$item->get_product()->get_image('thumbnail')`
  nếu map được, fallback meta `_yun_image` (URL ngoài). Hiện qty, đơn giá, thành tiền;
  tổng đơn `$order->get_formatted_order_total()`, shipping, discount, cọc.
- **Hành động**: nút **In phiếu** (mở cửa sổ in), **Copy đơn** (clipboard).

### 4. One-click AJAX — `inc/admin-order/index.php`

Một handler whitelist field, có nonce + capability:

```php
add_action('wp_ajax_okhub_update_order_meta', function () {
    check_ajax_referer('okhub_order_nonce', 'nonce');
    if (!current_user_can('manage_woocommerce')) wp_send_json_error('forbidden', 403);
    $order = wc_get_order(absint($_POST['order_id'] ?? 0));
    $field = sanitize_key($_POST['field'] ?? '');
    $value = sanitize_text_field(wp_unslash($_POST['value'] ?? ''));
    $allowed = [
        'order_status'       => ['pending','on-hold','processing','completed','cancelled'], // status WC
        '_rental_status'     => ['pending_pickup','picked_up','returned_complete','returned_missing','returned_damaged'],
        '_deposit_status'    => ['paid','pending'],
        '_collateral_status' => ['paid','unpaid'],
        '_yun_source_order'  => okhub_get_order_sources(),   // nguồn: distinct lấy từ data đã có
    ];
    if (!$order || !isset($allowed[$field]) || !in_array($value, $allowed[$field], true)) {
        wp_send_json_error('bad request', 400);
    }
    if ($field === 'order_status') {
        $order->update_status($value, 'OKHUB: ');           // đổi status WC + tự ghi note
    } else {
        $order->update_meta_data($field, $value);
        $order->add_order_note(sprintf('Cập nhật %s → %s (bởi %s)', $field, $value, wp_get_current_user()->display_name));
    }
    $order->save();
    wp_send_json_success(['field' => $field, 'value' => $value]);
});
```

`scripts.js`: click radio/toggle → `fetch(OKHUB_ORDER.ajaxurl, {nonce, order_id, field, value})`,
hiển thị trạng thái "Đã lưu". Call/copy/print thuần front-end.

**Nguồn đơn lấy từ data có sẵn** (không hardcode) — helper distinct, HPOS-aware, cache,
dùng chung cho radio (panel + form tạo đơn) và whitelist AJAX:

```php
function okhub_get_order_sources() {
    static $cache = null;
    if ($cache !== null) return $cache;
    global $wpdb;
    $table = \Automattic\WooCommerce\Utilities\OrderUtil::custom_orders_table_usage_is_enabled()
        ? $wpdb->prefix . 'wc_orders_meta' : $wpdb->postmeta;   // HPOS vs legacy
    $cache = $wpdb->get_col($wpdb->prepare(
        "SELECT DISTINCT meta_value FROM {$table} WHERE meta_key = %s AND meta_value <> ''",
        '_yun_source_order'
    ));
    return $cache;
}
```

### 5. Gọn lại màn hình ("one screen") — trong `add_meta_boxes` (priority cao)

- `remove_meta_box('woocommerce-order-downloads', okhub_order_screen_id(), 'normal');`
- `remove_meta_box('postcustom', okhub_order_screen_id(), 'normal');` (ẩn list `_yun_*` lộn xộn)
- `scripts.js` khi load: chuyển panel `#okhub-rental-panel` lên đầu `#normal-sortables` và
  **collapse** (`.closed`) panel `woocommerce-order-data` + `woocommerce-order-items`
  (giữ lại để chỉnh khi cần, nhưng không chiếm chỗ). Giữ Order actions (nút Update) + Order notes
  (lịch sử import).

### 6. Enqueue (chỉ admin, chỉ màn order)

```php
add_action('admin_enqueue_scripts', function ($hook) {
    $screen = get_current_screen();
    if (!$screen || $screen->id !== okhub_order_screen_id()) return;
    wp_enqueue_style('okhub-admin-order', get_theme_file_uri('/inc/admin-order/assets/styles.css'), [], THEME_VERSION);
    wp_enqueue_script('okhub-admin-order', get_theme_file_uri('/inc/admin-order/assets/scripts.js'), [], THEME_VERSION, true);
    wp_localize_script('okhub-admin-order', 'OKHUB_ORDER', [
        'ajaxurl' => admin_url('admin-ajax.php'),
        'nonce'   => wp_create_nonce('okhub_order_nonce'),
    ]);
});
```

### 7. In phiếu — `okhub_print_order` (admin-ajax) + `views/print.php`

Nút "In phiếu" mở `window.open('admin-ajax.php?action=okhub_print_order&order_id=…&nonce=…')`,
trả về HTML gọn (khách, thuê, cọc, items, tổng) rồi `window.print()`. Có nonce + capability.

## Màn 2 — Tạo đơn thuê mới (wp-admin)

### A. Trang admin riêng — `inc/admin-order/create.php`

`add_submenu_page('woocommerce', 'Tạo đơn thuê', 'Tạo đơn thuê', 'manage_woocommerce',
'okhub-create-order', 'okhub_render_create_form')`. Callback include `views/create-form.php`.
Form layout 1 màn: cột trái = khách + **nguồn & trạng thái đơn** + ngày thuê + bảng sản phẩm;
cột phải = phụ phí + tổng kết + nút Tạo đơn.
- **Nguồn đơn**: radio **tự sinh từ data có sẵn** (`okhub_get_order_sources()` — distinct
  `_yun_source_order` đang có), không hardcode.
- **Trạng thái đơn**: radio status WC (mặc định `processing` = Đang xử lý).
- **Cọc / phí tạm giữ**: ô nhập số tiền kèm **checkbox "Đã thu"** → set `_deposit_status` /
  `_collateral_status` = `paid` ngay khi tạo đơn (bỏ tick = `pending`/`unpaid`).

### B. Tìm khách theo SĐT — `wp_ajax_okhub_lookup_customer`

Khách thường là guest (đơn import set billing thẳng, không tạo WP user), nên tra theo
`_billing_phone` trên các đơn gần nhất thay vì `WC_Customer`:

```php
$orders = wc_get_orders(['billing_phone' => $phone, 'limit' => 1, 'orderby' => 'date', 'order' => 'DESC']);
// trả name + address để JS điền sẵn; không thấy → để nhập mới
```

### C. Tìm sản phẩm — dùng sẵn của WooCommerce

Dùng `<select class="wc-product-search" data-action="woocommerce_json_search_products_and_variations">`
(WC tự init select2 + AJAX trên trang admin). JS lấy `id` (product/variation) + đơn giá để dựng dòng.
Đơn giá ưu tiên meta `_sale_price_custom` (giá bán theme tự thêm ở `functions.php`), fallback `get_price()`.

### D. Tự tính tiền (JS — `assets/create.js`) — **xem trước**, đồng bộ logic hiện tại

`số ngày = max(1, diff(ngày_trả, ngày_nhận))` — **hiệu/exclusive, min 1**, khớp đúng
`WCRP\Helpers\DateHelper::calculate_days()` (`$start->diff($end)->days`) của plugin
`woocommerce-rental-pricing`. VD 20→23 = **3 ngày** (KHÔNG +1).
JS chỉ để **xem trước** trên màn: `thành tiền = đơn giá × SL × số ngày`, `tổng = Σ dòng + ship − giảm`.
Con số chính thức do **server tính** (mục E).

### E. Tạo đơn — `wp_ajax_okhub_create_rental_order` + `order-builder.php`

Validate (nonce + `manage_woocommerce`), gom input rồi gọi **helper dùng chung**
`okhub_build_rental_order($args)` — đặt cùng schema meta như `import-order.php`:
billing (tên/SĐT/địa chỉ); line items với **đơn giá gốc** + item meta `_rental_unit_price`;
shipping, discount; **status WC** (từ form, mặc định `processing`); meta đơn `_yun_source_order` (nguồn),
`_rental_start_date`/`_rental_end_date` (+ `_rental_days`), `_rental_status='pending_pickup'`,
`_deposit_amount`/`_deposit_status`, `_collateral_amount`/`_collateral_status`.

**Tính tiền → giao cho plugin (không nhân tay):** sau khi set ngày + `_rental_unit_price`, gọi
`$order->calculate_totals()` → hook `WCRP\Rental\RentalHooks::recalculate_prices` (chạy trên
`woocommerce_order_before_calculate_totals`) tự nhân `đơn giá × SL × số ngày` cho từng line.
Nhất quán với màn admin sửa đơn (plugin cũng recalc khi lưu). Đường import vẫn giữ cách set total
thủ công + tắt `calculate_totals()` cho nhanh (10k+ đơn) — chỉ path tạo tay mới gọi recalc.

Trả về `edit URL` của đơn → JS `window.location` sang **Màn 1**.

> Refactor nhẹ: tách phần set meta/line-item của `import-order.php:create_order_in_database()`
> ra `okhub_build_rental_order()` để import và tạo tay dùng chung (DRY), tránh lệch schema.

## Convention cần tuân

- CSS: **không dùng `aspect-ratio`, không `gap`**, dùng `margin` trên con thay thế;
  rem cho spacing, BEM-like class (`.okhub-rental__...`).
- PHP: prefix `okhub_`, escaping (`esc_html`/`esc_attr`/`esc_url`), nonce cho mọi write.
- File ≤ 500 dòng — tách `views/` nếu dài.
- Ảnh sản phẩm map được: dùng `$product->get_image()` (đi qua attachment). `_yun_image` là URL
  ngoài nên buộc dùng `<img>` thường.

## Verification (end-to-end)

1. Mở **WooCommerce → Orders** → mở 1 đơn đã import (có `_yun_*` meta).
2. Panel "🎭 Đơn thuê" nằm **trên cùng**, hiển thị đủ: khách+SĐT, ngày thuê/trả/số ngày,
   cọc + phí tạm giữ, danh sách item kèm ảnh + tổng tiền.
3. Click **radio trạng thái thuê** → thấy "Đã lưu"; reload → giữ nguyên (meta `_rental_status`).
4. Toggle **cọc/phí đã thu** → meta `_deposit_status`/`_collateral_status` đổi; có order note.
5. Nút **Gọi** (`tel:`), **Zalo**, **Copy SĐT**, **Copy đơn**, **In phiếu** hoạt động.
6. Downloads + Custom Fields đã ẩn; Order data/items đã collapse; Update vẫn lưu được.
7. **Tạo đơn:** vào *WooCommerce → Tạo đơn thuê*; gõ SĐT khách cũ → tự điền tên/địa chỉ;
   thêm sản phẩm; đổi ngày nhận/trả & SL → tiền tự tính lại.
8. Bấm **Tạo đơn** → tạo WC order đúng tổng + meta thuê, redirect sang Màn 1; kiểm tra
   số tiền khớp công thức `đơn giá × SL × số ngày`.

## Lưu ý / quyết định mở

- HPOS tắt → `okhub_order_screen_id()` tự fallback `'shop_order'`.
- Ẩn (không xoá) native items để vẫn refund/chỉnh khi cần — chỉ collapse.
- Trạng thái **đơn WooCommerce** (pending/processing/completed) vẫn ở panel order-data
  (collapse) — không gộp vào radio (radio chỉ cho *trạng thái thuê*). Muốn gộp luôn WC status
  sang radio thì mở rộng whitelist + thêm nhóm radio thứ hai.
