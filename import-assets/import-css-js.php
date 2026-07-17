<?php
/**
 * Enqueue asset.
 *
 * CSS/JS của theme được GỘP thành 2 bundle mỗi trang:
 *   core.css / core.js   — site-wide, giống nhau mọi trang → browser cache dùng lại khi chuyển trang
 *   {page}.css / {page}.js — riêng từng page-group
 *
 * Thư viện (Swiper, GSAP, Lenis, Fancybox, noUiSlider) GIỮ NGUYÊN file rời — chúng gần như
 * không đổi, để rời thì browser cache dùng chung được giữa các trang; nhồi vào bundle sẽ bắt
 * tải lại 168KB Swiper mỗi lần đổi trang.
 *
 * Khai báo file nào thuộc trang nào: asset-manifest.php
 * Cơ chế gộp:                        bundler.php
 *
 * ⚠️ Thứ tự in ra của theme này là `lib → core → page` (wp_enqueue_lib ở priority 1000,
 *    enqueue theme ở 1001). NGƯỢC với source vietdung. Giữ nguyên — xem PLAN §1.4.
 *
 * Bundle không sinh được (uploads read-only, còn sót @import/ESM) → tự fallback enqueue rời.
 * Tắt bundle để debug: define('OKHUB_BUNDLE', false) trong wp-config.php
 *
 * @see asset-manifest.php
 * @see bundler.php
 * @see docs/PLAN-optimize-import-css-js.md
 */

require_once __DIR__ . '/bundler.php';
require_once __DIR__ . '/asset-manifest.php';

$VERSION = false ? time() : wp_get_theme()->get('Version');
// define('THEME_VERSION', null);
define('THEME_VERSION', $VERSION);

/**
 * Version của 1 file local = mtime.
 *
 * THEME_VERSION là version tĩnh của theme (vd 1.0.0) nên sửa file CSS KHÔNG đổi version
 * → browser ăn cache cũ → phải hard-refresh. Dùng mtime thì đúng cả dev (sửa file → đổi ngay)
 * lẫn prod (không sửa → đứng yên → browser cache được).
 */
function okhub_file_version($relative_path)
{
	$path = get_theme_file_path($relative_path);

	return file_exists($path) ? (string) filemtime($path) : THEME_VERSION;
}

// ============================== start wp_enqueue lib =====================//

/**
 * ĐÃ XOÁ: my_add_preconnects() — preconnect tới fonts.googleapis.com + fonts.gstatic.com.
 *
 * Font Phudu nay self-host (assets/fonts/phudu.css + assets/fonts/phudu/*.woff2) nên KHÔNG còn
 * request nào tới 2 host đó. Giữ preconnect lại chỉ tổ bắt browser mở 2 connection tới host
 * không bao giờ dùng → phí, và tranh băng thông với request thật.
 *
 * @see assets/fonts/phudu.css
 */


/**
 * Lib.
 *
 * SITE-WIDE (mọi trang):
 *   - font Phudu
 *   - swiper — header mega-menu dùng `new Swiper()` ở CẢ desktop lẫn mobile, mà header render
 *     mọi trang (markup ACF-driven, giống nhau kể cả trang 404). KHÔNG cắt per-page được.
 *     Xem PLAN §1.5 — chỗ này plan bản đầu ghi SAI.
 *   - lenis — app.js khởi tạo, site-wide
 *
 * PER-PAGE (khai báo ở asset-manifest.php → okhub_asset_page_libs()):
 *   - gsap + ScrollTrigger, CustomEase
 *
 * Chạy ở priority 1000, TRƯỚC enqueue theme (1001) ⇒ giữ đúng thứ tự `lib → core → page`.
 */
function  wp_enqueue_lib()
{
	// ---- Site-wide. Tất cả LOCAL — font Phudu nay nằm trong core.css bundle
	// (assets/fonts/phudu.css, khai báo ở okhub_asset_core_css) nên KHÔNG enqueue riêng nữa:
	// bớt 1 request + bớt 1 stylesheet render-blocking từ host ngoài.
	wp_enqueue_style('swiper', get_theme_file_uri('/assets/css/swiper-bundle.min.css'), [], okhub_file_version('/assets/css/swiper-bundle.min.css'));
	wp_enqueue_script('swiper', get_theme_file_uri('/assets/js/swiper-bundle.min.js'), [], okhub_file_version('/assets/js/swiper-bundle.min.js'), true);
	wp_enqueue_style('lenis', get_theme_file_uri('/assets/css/lenis.css'), [], okhub_file_version('/assets/css/lenis.css'));
	wp_enqueue_script('lenis', get_theme_file_uri('/assets/js/lenis.min.js'), [], okhub_file_version('/assets/js/lenis.min.js'), true);

	// ---- Per-page.
	$key = okhub_current_page_key();

	if (!$key) {
		return;
	}

	$page_libs = okhub_asset_page_libs();

	if (!isset($page_libs[$key])) {
		return;
	}

	$libs = okhub_asset_libs();

	foreach ($page_libs[$key] as $name) {
		if (!isset($libs[$name])) {
			continue;
		}

		foreach ($libs[$name] as $script) {
			// Lib nay là file local → version = filemtime, giống mọi asset local khác.
			wp_enqueue_script(
				$script['handle'],
				get_theme_file_uri($script['src']),
				$script['deps'],
				okhub_file_version($script['src']),
				true
			);
		}
	}
}
add_action('wp_enqueue_scripts', 'wp_enqueue_lib', 1000);

