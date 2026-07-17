<?php
/**
 * Manifest asset — khai báo file nào thuộc trang nào, theo đúng thứ tự.
 *
 * ĐÂY LÀ NGUỒN SỰ THẬT DUY NHẤT về thứ tự CSS/JS. Source KHÔNG còn `@import` trong CSS
 * và `import {}` trong JS nữa — mọi thứ tự cascade/thực thi khai báo ở file này.
 *
 * `core`  : site-wide, giống nhau mọi trang.
 * `pages` : riêng từng page-group, chọn bằng okhub_current_page_key().
 *
 * Path là theme-relative. Thứ tự trong mảng = thứ tự in ra trang.
 *
 * ⚠️ THỨ TỰ CASCADE của theme này là: lib → core → page
 *    (wp_enqueue_lib chạy ở priority 1000, enqueue theme ở 1001)
 *    Tức _reset.css / global.css ĐANG đè lên Swiper. Đây là hành vi có sẵn, phải giữ.
 *    KHÔNG bê thứ tự core → lib → page của source vietdung sang.
 *
 * @see PLAN-optimize-import-css-js.md
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * CSS site-wide.
 *
 * Thứ tự lấy đúng từ bản enqueue cũ (mảng $wp_enqueue_mapping, các entry condition => true):
 *   stylesheet → _reset → _variables → global → header → footer → cta → components
 *
 * Các nhóm header/components trước đây là 1 file aggregator dùng @import; nay trải phẳng
 * theo đúng thứ tự DFS mà @import sinh ra (file được import in trước rule riêng của file cha).
 */
function okhub_asset_core_css() {
	return [
		// Phudu — self-host, thay cho <link> tới fonts.googleapis.com. Nằm trong core.css bundle
		// ⇒ 0 request riêng. Xếp cùng chỗ với @font-face khác. Xem assets/fonts/phudu.css.
		'/assets/fonts/phudu.css',
		'/assets/fonts/stylesheet.css',
		'/assets/css/_reset.css',
		'/assets/css/_variables.css',
		'/assets/css/global.css',

		// ---- Header. Cũ: layouts/header/assets/styles.css @import desktop + mobile.
		// desktop/styles.css tự @import 3 mega-menu; mobile/styles.css @import 5 file.
		// header-mobile/assets/styles.css đã XOÁ (0 rule riêng, chỉ còn là aggregator).
		'/template-parts/layouts/header/assets/header-desktop/mega-menu-product.css',
		'/template-parts/layouts/header/assets/header-desktop/mega-menu-service.css',
		'/template-parts/layouts/header/assets/header-desktop/mega-menu-search-result.css',
		'/template-parts/layouts/header/assets/header-desktop/styles.css',
		'/template-parts/layouts/header/assets/header-mobile/header-main.css',
		'/template-parts/layouts/header/assets/header-mobile/header-menu.css',
		'/template-parts/layouts/header/assets/header-mobile/header-search.css',
		'/template-parts/layouts/header/assets/header-mobile/header-product.css',
		'/template-parts/layouts/header/assets/header-mobile/header-service.css',
		'/template-parts/layouts/header/assets/styles.css',

		'/template-parts/layouts/footer/assets/styles.css',
		'/template-parts/layouts/cta/assets/styles.css',

		// ---- Components. Cũ: components/assets/styles.css (đã XOÁ, 0 rule riêng).
		'/template-parts/components/animated-button/styles.css',
		'/template-parts/components/product/styles.css',
		'/template-parts/components/blog-item/styles.css',
		'/template-parts/components/blog-item-v2/styles.css',
		'/template-parts/components/marquee/styles.css',
	];
}

/**
 * CSS riêng từng page-group.
 *
 * Mỗi list trải phẳng đúng thứ tự @import cũ; file aggregator (đã strip @import, còn rule
 * riêng) xếp CUỐI vì @import luôn nằm trên đầu file ⇒ CSS import in trước rule riêng.
 */
