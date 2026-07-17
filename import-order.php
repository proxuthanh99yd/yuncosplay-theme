<?php
// register res api update order
// add_action('rest_api_init', 'register_update_order_api');
// function register_update_order_api()
// {
//     register_rest_route('api/v1', '/orders', array(
//         'methods' => 'POST',
//         'callback' => 'import_orders_handler',
//     ));
// }

function import_orders_handler($request)
{
    $limit = intval($request->get_param('limit') ?: 50);
    $page  = intval($request->get_param('page')  ?: 1);

    $api_url  = 'http://localhost:3535/api/orders?limit=' . $limit . '&page=' . $page;
    $response = wp_remote_get($api_url, ['timeout' => 60]);

    if (is_wp_error($response)) {
        return rest_ensure_response(['success' => false, 'message' => $response->get_error_message()]);
    }

    $body = json_decode(wp_remote_retrieve_body($response), true);

    if (empty($body['success']) || empty($body['orders'])) {
        return rest_ensure_response(['success' => false, 'message' => 'No orders found']);
    }

    $results = ['imported' => 0, 'skipped' => 0, 'errors' => []];

    foreach ($body['orders'] as $order_data) {
        $result = create_order_in_database($order_data);
        if ($result === 'skipped') {
            $results['skipped']++;
        } elseif ($result === true) {
            $results['imported']++;
        } else {
            $results['errors'][] = $result;
        }
    }

    return rest_ensure_response([
        'success'  => true,
        'page'     => $page,
        'limit'    => $limit,
        'imported' => $results['imported'],
        'skipped'  => $results['skipped'],
        'errors'   => $results['errors'],
    ]);
}