// ============================== end wp_enqueue lib =====================//

// ============================== wp_enqueue CSS (manifest) =====================//

/**
 * Enqueue 1 list CSS theo đúng thứ tự khai báo.
 *
 * Chain dep theo thứ tự mảng: file sau phụ thuộc file trước → WordPress buộc phải in ra
 * đúng thứ tự đó, không phụ thuộc vào thứ tự nội bộ của queue.
 *
 * @return string|null Handle cuối cùng (để list sau chain tiếp).
 */
function okhub_enqueue_css_list($key, array $entries, array $deps)
{
	$previous = null;
	$index    = 0;

	foreach ($entries as $src) {
		if (!file_exists(get_theme_file_path($src))) {
			continue;
		}

		$handle = 'okhub-' . $key . '-css-' . (++$index);
		$chain  = $previous ? array_merge($deps, [$previous]) : $deps;

		wp_enqueue_style($handle, get_theme_file_uri($src), $chain, okhub_file_version($src));

		$previous = $handle;
	}

	return $previous;
}

/**
 * Enqueue lib CSS gắn với page-group (nouislider / fancybox).
 *
 * @return array Deps mới (để chain tiếp).
 */
function okhub_enqueue_page_lib_css(array $libs, array $deps)
{
	foreach ($libs as $lib) {
		wp_enqueue_style($lib['handle'], get_theme_file_uri($lib['src']), $deps, okhub_file_version($lib['src']));
		$deps = [$lib['handle']];
	}

	return $deps;
}

function okhub_enqueue_theme_css()
{
	// ---- 1. Core — site-wide. Bundle 1 file; không bundle được thì enqueue rời (dep chain).
	$core_css = okhub_asset_core_css();
	$core_url = okhub_bundle('core', $core_css, 'css');

	if ($core_url) {
		wp_enqueue_style('okhub-core', $core_url, [], null);
		$deps = ['okhub-core'];
	} else {
		$last = okhub_enqueue_css_list('core', $core_css, []);
		$deps = $last ? [$last] : [];
	}

	$key = okhub_current_page_key();

	if (!$key) {
		return;
	}

	$pages = okhub_asset_pages_css();

	if (!isset($pages[$key]) || !$pages[$key]) {
		return;
	}

	$page_libs = okhub_asset_page_lib_css();
	$lib       = isset($page_libs[$key]) ? $page_libs[$key] : [];

	// ---- 2. Lib CSS in TRƯỚC page (nouislider).
	if (!empty($lib['before'])) {
		$deps = okhub_enqueue_page_lib_css($lib['before'], $deps);
	}

	// ---- 3. Page CSS.
	$page_url = okhub_bundle($key, $pages[$key], 'css');

	if ($page_url) {
		wp_enqueue_style('okhub-page', $page_url, $deps, null);
		$deps = ['okhub-page'];
	} else {
		$last = okhub_enqueue_css_list($key, $pages[$key], $deps);
		$deps = $last ? [$last] : $deps;
	}

	// ---- 4. Lib CSS in SAU page (fancybox — phải thắng khi đụng rule với single-product).
	if (!empty($lib['after'])) {
		okhub_enqueue_page_lib_css($lib['after'], $deps);
	}
}
add_action('wp_enqueue_scripts', 'okhub_enqueue_theme_css', 1001);

// ============================== wp_enqueue JS (manifest) =====================//

/**
 * Enqueue 1 list JS theo đúng thứ tự khai báo.
 *
 * Chain dep theo thứ tự mảng: file sau phụ thuộc file trước → WordPress buộc phải in ra
 * đúng thứ tự đó. BẮT BUỘC với JS: thứ tự in = thứ tự đăng ký listener DOMContentLoaded
 * = thứ tự chạy. In sai thứ tự là đổi hành vi (vd initAllSwipers chưa định nghĩa).
 *
 * @return array{first:?string,last:?string}
 */
