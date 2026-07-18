<?php
require get_theme_file_path('/inc/functions.php');
require get_theme_file_path('/inc/helpers.php');
require get_theme_file_path('/import-assets/reset-css-js.php');
require get_theme_file_path('/import-assets/import-css-js.php');
require get_theme_file_path('/inc/perf-dequeue.php');
require get_theme_file_path('/inc/ajax.php');
require get_theme_file_path('/inc/api.php');
require get_theme_file_path('/inc/shortcodes.php');
require get_theme_file_path('/import-order.php');
require get_theme_file_path('/inc/product-api.php');
require get_theme_file_path('/inc/blog-api.php');
require get_theme_file_path('/template-parts/contact-page/acf.php');
require get_theme_file_path('/inc/schema.php');

// Hook cho sản phẩm đơn giản (simple products)
add_action(
    'woocommerce_product_options_pricing',
    'cosplay_add_rental_field_under_regular_price'
);

// Hook cho sản phẩm biến thể (variable products)
add_action(
    'woocommerce_variation_options_pricing',
    'cosplay_add_rental_field_to_variation',
    10,
    3
);

function cosplay_add_rental_field_under_regular_price()
{

    echo '<div class="options_group">';

    woocommerce_wp_text_input([
        'id'                => '_sale_price_custom',
        'label'             => 'Giá bán (₫)',
        'type'              => 'text',
        'class'             => 'short wc_input_price',
        'custom_attributes' => [
            'step' => '0.01',
            'min'  => '0'
        ],
        'desc_tip'          => true,
        'description'       => 'Giá bán sản phẩm.'
    ]);

    echo '</div>';
}

// Function cho sản phẩm biến thể
function cosplay_add_rental_field_to_variation($loop, $variation_data, $variation)
{
    echo '<div class="options_group">';
    // Sale price custom cho biến thể
    woocommerce_wp_text_input([
        'id'            => "_sale_price_custom_loop{$loop}",
        'label'         => __('Giá bán (₫)', 'woocommerce'),
        'type'          => 'text',
        'class'         => 'short wc_input_price',
        'wrapper_class' => 'form-field form-row form-row-full',
        'custom_attributes' => [
            'step' => '0.01',
            'min'  => '0'
        ],
        'value'         => get_post_meta($variation->ID, '_sale_price_custom', true)
    ]);
    echo '</div>';
}

add_action(
    'woocommerce_admin_process_product_object',
    'cosplay_save_rental_fields'
);

function cosplay_save_rental_fields($product)
{

    if (isset($_POST['_sale_price_custom'])) {
        $product->update_meta_data(
            '_sale_price_custom',
            wc_format_decimal($_POST['_sale_price_custom'])
        );
    }
}

// Lưu meta cho biến thể
add_action('woocommerce_save_product_variation', 'cosplay_save_variation_rental_fields', 10, 2);

function cosplay_save_variation_rental_fields($variation_id, $i)
{
    if (isset($_POST["_sale_price_custom_loop{$i}"])) {
        update_post_meta($variation_id, '_sale_price_custom', wc_format_decimal($_POST["_sale_price_custom_loop{$i}"]));
    }
}

// ================================
// Replace base64 images in post content
// ================================

add_action('pmxi_saved_post', 'ks_handle_base64_images', 10, 3);

