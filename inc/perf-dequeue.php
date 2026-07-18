<?php
/**
 * Gỡ các asset không dùng khỏi trang chủ để giảm tranh chấp băng thông với ảnh hero (LCP)
 * và loại render-blocking. Chỉ áp cho front page — trang shop/product KHÔNG bị đụng.
 *
 * Handle name đã verify thật từ HTML output ngày 18/07/2026 (WooCommerce 10.5.2,
 * Easy Table of Contents 2.0.82.2) — KHÔNG đoán theo tên mặc định của plugin.
 *
 * CỐ Ý GIỮ LẠI: wc-order-attribution + sourcebuster-js (nằm ở footer, deferred,
 * không tranh chấp với LCP) — gỡ chúng sẽ mất dữ liệu order attribution cho các
 * phiên truy cập landing thẳng vào trang chủ. Không đáng đánh đổi lấy ~0 ms LCP.
 */

if (! defined('ABSPATH')) {
    exit;
}

add_action('wp_enqueue_scripts', function () {
    if (! is_front_page()) {
        return;
    }

    // --- WooCommerce: trang chủ không dùng cart / add-to-cart (đều nằm trong <head>, render-blocking) ---
    wp_dequeue_script('wc-add-to-cart');
    wp_dequeue_script('woocommerce');
    wp_dequeue_script('wc-jquery-blockui');
    wp_dequeue_script('wc-js-cookie');
    wp_dequeue_style('wc-blocks-style');

    // --- Easy Table of Contents: trang chủ không có TOC (script ở footer, style render-blocking) ---
    wp_dequeue_script('eztoc-js');
    wp_dequeue_script('eztoc-scroll-scriptjs');
    wp_dequeue_script('eztoc-jquery-sticky-kit');
    wp_dequeue_script('eztoc-js-cookie');
    wp_dequeue_style('ez-toc');   // handle style chính (screen.min.css)
    wp_dequeue_style('eztoc');    // phòng trường hợp version đổi tên handle

    // --- jQuery: chỉ bỏ SAU khi mọi dependent ở trên đã gỡ. Theme + WP Rocket + GSAP/Swiper/Lenis đều vanilla ---
    wp_dequeue_script('jquery');
    wp_dequeue_script('jquery-core');
    wp_dequeue_script('jquery-migrate');
}, 99);