function create_order_in_database($data)
{
    // --- Dedup: skip nếu order_uuid (order_id từ API) đã tồn tại ---
    if (!empty($data['order_id'])) {
        $existing = wc_get_orders([
            'meta_key'   => '_order_uuid',
            'meta_value' => $data['order_id'],
            'limit'      => 1,
            'return'     => 'ids',
        ]);
        if (!empty($existing)) {
            return 'skipped';
        }
    }

    // --- Map status ---
    $status = map_order_status($data);

    // --- Tạo order ---
    $order = wc_create_order(['status' => $status]);
    if (is_wp_error($order)) {
        return 'ERROR create_order: ' . $order->get_error_message();
    }

    // --- Billing (customer info) ---
    $customer = $data['customer'] ?? [];
    $da       = $data['delivery_address'] ?? [];

    $billing_first = $customer['customer_first_name'] ?? '';
    $billing_last  = $customer['customer_last_name']  ?? '';
    $billing_email = $customer['customer_email']       ?? '';
    $billing_phone = $customer['customer_phone_number'] ?? '';

    // Validate email - trim và kiểm tra
    $billing_email = trim($billing_email);
    if ($billing_email === '' || !is_email($billing_email)) {
        $billing_email = 'ngochaiit257@gmail.com';
    }

    // Dùng delivery_address nếu có
    $billing_address = $da['address_detail'] ?? ($customer['customer_address'] ?? '');
    $billing_city    = $da['district_name']  ?? '';
    $billing_state   = $da['province_name']  ?? '';

    error_log('Billing Email before set: ' . $billing_email . ' | Order ID: ' . ($data['order_id'] ?? 'N/A'));

    $order->set_billing_first_name($billing_first);
    $order->set_billing_last_name($billing_last);
    $order->set_billing_email($billing_email);
    $order->set_billing_phone($billing_phone);
    $order->set_billing_address_1($billing_address);
    $order->set_billing_city($billing_city);
    $order->set_billing_state($billing_state);
    $order->set_billing_country('VN');

    // Shipping address (giống billing)
    $ship_first = $da['first_name'] ?? $billing_first;
    $ship_last  = $da['last_name']  ?? $billing_last;
    $ship_phone = $da['phone_number'] ?? $billing_phone;
    $order->set_shipping_first_name($ship_first);
    $order->set_shipping_last_name($ship_last);
    $order->set_shipping_phone($ship_phone);
    $order->set_shipping_address_1($da['address_detail'] ?? $billing_address);
    $order->set_shipping_city($da['district_name'] ?? $billing_city);
    $order->set_shipping_state($da['province_name'] ?? $billing_state);
    $order->set_shipping_country('VN');

    // --- Payment method ---
    $payment_desc = $data['payment_method_description'] ?? '';
    $payment_id   = (strpos(strtolower($payment_desc), 'cod') !== false || strpos($payment_desc, 'tiền mặt') !== false)
        ? 'cod' : 'bacs';
    $order->set_payment_method($payment_id);
    $order->set_payment_method_title($payment_desc ?: $payment_id);

    // Số ngày thuê — dùng để tính line total cho plugin rental pricing
    $rental_days = max(1, intval($data['number_of_rental_date'] ?? 1));

    // --- Order items: sản phẩm variation ---
    foreach ($data['product_variation_in_orders'] ?? [] as $item) {
        $product = find_product_by_name(
            $item['product_name'] ?? '',
            $item['product_variation_name'] ?? ''
        );

        $unit_price = floatval($item['product_variation_price'] ?? 0);
        $qty        = intval($item['product_variation_quantity'] ?? 1);
        $line_total = $unit_price * $qty * $rental_days;

        if ($product && $product->exists()) {
            $line_item = new WC_Order_Item_Product();
            $line_item->set_product($product);
            $line_item->set_quantity($qty);
            $line_item->set_subtotal($line_total);
            $line_item->set_total($line_total);
            $line_item->add_meta_data('_rental_unit_price', $unit_price);
            // $line_item->add_meta_data('_yun_unit_price',    $unit_price);
            // $line_item->add_meta_data('_yun_unit_name',     $item['unit_name'] ?? '');
            $order->add_item($line_item);
        } else {
            // Product không tồn tại trong Woo → tạo line item thủ công
            $line_item = new WC_Order_Item_Product();
            $line_item->set_name(
                ($item['product_name'] ?? 'Unknown') .
                    (empty($item['product_variation_name']) || $item['product_variation_name'] === 'Default Title'
                        ? '' : ' - ' . $item['product_variation_name'])
            );
            $line_item->set_quantity($qty);
            $line_item->set_subtotal($line_total);
            $line_item->set_total($line_total);
            $line_item->add_meta_data('_rental_unit_price',          $unit_price);
            $line_item->add_meta_data('_yun_product_variation_id',   $item['product_variation_id'] ?? '');
            $line_item->add_meta_data('_yun_unit_price',             $unit_price);
            $line_item->add_meta_data('_yun_unit_name',              $item['unit_name'] ?? '');
            $line_item->add_meta_data('_yun_image',                  $item['product_variation_image'] ?? '');
            $order->add_item($line_item);
        }
    }

    // --- Order items: sản phẩm custom ---
    foreach ($data['custom_product_in_orders'] ?? [] as $item) {
        $unit_price = floatval($item['custom_product_in_order_price'] ?? 0);
        $qty        = intval($item['custom_product_in_order_quantity'] ?? 1);
        $line_total = $unit_price * $qty * $rental_days;

        $line_item = new WC_Order_Item_Product();
        $line_item->set_name($item['custom_product_in_order_name'] ?? 'Custom Product');
        $line_item->set_quantity($qty);
        $line_item->set_subtotal($line_total);
        $line_item->set_total($line_total);
        $line_item->add_meta_data('_rental_unit_price', $unit_price);
        $line_item->add_meta_data('_yun_custom_product', true);
        $line_item->add_meta_data('_yun_unit_name', $item['unit_name'] ?? '');
        $order->add_item($line_item);
    }

    // --- Shipping ---
    $shipping_total = floatval($data['cost_of_shipping'] ?? 0);
    if ($shipping_total > 0) {
        $shipping_item = new WC_Order_Item_Shipping();
        $shipping_item->set_method_title($data['name_of_shipping'] ?: 'Phí vận chuyển');
        $shipping_item->set_total($shipping_total);
        $order->add_item($shipping_item);
    }

    // --- Discount ---
    $discount = floatval($data['discount_amount'] ?? 0);
    if ($discount > 0) {
        $coupon_code = $data['coupon']['coupon_code'] ?? ('yun-discount-' . $data['order_id']);
        $order->set_discount_total($discount);
        $order->update_meta_data('_yun_discount_reason', $data['reason_of_promotion'] ?? '');
    }

    // --- Dates ---
    $order->set_date_created(date('Y-m-d H:i:s', strtotime($data['create_datetime'])));

    // Cập nhật ngày sửa đổi nếu có update_datetime

    // --- Customer note ---
    if (!empty($data['order_note'])) {
        $order->set_customer_note($data['order_note']);
    }

    // --- Meta: rental / deposit / source ---
    $order->update_meta_data('_yun_order_code',      $data['order_code'] ?? '');
    $order->update_meta_data('_yun_order_id',        $data['order_id'] ?? '');
    $order->update_meta_data('_yun_source_order',    $data['source_order'] ?? '');
    $order->update_meta_data('_yun_rental_start',    $data['rental_datetime'] ?? '');
    $order->update_meta_data('_yun_rental_end',      $data['return_datetime'] ?? '');
    $order->update_meta_data('_yun_rental_days',     $data['number_of_rental_date'] ?? 0);

    // Meta keys dùng bởi plugin woocommerce-rental-pricing (RentalMeta)
    $rental_start_date = !empty($data['rental_datetime'])
        ? date('Y-m-d', strtotime($data['rental_datetime'])) : '';
    $rental_end_date = !empty($data['return_datetime'])
        ? date('Y-m-d', strtotime($data['return_datetime'])) : '';
    $order->update_meta_data('_rental_start_date', $rental_start_date);
    $order->update_meta_data('_rental_end_date',   $rental_end_date);
    $order->update_meta_data('_rental_days',       intval($data['number_of_rental_date'] ?? 0));
    $rental_status = [
        1 => 'pending_pickup',
        2 => 'picked_up',
        3 => 'returned_complete',
        4 => 'returned_missing',
        5 => 'returned_damaged',
    ];


    $order->update_meta_data('_rental_status',      $rental_status[$data['getting_status_id']] ?? '');

    // Deposit (standard keys for plugin)
    $deposit_amount = floatval($data['deposit_amount'] ?? 0);
    $order->update_meta_data('_deposit_amount', $deposit_amount);
    $order->update_meta_data('_deposit_paid_at', $data['deposit_datetime'] ?? '');
    $order->update_meta_data('_deposit_status', $deposit_amount > 0 ? 'paid' : 'pending');

    // Collateral (standard keys for plugin)
    $collateral_amount = floatval($data['temporary_fee'] ?? 0);
    $order->update_meta_data('_collateral_amount', $collateral_amount);
    $order->update_meta_data('_collateral_paid', $collateral_amount);
    $order->update_meta_data('_collateral_status', $collateral_amount > 0 ? 'paid' : 'unpaid');

    // Legacy keys (keep for compatibility)
    $order->update_meta_data('_yun_deposit_amount',  $deposit_amount);
    $order->update_meta_data('_yun_deposit_datetime', $data['deposit_datetime'] ?? '');
    $order->update_meta_data('_yun_temporary_fee',   $collateral_amount);
    $order->update_meta_data('_yun_incurred_amount', $data['incurred_amount'] ?? 0);
    $order->update_meta_data('_yun_incurred_reason', $data['reason_of_incurred_amount'] ?? '');
    $order->update_meta_data('_yun_total_cost',      $data['total_cost_of_order'] ?? 0);
    $order->update_meta_data('_yun_getting_status',  $data['getting_status_description'] ?? '');
    $order->update_meta_data('_yun_customer_id',     $data['customer']['customer_user_id'] ?? '');

    // Meta: transport / vận đơn
    if (!empty($data['transport'])) {
        $t = $data['transport'];
        $order->update_meta_data('_yun_waybill_code',     $t['waybill_code'] ?? '');
        $order->update_meta_data('_yun_transport_method', $t['transport_method'] ?? '');
        $order->update_meta_data('_yun_transport_service', $t['transport_service'] ?? '');
        $order->update_meta_data('_yun_shipping_fee',     $t['shipping_fee'] ?? 0);
    }

    // --- calculate_totals: TẮT để tránh slow trên 10k+ orders ---
    // $order->calculate_totals();
    // Set thủ công thay thế
    $order->set_shipping_total($shipping_total);
    $order->set_discount_total($discount);
    $order->set_total(floatval($data['total_cost_of_order'] ?? 0));

    $order->save();
    $order_id = $order->get_id();

    // --- Lưu order_uuid để dedup lần sau ---
    if (!empty($data['order_id'])) {
        $order->update_meta_data('_order_uuid', $data['order_id']);
        error_log('Order UUID: ' . $data['order_id']);
        $order->save();
    }

    // --- Order logs → Order notes ---
    foreach ($data['order_logs'] ?? [] as $log) {
        $note = wp_strip_all_tags($log['order_log_description'] ?? '');
        if (empty($note)) continue;

        $date_str = $log['order_log_create_datetime'] ?? '';
        wp_insert_comment([
            'comment_post_ID'  => $order_id,
            'comment_author'   => 'Yuncosplay Import',
            'comment_content'  => $note,
            'comment_type'     => 'order_note',
            'comment_approved' => 1,
            'comment_date'     => $date_str ? date('Y-m-d H:i:s', strtotime($date_str)) : current_time('mysql'),
        ]);
    }

    // Cập nhật date_updated_gmt trực tiếp vào database
    if (!empty($data['update_datetime'])) {
        global $wpdb;

        $timestamp = strtotime($data['update_datetime']);
        $gmt_date  = gmdate('Y-m-d H:i:s', $timestamp);

        $wpdb->update(
            $wpdb->prefix . 'wc_orders',
            array(
                'date_updated_gmt' => $gmt_date
            ),
            array(
                'id' => $order_id
            ),
            array(
                '%s'
            ),
            array(
                '%d'
            )
        );

        error_log("Updated date_updated_gmt for order $order_id: $gmt_date");
    }

    return true;
}