function okhub_asset_pages_css() {
	return [
		'home-page' => [
			'/template-parts/home-page/section-banner/assets/styles.css',
			'/template-parts/home-page/section-about/assets/styles.css',
			'/template-parts/home-page/section-products/assets/styles.css',
			'/template-parts/home-page/section-category/assets/styles.css',
			'/template-parts/home-page/section-services/assets/styles.css',
			'/template-parts/components/section-highlights/assets/styles.css',
			'/template-parts/home-page/section-gallery/assets/styles.css',
			'/template-parts/components/section-events/assets/styles.css',
			'/template-parts/home-page/section-blog/assets/styles.css',
			'/template-parts/home-page/assets/styles.css',
		],

		'about-us' => [
			'/template-parts/about-us/section-banner/assets/styles.css',
			'/template-parts/about-us/section-partners/assets/styles.css',
			'/template-parts/about-us/section-about/assets/styles.css',
			'/template-parts/about-us/section-services/assets/styles.css',
			'/template-parts/components/section-highlights/assets/styles.css',
			'/template-parts/components/section-events/assets/styles.css',
			'/template-parts/about-us/section-process/assets/styles.css',
			'/template-parts/about-us/assets/styles.css',
		],

		// faqs/assets/styles.css đã XOÁ (0 rule riêng).
		// Bản cũ @import section-mermaid-banner 2 LẦN (dòng 7-8) → ở đây khai báo 1 lần.
		'faqs' => [
			'/template-parts/faqs/section-banner/assets/styles.css',
			'/template-parts/faqs/section-question/assets/styles.css',
			'/template-parts/faqs/section-quick-search/assets/styles.css',
			'/template-parts/faqs/section-detail-shop/assets/styles.css',
			'/template-parts/faqs/section-mermaid-banner/assets/styles.css',
		],

		'blog-list' => [
			'/template-parts/blog-list/hero-section/assets/styles.css',
			'/template-parts/blog-list/featured-section/assets/styles.css',
			'/template-parts/blog-list/list-section/assets/styles.css',
			'/template-parts/blog-list/assets/styles.css',
		],

		'single-blog' => [
			'/template-parts/single-blog/related-posts/assets/styles.css',
			'/template-parts/single-blog/table-content-mobile/assets/styles.css',
			'/template-parts/single-blog/assets/styles.css',
		],

		'single-product' => [
			'/template-parts/single-product/product-detail/assets/styles.css',
			'/template-parts/single-product/product-faq/assets/styles.css',
			'/template-parts/single-product/product-feedback/assets/styles.css',
			'/template-parts/single-product/related-product/assets/styles.css',
			'/template-parts/single-product/assets/styles.css',
		],

		'product-listing' => [
			'/template-parts/product-listing/section-breadcrumb/assets/styles.css',
			'/template-parts/product-listing/section-sidebar/assets/styles.css',
			'/template-parts/product-listing/section-content/assets/styles.css',
			'/template-parts/product-listing/assets/styles.css',
		],

		'contact-page' => [
			'/template-parts/contact-page/section-breadcrumb/assets/styles.css',
			'/template-parts/contact-page/section-contact-info/assets/styles.css',
			'/template-parts/contact-page/section-contact-form/assets/styles.css',
			'/template-parts/contact-page/assets/styles.css',
		],

		'search-page' => [
			'/template-parts/search-page/assets/styles.css',
		],

		// FIX: bản cũ COMMENT dòng @import intro-service (aggregator dòng 2:
		//   /* @import "../intro-service/assets/styles.css"; */
		// ) trong khi service-makeup/index.php VẪN render section intro-service → section này
		// chạy không có CSS. Thêm lại đúng vị trí render (sau hero, trước service-makeups).
		// So sánh: service-pgpb import intro-service bình thường.
		'service-makeup' => [
			'/template-parts/service-makeup/hero-section/assets/styles.css',
			'/template-parts/service-makeup/intro-service/assets/styles.css',
			'/template-parts/service-makeup/service-makeups/assets/styles.css',
			'/template-parts/service-makeup/feedback/assets/styles.css',
			'/template-parts/service-makeup/workflow-section/assets/styles.css',
			'/template-parts/service-makeup/section-mermaid-banner/assets/styles.css',
			'/template-parts/service-makeup/assets/styles.css',
		],

		'service-pgpb' => [
			'/template-parts/service-pgpb/hero-section/assets/styles.css',
			'/template-parts/service-pgpb/intro-service/assets/styles.css',
			'/template-parts/service-pgpb/service-makeups/assets/styles.css',
			'/template-parts/service-pgpb/feedback/assets/styles.css',
			'/template-parts/service-pgpb/workflow-section/assets/styles.css',
			'/template-parts/service-pgpb/section-mermaid-banner/assets/styles.css',
			'/template-parts/service-pgpb/our-customer-section/assets/styles.css',
			'/template-parts/service-pgpb/assets/styles.css',
		],

		'service-take-photo-page' => [
			'/template-parts/service-take-photo-page/section-banner/assets/styles.css',
			'/template-parts/service-take-photo-page/section-intro-service/assets/styles.css',
			'/template-parts/service-take-photo-page/section-services/assets/styles.css',
			'/template-parts/service-take-photo-page/section-change/assets/styles.css',
			'/template-parts/service-take-photo-page/section-contact-service/assets/styles.css',
			'/template-parts/service-take-photo-page/section-contact-form/assets/styles.css',
			'/template-parts/service-take-photo-page/assets/styles.css',
		],
	];
}