function ks_handle_base64_images($post_id, $xml, $is_update)
{
    // Chỉ áp dụng cho các post type cần thiết
    if (!in_array(get_post_type($post_id), ['post', 'page', 'product'])) {
        return;
    }

    if (!current_user_can('edit_post', $post_id)) {
        return;
    }

    $content = get_post_field('post_content', $post_id);
    if (empty($content)) return;

    libxml_use_internal_errors(true);

    $dom = new DOMDocument('1.0', 'UTF-8');
    $dom->loadHTML(
        mb_convert_encoding($content, 'HTML-ENTITIES', 'UTF-8')
    );

    $images = $dom->getElementsByTagName('img');

    if (!$images->length) return;

    require_once ABSPATH . 'wp-admin/includes/file.php';
    require_once ABSPATH . 'wp-admin/includes/media.php';
    require_once ABSPATH . 'wp-admin/includes/image.php';

    foreach ($images as $img) {

        $src = $img->getAttribute('src');

        // Chỉ xử lý base64
        if (!str_starts_with($src, 'data:image')) {
            continue;
        }

        // Lấy filename từ data-filename
        $filename = $img->getAttribute('data-filename');

        if (!$filename) {
            $filename = 'import_' . time() . '.jpg';
        }

        $filename = sanitize_file_name($filename);

        // Check ảnh đã tồn tại chưa
        $attach_id = ks_find_attachment_by_filename($filename);

        if (!$attach_id) {

            // Parse base64
            if (!preg_match('/^data:image\/(\w+);base64,/', $src, $type)) {
                continue;
            }

            $image_type = strtolower($type[1]);

            $data = substr($src, strpos($src, ',') + 1);
            $data = base64_decode($data);

            if (!$data) continue;

            // Upload file
            $upload = wp_upload_bits($filename, null, $data);

            if (!empty($upload['error'])) continue;

            // Tạo attachment
            $attachment = [
                'post_mime_type' => 'image/' . $image_type,
                'post_title' => pathinfo($filename, PATHINFO_FILENAME),
                'post_status' => 'inherit',
                'post_parent' => $post_id
            ];

            $attach_id = wp_insert_attachment(
                $attachment,
                $upload['file'],
                $post_id
            );

            if (is_wp_error($attach_id)) continue;

            $attach_data = wp_generate_attachment_metadata(
                $attach_id,
                $upload['file']
            );

            wp_update_attachment_metadata($attach_id, $attach_data);
            // Set ALT chuẩn SEO
            update_post_meta(
                $attach_id,
                '_wp_attachment_image_alt',
                pathinfo($filename, PATHINFO_FILENAME)
            );
        }

        // Lấy URL ảnh
        $image_url = wp_get_attachment_url($attach_id);

        if (!$image_url) continue;

        // Build class chuẩn WP Classic
        $classes = trim($img->getAttribute('class') . ' wp-image-' . $attach_id . ' alignnone');

        // Replace attributes
        $img->setAttribute('src', $image_url);
        $img->setAttribute('class', $classes);
        $img->setAttribute('alt', get_the_title($attach_id));
        $img->removeAttribute('data-filename');

        // Gắn parent cho ảnh cũ nếu chưa có
        if (!get_post_field('post_parent', $attach_id)) {
            wp_update_post([
                'ID' => $attach_id,
                'post_parent' => $post_id
            ]);
        }
    }

    // Lưu lại content
    $new_content = $dom->saveHTML();
    $new_content = preg_replace('~<(?:!DOCTYPE| /?(?:html|head|body))[^>]*>\s*~i', '', $new_content);

    wp_update_post([
        'ID' => $post_id,
        'post_content' => $new_content
    ]);
}


/**
 * Tìm attachment theo filename
 */