/**
 * Tìm WooCommerce product/variation theo tên sản phẩm + tên variation.
 *
 * Ưu tiên:
 *  1. Tìm variation khớp chính xác cả product_name + variation_name
 *  2. Nếu variation_name = "Default Title" hoặc rỗng → trả về product cha
 *  3. Không tìm thấy → trả về null (line item sẽ được tạo thủ công)
 *
 * Cache bằng static array để tránh query lặp khi cùng tên xuất hiện nhiều lần.
 */
function find_product_by_name(string $product_name, string $variation_name = ''): ?WC_Product
{
    static $cache = [];

    $cache_key = md5($product_name . '|' . $variation_name);
    if (array_key_exists($cache_key, $cache)) {
        return $cache[$cache_key];
    }

    $product_name   = trim($product_name);
    $variation_name = trim($variation_name);

    if (empty($product_name)) {
        return $cache[$cache_key] = null;
    }

    // Tìm post theo tên chính xác (post_title)
    $posts = get_posts([
        'post_type'      => 'product',
        'post_status'    => 'any',
        'posts_per_page' => 1,
        'title'          => $product_name,
        'fields'         => 'ids',
    ]);

    if (empty($posts)) {
        return $cache[$cache_key] = null;
    }

    $parent_id = $posts[0];

    // Nếu không có variation name hoặc là Default Title → trả về product cha
    if (empty($variation_name) || $variation_name === 'Default Title') {
        return $cache[$cache_key] = wc_get_product($parent_id) ?: null;
    }

    // Tìm variation khớp tên trong các variation của product cha
    $variations = get_posts([
        'post_type'      => 'product_variation',
        'post_status'    => 'any',
        'post_parent'    => $parent_id,
        'posts_per_page' => -1,
        'fields'         => 'ids',
    ]);

    foreach ($variations as $var_id) {
        $variation = wc_get_product($var_id);
        if (!$variation) continue;

        // So sánh tên variation (attributes ghép lại)
        $attr_string = implode(', ', $variation->get_variation_attributes());
        if (
            stripos($attr_string, $variation_name) !== false ||
            stripos($variation_name, $attr_string) !== false
        ) {
            return $cache[$cache_key] = $variation;
        }

        // Fallback: so sánh theo variation name slug
        if (stripos($variation->get_name(), $variation_name) !== false) {
            return $cache[$cache_key] = $variation;
        }
    }

    // Không tìm thấy variation phù hợp → trả về product cha
    return $cache[$cache_key] = wc_get_product($parent_id) ?: null;
}

function map_order_status($data)
{
    // Draft order - skip import
    if (!empty($data['draft_status'])) {
        return 'checkout-draft';
    }

    // Success: getting_status_id >= 3 AND payment_status = true → completed
    $getting_status_id = intval($data['getting_status_id'] ?? 0);
    $payment_status = !empty($data['payment_status']);
    if ($getting_status_id >= 3 && $payment_status) {
        return 'completed';
    }

    // Completed status explicitly set
    if (!empty($data['completed_status'])) {
        return 'completed';
    }

    // Confirmed and paid → processing
    if (!empty($data['confirm_order_status']) && !empty($data['payment_status'])) {
        return 'processing';
    }

    // Confirmed but not paid → on-hold
    if (!empty($data['confirm_order_status'])) {
        return 'on-hold';
    }

    // Not paid → pending
    if (empty($data['payment_status'])) {
        return 'pending';
    }

    return 'pending';
}