function okhub_enqueue_js_list($key, array $entries, array $deps)
{
	$previous = null;
	$first    = null;
	$index    = 0;

	foreach ($entries as $entry) {
		$normalized = okhub_normalize_js_entry($entry);
		$src        = $normalized['src'];

		// File rỗng 0 byte (service-makeup/assets, service-pgpb/assets) → bỏ, khỏi tốn request.
		$path = get_theme_file_path($src);
		if (!file_exists($path) || 0 === filesize($path)) {
			continue;
		}

		$handle = 'okhub-' . $key . '-js-' . (++$index);
		$chain  = $previous ? array_merge($deps, [$previous]) : $deps;

		wp_enqueue_script($handle, get_theme_file_uri($src), $chain, okhub_file_version($src), true);

		if (null === $first) {
			$first = $handle;
		}
		$previous = $handle;
	}

	return ['first' => $first, 'last' => $previous];
}

/**
 * Enqueue lib JS gắn với page-group (nouislider / fancybox) — in TRƯỚC page JS.
 *
 * @return array Handle lib (để page JS chain dep vào).
 */
function okhub_enqueue_page_lib_js(array $libs, array $deps)
{
	$handles = [];

	foreach ($libs as $lib) {
		wp_enqueue_script($lib['handle'], get_theme_file_uri($lib['src']), $deps, okhub_file_version($lib['src']), true);
		$handles[] = $lib['handle'];
	}

	return $handles;
}

function okhub_enqueue_theme_js()
{
	// ---- 1. Core — site-wide. Bundle 1 file; không bundle được thì enqueue rời (dep chain).
	$core_js  = okhub_asset_core_js();
	$core_url = okhub_bundle('core', $core_js, 'js');

	if ($core_url) {
		wp_enqueue_script('okhub-core', $core_url, [], null, true);
		$localize_target = 'okhub-core';
		$deps            = ['okhub-core'];
	} else {
		$core            = okhub_enqueue_js_list('core', $core_js, []);
		$localize_target = $core['first'];
		$deps            = $core['last'] ? [$core['last']] : [];
	}

	// wpApiSettings: bản cũ gắn vào handle 'header' (nay không còn tồn tại). Người dùng thật
	// là product-listing/section-content ('window.wpApiSettings.root'). Gắn vào handle ĐẦU
	// (bundle core, hoặc file đầu ở đường fallback) → data in ra sớm nhất, mọi script sau đều thấy.
	if ($localize_target) {
		wp_localize_script($localize_target, 'wpApiSettings', [
			'nonce' => wp_create_nonce('wp_rest'),
			'root'  => esc_url_raw(rest_url()),
		]);
	}

	$key = okhub_current_page_key();

	if (!$key) {
		return;
	}

	$pages = okhub_asset_pages_js();

	if (!isset($pages[$key]) || !$pages[$key]) {
		return;
	}

	// ---- 2. Lib JS của page (nouislider trước product-listing, fancybox trước single-product).
	$page_libs = okhub_asset_page_lib_js();
	$lib_deps  = isset($page_libs[$key]) ? okhub_enqueue_page_lib_js($page_libs[$key], $deps) : [];

	// ---- 3. Dep WordPress bổ sung (single-blog → jquery).
	$extra     = okhub_asset_page_js_deps();
	$page_deps = array_values(array_unique(array_merge($deps, $lib_deps, isset($extra[$key]) ? $extra[$key] : [])));

	// ---- 4. Page JS.
	$page_url = okhub_bundle($key, $pages[$key], 'js');

	if ($page_url) {
		wp_enqueue_script('okhub-page', $page_url, $page_deps, null, true);
	} else {
		okhub_enqueue_js_list($key, $pages[$key], $page_deps);
	}
}
add_action('wp_enqueue_scripts', 'okhub_enqueue_theme_js', 1001);

// ============================== script tag =====================//

/**
 * KHÔNG còn handle nào cần type="module".
 *
 * Phase 1b đã bỏ toàn bộ `import {}` / `export` khỏi source: mọi file nay là classic script,
 * tự init bằng DOMContentLoaded, và chia sẻ binding qua top-level global (khai báo
 * `scope => 'global'` trong manifest). Filter `add_type_attribute` cũ đã xoá — nó vừa thừa,
 * vừa DỰNG LẠI thẻ <script> từ đầu nên nuốt mất `id` và mọi data mà wp_localize_script gắn vào.
 */