function ks_find_attachment_by_filename($filename)
{
    global $wpdb;

    $like = '%' . $wpdb->esc_like($filename) . '%';

    $id = $wpdb->get_var($wpdb->prepare("
    SELECT post_id
    FROM {$wpdb->postmeta}
    WHERE meta_key = '_wp_attached_file'
    AND meta_value LIKE %s
    LIMIT 1
    ", $like));

    return $id ? intval($id) : false;
}

// add_action(
//     'woocommerce_checkout_create_order_line_item',
//     'ks_add_custom_field_to_order_items',
//     10,
//     4
// );

// function ks_add_custom_field_to_order_items($item, $cart_item_key, $values, $order)
// {

//     if (!empty($values['rental_days'])) {

//         $item->add_meta_data(
//             '_rental_days', // meta key (nên có _)
//             intval($values['rental_days']),
//             true
//         );
//     }
// }

// add_action(
//     'woocommerce_after_order_itemmeta',
//     'ks_show_custom_field_in_admin_order',
//     10,
//     3
// );

// function ks_show_custom_field_in_admin_order($item_id, $item, $product)
// {

//     $rental_days = wc_get_order_item_meta(
//         $item_id,
//         '_rental_days',
//         true
//     );

//     if ($rental_days) {

//         echo '<p><strong>Số ngày thuê:</strong> '
//             . esc_html($rental_days)
//             . ' ngày</p>';
//     }
// }

// add_action(
//     'woocommerce_after_order_itemmeta',
//     'ks_add_edit_field_admin_order',
//     20,
//     3
// );

// function ks_add_edit_field_admin_order($item_id, $item, $product)
// {

//     if (!is_admin()) return;

//     $value = wc_get_order_item_meta($item_id, '_rental_days', true);

//     woocommerce_wp_text_input([
//         'id'    => 'rental_days_' . $item_id,
//         'name'  => 'rental_days_' . $item_id,  // Thêm name để POST được
//         'label' => 'Số ngày thuê',
//         'value' => $value,
//         'type'  => 'number',
//         'custom_attributes' => [
//             'min' => 1
//         ]
//     ]);
// }

// add_action(
//     'woocommerce_saved_order_items',
//     'ks_recalc_rental_price_after_admin_edit',
//     20
// );

// function ks_recalc_rental_price_after_admin_edit($order_id)
// {

//     error_log('=== KS RECALC RENTAL === Order ID: ' . $order_id);

//     // Kiểm tra quyền - dùng manage_woocommerce thay vì edit_shop_order
//     if (!current_user_can('manage_woocommerce') && !current_user_can('edit_shop_orders')) {
//         error_log('KS: No permission - user: ' . get_current_user_id());
//         return;
//     }

//     $order = wc_get_order($order_id);

//     if (!$order) {
//         error_log('KS: No order found');
//         return;
//     }

//     // Debug: log all POST data
//     error_log('KS POST: ' . print_r($_POST, true));

//     // Parse items data từ URL-encoded string
//     $items_data = [];
//     if (!empty($_POST['items'])) {
//         parse_str($_POST['items'], $items_data);
//         error_log('KS items parsed: ' . print_r($items_data, true));
//     }

//     $processed = 0;

//     foreach ($order->get_items() as $item_id => $item) {

//         $meta_key = 'rental_days_' . $item_id;

//         // Lấy số ngày mới - ưu tiên từ items đã parse, fallback về POST trực tiếp
//         $new_days = 0;
//         if (!empty($items_data[$meta_key])) {
//             $new_days = intval($items_data[$meta_key]);
//         } elseif (!empty($_POST[$meta_key])) {
//             $new_days = intval($_POST[$meta_key]);
//         }

//         if ($new_days < 1) {
//             error_log("KS: Missing or invalid days for $meta_key, new_days=$new_days");
//             continue;
//         }


//         /* =====================
//          * 1. Get old value
//          ===================== */

//         $old_days = wc_get_order_item_meta(
//             $item_id,
//             '_rental_days',
//             true
//         );

//         error_log("KS: old_days=$old_days, new_days=$new_days");


//         /* =====================
//          * 2. Update meta
//          ===================== */

//         wc_update_order_item_meta(
//             $item_id,
//             '_rental_days',
//             $new_days
//         );


//         /* =====================
//          * 3. Recalc price
//          ===================== */

//         // Lấy giá từ order item (giá tại thời điểm đặt hàng)
//         // Để tránh bị ảnh hưởng khi giá sản phẩm thay đổi
//         $base_price = 0;

//         if ($old_days > 0) {
//             // Lấy giá từ order item / số ngày cũ
//             $base_price = $item->get_subtotal() / $old_days;
//         } else {
//             // Nếu chưa có số ngày cũ (đơn mới), lấy từ sản phẩm
//             $product = $item->get_product();
//             if ($product) {
//                 $base_price = $product->get_regular_price();
//             }
//         }

//         error_log('KS: base_price=' . $base_price . ', old_days=' . $old_days);

//         if (!$base_price || $base_price <= 0) {
//             error_log('KS: No base price');
//             continue;
//         }

//         $new_price = $base_price * $new_days;

//         error_log("KS: new_price = $base_price * $new_days = $new_price");

//         // Cập nhật giá order item KHÔNG ảnh hưởng đến sản phẩm gốc
//         wc_update_order_item_meta($item_id, '_line_subtotal', $new_price);
//         wc_update_order_item_meta($item_id, '_line_total', $new_price);
//         wc_update_order_item_meta($item_id, '_subtotal', $new_price);
//         wc_update_order_item_meta($item_id, '_total', $new_price);

//         $processed++;
//     }


//     /* =====================
//      * 5. Recalc toàn đơn
//      ===================== */

//     error_log("KS: Processed $processed items");

//     // Tính lại subtotal từ các item đã cập nhật
//     $new_subtotal = 0;
//     $max_rental_days = 0;
//     foreach ($order->get_items() as $item_id => $item) {
//         $item_total = wc_get_order_item_meta($item_id, '_line_total', true);
//         $new_subtotal += floatval($item_total);

//         $item_days = wc_get_order_item_meta($item_id, '_rental_days', true);
//         $item_days = intval($item_days);
//         if ($item_days > $max_rental_days) {
//             $max_rental_days = $item_days;
//         }
//     }

//     error_log("KS: new_subtotal=$new_subtotal, max_rental_days=$max_rental_days");

//     // Cập nhật order meta tổng số ngày thuê
//     update_post_meta($order_id, '_rental_days_total', $max_rental_days);

//     // Cập nhật order totals
//     $order->set_subtotal($new_subtotal);
//     $order->set_total($new_subtotal + floatval($order->get_total_tax()));
//     $order->save();
// }

// /**
//  * Hiển thị tổng số ngày thuê trong order meta box
//  */
// add_action('woocommerce_admin_order_data_after_billing_address', 'ks_display_total_rental_days_in_admin', 10, 1);
// function ks_display_total_rental_days_in_admin($order)
// {
//     $total_days = get_post_meta($order->get_id(), '_rental_days_total', true);
//     if ($total_days) {
//         echo '<p class="form-field form-field-wide"><strong>Tổng ngày thuê:</strong> ' . intval($total_days) . ' ngày</p>';
//     }
// }

add_action('created_term', 'yun_force_pm_option_uri', 99, 3);
add_action('edited_term',  'yun_force_pm_option_uri', 99, 3);

function yun_force_pm_option_uri($term_id, $tt_id, $taxonomy)
{

    if ($taxonomy !== 'product_cat') return;

    $option = get_option('permalink-manager-uris');

    if (!is_array($option)) {
        $option = maybe_unserialize($option);
    }

    if (!is_array($option)) {
        $option = [];
    }

    $term = get_term($term_id);
    if (!$term || is_wp_error($term)) return;

    $uri = 'collections/' . $term->slug;

    // Key format: tax-{id}
    $key = 'tax-' . $term_id;

    // Update
    $option[$key] = $uri;

    update_option('permalink-manager-uris', $option);

    // Flush rewrite
    flush_rewrite_rules(false);

    error_log("PM OPTION SET: {$key} => {$uri}");
}

// ================================
// REST API: Strip inline styles from product descriptions
// POST /wp-json/api/v1/products/strip-styles
// ================================

// add_action('rest_api_init', function () {
//     register_rest_route('api/v1', '/post/strip-styles', [
//         'methods'             => 'GET',
//         'callback'            => 'cosplay_strip_post_content_styles',
//         'permission_callback' => '__return_true',
//     ]);
// });

function cosplay_strip_post_content_styles($request)
{
    $page     = max(1, (int) $request->get_param('page'));
    $per_page = 200;

    $posts = get_posts([
        'post_type'              => 'post',
        'post_status'            => 'publish',
        'posts_per_page'         => $per_page,
        'paged'                  => $page,
        'update_post_meta_cache' => false,
        'update_post_term_cache' => false,
    ]);

    if (empty($posts)) {
        return new WP_REST_Response([
            'success' => true,
            'message' => 'Không còn bài viết nào để xử lý.',
            'updated' => 0,
            'page'    => $page,
        ], 200);
    }

    $updated = 0;
    $errors  = [];

    foreach ($posts as $post) {
        $content = $post->post_content;

        if (empty($content)) {
            continue;
        }

        // remove toàn bộ style=""
        $clean = preg_replace('/\sstyle\s*=\s*("|\')(.*?)\1/i', '', $content);

        if ($clean === $content) {
            continue;
        }

        $result = wp_update_post([
            'ID'           => $post->ID,
            'post_content' => $clean,
        ], true);

        if (is_wp_error($result)) {
            $errors[$post->ID] = $result->get_error_message();
            continue;
        }

        $updated++;
    }

    return new WP_REST_Response([
        'success' => true,
        'message' => "Đã cập nhật {$updated} bài viết.",
        'updated' => $updated,
        'page'    => $page,
        'next'    => rest_url('cosplay/v1/strip-post-styles?page=' . ($page + 1)),
        'errors'  => $errors,
    ], 200);
}

/**
 * ĐÃ XOÁ: enqueue_featured_news_assets().
 *
 * Hàm này hook wp_enqueue_scripts KHÔNG điều kiện nên nạp trùng asset trên MỌI trang:
 *   - swiper CDN (swiper@11) → Swiper load 2 lần, đã có bản local ở wp_enqueue_lib()
 *   - app.js (handle 'app-script') → app.js load 2 lần → `new App()` chạy 2 lần
 *     → 2 instance Lenis + 2 vòng RAF
 *   - featured-scripts → nạp mọi trang dù chỉ trang blogs cần
 *
 * blog-list/featured-section/assets/scripts.js nay nằm trong page-group 'blog-list' của
 * asset-manifest.php, đứng trước blog-list/assets/scripts.js (file này gọi initAllSwipers).
 *
 * @see import-assets/asset-manifest.php → okhub_asset_pages_js()
 */


function ajax_filter_posts()
{
    // Lấy dữ liệu từ AJAX
    $cat_slug = isset($_POST['category']) ? sanitize_text_field($_POST['category']) : '';
    $paged    = isset($_POST['paged']) ? intval($_POST['paged']) : 1;

    $per_page = wp_is_mobile() ? 9 : 16;

    $args = array(
        'post_type'      => 'post',
        'posts_per_page' => $per_page,
        'paged'          => $paged,
        'orderby'        => 'date',
        'order'          => 'DESC',
        'post_status'    => 'publish',
    );

    if (!empty($cat_slug)) {
        $args['category_name'] = $cat_slug;
    }

    $query = new WP_Query($args);

    ob_start();
?>
    <div class="list">
        <?php if ($query->have_posts()) : ?>
            <?php while ($query->have_posts()) : $query->the_post();
                $current_cats = get_the_category();
                $first_cat    = !empty($current_cats) ? $current_cats[0]->name : 'Tin tức';
                $thumbnail_id = get_post_thumbnail_id();
            ?>
                <!-- Đồng bộ Class: post-card img-zoom-on-hover -->
                <div class="post-card img-zoom-on-hover">
                    <div class="post-card__media">
                        <a href="<?php the_permalink(); ?>">
                            <?php if ($thumbnail_id) : ?>
                                <?php echo wp_get_attachment_image($thumbnail_id, 'full', false, array(
                                    'loading'  => 'lazy',
                                    'decoding' => 'async',
                                    'class'    => 'post-card__image zoom-image' // Thêm zoom-image ở đây
                                )); ?>
                            <?php else : ?>
                                <?php echo wp_get_attachment_image(168, 'full', false, array(
                                    'loading'  => 'lazy',
                                    'decoding' => 'async',
                                    'class'    => 'post-card__image zoom-image'
                                )); ?>
                            <?php endif; ?>
                            
                            <img class="post-card__layer" src="<?= get_template_directory_uri(); ?>/assets/images/layer_image.svg" alt="Layer Overlay" />
                        </a>
                    </div>
                    <div class="post-card__content">
                        <div class="post-card__meta">
                            <span class="post-card__tag post-card__tag--red"><?= esc_html($first_cat); ?></span>
                            <time datetime="<?php echo get_the_date('c'); ?>" class="post-card__date">
                                <?php echo get_the_date('d/m/Y'); ?>
                            </time>
                        </div>

                        <h3 class="post-card__title">
                            <a href="<?php the_permalink(); ?>"><?php the_title(); ?></a>
                        </h3>
                    </div>
                </div>
            <?php endwhile; wp_reset_postdata(); ?>
        <?php else : ?>
            <p>Không tìm thấy bài viết nào phù hợp.</p>
        <?php endif; ?>
    </div>

    <?php if ($query->max_num_pages > 1) : ?>
        <nav class="pagination" aria-label="Page navigation">
            <ul class="pagination__list">
                <?php
                $pages = paginate_links(array(
                    'total'        => $query->max_num_pages,
                    'current'      => $paged,
                    'format'       => '?paged=%#%',
                    'add_args'     => array('categories' => $cat_slug),
                    'prev_text'    => '<svg width="7" height="11" viewBox="0 0 7 11" fill="none"><path d="M5.70605 0.353516L0.706873 5.3527L5.70605 10.3519" stroke="#1D1D1D" /></svg>',
                    'next_text'    => '<svg width="7" height="11" viewBox="0 0 7 11" fill="none"><path d="M0.353516 0.353516L5.3527 5.3527L0.353516 10.3519" stroke="#1D1D1D" /></svg>',
                    'type'         => 'array',
                    'prev_next'    => true,
                ));

                if (is_array($pages)) {
                    foreach ($pages as $page) {
                        $is_prev_next = (strpos($page, 'prev') !== false || strpos($page, 'next') !== false);
                        $is_active    = strpos($page, 'current') !== false;

                        $page = str_replace('page-numbers', 'pagination__link', $page);

                        if ($is_prev_next) {
                            $page = str_replace('pagination__link', 'pagination__link pagination__link--btn', $page);
                            $page = str_replace(['prev', 'next'], '', $page);
                            echo '<li class="pagination__item pagination__item--control">' . $page . '</li>';
                        } elseif (strpos($page, 'dots') !== false) {
                            echo '<li class="pagination__item pagination__item--dots"><span class="pagination__dot"></span><span class="pagination__dot"></span><span class="pagination__dot"></span></li>';
                        } else {
                            if ($is_active) {
                                $page = str_replace('current', 'pagination__link--active', $page);
                            }
                            echo '<li class="pagination__item">' . $page . '</li>';
                        }
                    }
                }
                ?>
            </ul>
        </nav>
    <?php endif; ?>
<?php
    $output = ob_get_clean();
    echo $output;
    wp_die();
}
add_action('wp_ajax_filter_posts', 'ajax_filter_posts');
add_action('wp_ajax_nopriv_filter_posts', 'ajax_filter_posts');