/**
 * CSS của lib gắn với 1 page-group cụ thể.
 *
 * Giữ file rời (KHÔNG nhồi vào bundle sau này) để browser cache dùng chung.
 * `before` = in trước page CSS, `after` = in sau — phải đúng như bản cũ, nếu không cascade đổi:
 *   - nouislider.css: bản cũ đứng TRƯỚC product-listing → giữ trước
 *   - fancybox.css  : bản cũ đứng SAU single-product   → giữ sau (fancybox thắng khi đụng rule)
 */
function okhub_asset_page_lib_css() {
	return [
		'product-listing' => [
			'before' => [ [ 'handle' => 'nouislider', 'src' => '/assets/css/nouislider.min.css' ] ],
		],
		'single-product' => [
			'after' => [ [ 'handle' => 'fancybox', 'src' => '/assets/css/fancybox.css' ] ],
		],
	];
}

// ============================== JS =====================//

/**
 * JS site-wide.
 *
 * ⚠️ THỨ TỰ = THỨ TỰ THỰC THI. Mỗi file nay tự init bằng `DOMContentLoaded`, listener chạy
 * theo đúng thứ tự đăng ký ⇒ thứ tự mảng này quyết định thứ tự chạy.
 *
 * Lấy đúng thứ tự THỰC THI của bản cũ, không phải thứ tự khai báo: bản cũ đánh dấu
 * `header` + `components` là `type="module"` (⇒ defer, chạy SAU mọi classic script), còn
 * app.js / utils.js / custom-option / custom-drawer / footer / cta là classic (chạy tại chỗ).
 * Nên nhóm header + components xếp SAU footer/cta ở đây.
 *
 * `scope => 'global'` = KHÔNG bọc IIFE khi bundle (Phase 3) — file cố tình định nghĩa
 * binding top-level cho file khác dùng. Bọc IIFE sẽ biến chúng thành private → ReferenceError.
 */
function okhub_asset_core_js() {
	return [
		'/assets/js/app.js',

		// utils.js khai báo `remToPixels`, `CF7Request`, `FormValidator` ở top-level cho
		// file khác gọi (header-mobile, section-events, section-products, take-photo/section-banner).
		[ 'src' => '/assets/js/utils.js', 'scope' => 'global' ],

		'/assets/js/custom-option.js',
		'/assets/js/custom-drawer.js',
		'/template-parts/layouts/footer/assets/scripts.js',
		'/template-parts/layouts/cta/assets/scripts.js',

		// ---- Header. Cũ: header/assets/scripts.js (module) import desktop + mobile rồi chọn
		// theo `window.innerWidth < 640`. Aggregator GIỮ LẠI vì còn logic chọn; 3 file dưới
		// phải là global để nó gọi được.
		[ 'src' => '/template-parts/layouts/header/assets/auto-hide-on-scroll.js', 'scope' => 'global' ],
		[ 'src' => '/template-parts/layouts/header/assets/header-desktop/scripts.js', 'scope' => 'global' ],
		[ 'src' => '/template-parts/layouts/header/assets/header-mobile/scripts.js', 'scope' => 'global' ],
		'/template-parts/layouts/header/assets/scripts.js',

		// ---- Components. Cũ: components/assets/scripts.js (đã XOÁ) gọi initProduct() rồi
		// initMarqueeImages() trong DOMContentLoaded → giữ nguyên thứ tự đó.
		// initProduct phải global: product-listing/section-content re-init sau khi filter.
		[ 'src' => '/template-parts/components/product/scripts.js', 'scope' => 'global' ],
		'/template-parts/components/marquee/scripts.js',
	];
}

/**
 * JS riêng từng page-group. Thứ tự = thứ tự aggregator cũ GỌI hàm (không phải thứ tự import).
 */
function okhub_asset_pages_js() {
	return [
		// section-gallery: bản cũ là `import "…"` side-effect và chạy code ở top-level (không
		// bọc DOMContentLoaded) ⇒ luôn chạy TRƯỚC 7 section kia. Giữ nó ở đầu list.
		'home-page' => [
			'/template-parts/home-page/section-gallery/assets/scripts.js',
			'/template-parts/home-page/section-banner/assets/scripts.js',
			'/template-parts/home-page/section-about/assets/scripts.js',
			'/template-parts/home-page/section-category/assets/scripts.js',
			'/template-parts/home-page/section-services/assets/scripts.js',
			'/template-parts/components/section-events/assets/scripts.js',
			'/template-parts/home-page/section-products/assets/scripts.js',
			'/template-parts/home-page/section-blog/assets/scripts.js',
		],

		// Thứ tự GỌI của about-us/assets/scripts.js cũ: banner → partners → about → services → events.
		'about-us' => [
			'/template-parts/about-us/section-banner/assets/scripts.js',
			'/template-parts/about-us/section-partners/assets/scripts.js',
			'/template-parts/about-us/section-about/assets/scripts.js',
			'/template-parts/about-us/section-services/assets/scripts.js',
			'/template-parts/components/section-events/assets/scripts.js',
		],

		'faqs' => [
			'/template-parts/faqs/section-banner/assets/scripts.js',
			'/template-parts/faqs/section-question/assets/scripts.js',
		],

		// featured-section định nghĩa `initAllSwipers` ở top-level, blog-list/assets/scripts.js
		// GỌI nó (dòng 217). Trước đây file này được enqueue_featured_news_assets() nạp site-wide
		// (bug §5.10); nay về đúng page-group và phải đứng TRƯỚC + là global.
		'blog-list' => [
			[ 'src' => '/template-parts/blog-list/featured-section/assets/scripts.js', 'scope' => 'global' ],
			'/template-parts/blog-list/assets/scripts.js',
		],

		'single-blog' => [
			'/template-parts/single-blog/assets/scripts.js',
		],

		'single-product' => [
			'/template-parts/single-product/product-detail/assets/scripts.js',
			'/template-parts/single-product/product-faq/assets/scripts.js',
			'/template-parts/single-product/product-feedback/assets/scripts.js',
			'/template-parts/single-product/related-product/assets/scripts.js',
		],

		// sidebar + content phải global: product-listing/assets/scripts.js gọi
		// sectionSidebarScripts / sectionContentScripts / getActiveFilters / expandHeight / collapseHeight.
		'product-listing' => [
			[ 'src' => '/template-parts/product-listing/section-sidebar/assets/scripts.js', 'scope' => 'global' ],
			[ 'src' => '/template-parts/product-listing/section-content/assets/scripts.js', 'scope' => 'global' ],
			'/template-parts/product-listing/assets/scripts.js',
		],

		'contact-page' => [
			'/template-parts/contact-page/section-contact-info/assets/scripts.js',
			'/template-parts/contact-page/section-contact-form/assets/scripts.js',
		],

		'search-page' => [
			'/template-parts/search-page/assets/scripts.js',
		],

		// service-makeup/assets/scripts.js + service-pgpb/assets/scripts.js là file RỖNG 0 byte
		// → không có JS nào. (Bản cũ vẫn enqueue chúng — 2 request rỗng/trang.)
		'service-makeup' => [],
		'service-pgpb'   => [],

		// section-contact-form là `import "…"` side-effect ⇒ eval TRƯỚC thân aggregator ⇒
		// listener của nó đăng ký trước → giữ ở đầu.
		'service-take-photo-page' => [
			'/template-parts/service-take-photo-page/section-contact-form/assets/scripts.js',
			'/template-parts/service-take-photo-page/section-banner/assets/scripts.js',
			'/template-parts/service-take-photo-page/section-services/assets/scripts.js',
		],
	];
}

/**
 * Lib JS gắn với 1 page-group — enqueue TRƯỚC page JS và thành dep của nó.
 * (Giữ file rời, không nhồi vào bundle.)
 */
function okhub_asset_page_lib_js() {
	return [
		'product-listing' => [ [ 'handle' => 'nouislider', 'src' => '/assets/js/nouislider.min.js' ] ],
		'single-product'  => [ [ 'handle' => 'fancybox', 'src' => '/assets/js/fancybox.umd.js' ] ],
	];
}

/**
 * Dep WordPress bổ sung cho page JS.
 *
 * ⚠️ `single-blog` → `jquery`: bản cũ khai báo `deps => ['jquery']`. Đã grep:
 * single-blog/assets/scripts.js **KHÔNG dùng jQuery** (0 ref `$(` / `jQuery`) ⇒ dep này thừa.
 * VẪN GIỮ ở Phase 1b để "site chạy y hệt" — bỏ nó sẽ làm jQuery biến mất khỏi trang,
 * đổi tập script của page. Cân nhắc gỡ ở Phase 4 sau khi rà plugin.
 */
function okhub_asset_page_js_deps() {
	return [
		'single-blog' => [ 'jquery' ],
	];
}

// ============================== LIB =====================//

/**
 * Định nghĩa lib. GIỮ NGUYÊN file rời, KHÔNG nhồi vào bundle — lib gần như không đổi, để rời
 * thì browser cache được lâu; nhồi vào bundle sẽ bắt tải lại mỗi khi sửa 1 dòng CSS.
 *
 * ⚠️ TẤT CẢ LIB NAY LÀ FILE LOCAL — không còn CDN (jsdelivr / unpkg / fonts.googleapis).
 * Lý do: "CDN cache dùng chung giữa các site" đã hết tác dụng từ khi browser **partition cache
 * theo site** (~2020) — file GSAP user tải ở site khác KHÔNG dùng lại được cho site này.
 * Đổi lại vẫn phải trả 1 DNS + 1 TCP + 1 TLS handshake cho MỖI host ngoài, ngay trên đường
 * critical. Để local ⇒ dùng chung connection với bundle, 0 handshake mới, và không chết theo
 * jsdelivr/unpkg.
 *
 * `src` là path theme-relative ⇒ version = filemtime (xem okhub_enqueue_page_lib_js).
 * Version lib ghi trong tên/ghi chú, không nhét vào ?ver.
 *
 * GSAP 3.14.1 — GreenSock standard license, được phép self-host.
 */
function okhub_asset_libs() {
	return [
		'gsap' => [
			[ 'handle' => 'gsap-core', 'src' => '/assets/js/gsap.min.js', 'deps' => [] ],
			[ 'handle' => 'gsap-scroll-trigger', 'src' => '/assets/js/ScrollTrigger.min.js', 'deps' => [ 'gsap-core' ] ],
		],
		'gsap-custom-ease' => [
			[ 'handle' => 'gsap-custom-ease', 'src' => '/assets/js/CustomEase.min.js', 'deps' => [ 'gsap-core' ] ],
		],
	];
}

/**
 * Lib JS nào thuộc page-group nào. Group KHÔNG liệt kê ở đây = không load lib nào.
 *
 * ⚠️ ĐÃ GREP XÁC MINH TỪNG FILE — chỉ 5 file thật sự gọi GSAP:
 *   components/marquee            → render bởi home-page/section-gallery, service-makeup/feedback,
 *                                    service-pgpb/feedback, service-take-photo-page/section-change
 *   components/section-events     → render bởi home-page, about-us
 *   home-page/section-about       → gsap + **CustomEase** (file DUY NHẤT dùng CustomEase)
 *   home-page/section-gallery     → gsap
 *   home-page/section-products    → gsap
 *
 * ⚠️ `faqs` KHÔNG có trong danh sách dù PLAN §1.6 bản cũ ghi là có: đó là **false positive** —
 * faqs/section-banner chỉ có hàm local tên `handleScrollTrigger`, không đụng GSAP ScrollTrigger.
 *
 * ⚠️ Ràng buộc ngầm: `components/marquee/scripts.js` nằm trong CORE (site-wide) nhưng cần GSAP.
 * Nó tự guard `if (!marquee || !window.gsap) return;` nên trang không có GSAP thì no-op, KHÔNG lỗi.
 * ⇒ Thêm marquee vào 1 trang mới thì PHẢI khai báo lib 'gsap' cho group đó, nếu không marquee
 * render ra nhưng đứng im (không animate, không báo lỗi).
 */
function okhub_asset_page_libs() {
	return [
		'home-page'               => [ 'gsap', 'gsap-custom-ease' ], // section-about dùng CustomEase
		'about-us'                => [ 'gsap' ],                     // components/section-events
		'service-makeup'          => [ 'gsap' ],                     // feedback → marquee
		'service-pgpb'            => [ 'gsap' ],                     // feedback → marquee
		'service-take-photo-page' => [ 'gsap' ],                     // section-change → marquee
	];
}

/**
 * Page-group nào đang render. Thứ tự CÓ Ý NGHĨA: nhánh hẹp trước.
 *
 * @return string|null Key trong okhub_asset_pages_css(), hoặc null.
 */
function okhub_current_page_key() {
	// Phải đứng đầu: front page là WP Page nên các nhánh is_page_template() dưới cũng có thể khớp.
	// Dùng cả is_front_page(): bản cũ chỉ gate bằng is_page_template('front-page.php') — hàm này
	// đọc meta _wp_page_template, nên nếu trang chủ được chọn qua template hierarchy (không gán
	// template trong editor) thì nó trả false và trang chủ mất sạch CSS.
	if ( is_front_page() || is_page_template( 'front-page.php' ) ) {
		return 'home-page';
	}

	if ( is_search() ) {
		return 'search-page';
	}

	// ---- Single service: single-service.php chỉ render ĐÚNG 1 template-part theo has_term().
	// Bản cũ gate bằng `|| is_singular('service')` lặp ở cả 3 nhóm → 1 bài service kéo CẢ 3 bộ CSS.
	// Ở đây soi đúng term như single-service.php để chọn 1. Không term nào khớp → template in
	// "Không có dịch vụ" → không cần CSS nào.
	if ( is_singular( 'service' ) ) {
		$post_id = get_the_ID();

		if ( has_term( 'dich-vu-chup-anh', 'service_category', $post_id ) ) {
			return 'service-take-photo-page';
		}

		if ( has_term( 'dich-vu-pg-pb', 'service_category', $post_id ) ) {
			return 'service-pgpb';
		}

		if ( has_term( 'dich-vu-makeup', 'service_category', $post_id ) ) {
			return 'service-makeup';
		}

		return null;
	}

	if ( is_page_template( 'service-take-photo-page.php' ) ) {
		return 'service-take-photo-page';
	}

	if ( is_page_template( 'service-pgpb-page.php' ) ) {
		return 'service-pgpb';
	}

	if ( is_page_template( 'service-makeup-page.php' ) ) {
		return 'service-makeup';
	}

	// Bản cũ chấp nhận cả 2 biến thể hoa/thường của tên file template.
	if ( is_page_template( 'faqs.php' ) || is_page_template( 'Faqs.php' ) ) {
		return 'faqs';
	}

	if ( is_page_template( 'about-us-page.php' ) ) {
		return 'about-us';
	}

	if ( is_page_template( 'page-contact.php' ) ) {
		return 'contact-page';
	}

	if ( is_page_template( 'blogs.php' ) ) {
		return 'blog-list';
	}

	// WooCommerce có thể bị tắt → không guard là fatal.
	if ( function_exists( 'is_shop' ) && ( is_shop() || is_product_taxonomy() ) ) {
		return 'product-listing';
	}

	if ( is_singular( 'product' ) ) {
		return 'single-product';
	}

	if ( is_singular( 'post' ) ) {
		return 'single-blog';
	}

	return null;
}
