<?php
/**
 * Bundler asset — gộp CSS/JS của theme thành 1 file mỗi loại mỗi trang.
 *
 * Không có build step: bundle sinh lúc runtime, cache trên đĩa theo fingerprint NỘI DUNG.
 * Sửa file nguồn → nội dung đổi → hash đổi → tên file mới → browser tải bản mới.
 * Không sửa gì → tên file đứng yên → browser dùng cache. Workflow "sửa file → F5" giữ nguyên.
 *
 * Output nằm trong uploads/ (không phải thư mục theme) vì thư mục theme có thể read-only
 * trên production.
 *
 * ĐƠN GIẢN HƠN BẢN `vietdung`: source của theme này đã bỏ hẳn `@import` (Phase 1a) và
 * `import {}` / `export` (Phase 1b) ⇒ bundler KHÔNG cần expand @import đệ quy, KHÔNG cần
 * inline import, KHÔNG cần ESM registry transform. Chỉ còn: nối file + bọc IIFE + rewrite url().
 * Hai guard ở dưới chỉ là lưới an toàn nếu ai đó lỡ thêm import lại.
 *
 * Fail-safe: bất kỳ lỗi nào → trả null → caller fallback enqueue từng file rời.
 * Không bao giờ sinh ra bundle hỏng.
 *
 * Tắt bundle (debug): define('OKHUB_BUNDLE', false) trong wp-config.php
 */

if (! defined('ABSPATH')) {
	exit;
}

/** Thư mục con trong uploads/ chứa bundle. */
const OKHUB_BUNDLE_SUBDIR = 'okhub-assets';

/**
 * Chuẩn hoá 1 entry manifest → ['src' => path, 'scope' => 'module'|'global'].
 *
 * scope 'global' = file cố tình định nghĩa binding top-level cho file khác dùng
 * (utils.js → remToPixels; product/scripts.js → initProduct; featured-section → initAllSwipers…)
 * ⇒ KHÔNG bọc IIFE, nếu không chúng thành private → ReferenceError.
 */
function okhub_normalize_js_entry($entry)
{
	if (is_array($entry)) {
		return [
			'src'   => isset($entry['src']) ? $entry['src'] : '',
			'scope' => isset($entry['scope']) ? $entry['scope'] : 'module',
		];
	}

	return ['src' => (string) $entry, 'scope' => 'module'];
}

/**
 * Đường dẫn + URL thư mục bundle. Trả null nếu không ghi được.
 *
 * @return array{dir:string,url:string}|null
 */
function okhub_bundle_dir()
{
	static $cached = null;

	if (null !== $cached) {
		return $cached ?: null;
	}

	$uploads = wp_upload_dir();

	if (! empty($uploads['error'])) {
		$cached = false;
		return null;
	}

	$dir = trailingslashit($uploads['basedir']) . OKHUB_BUNDLE_SUBDIR;

	if (! wp_mkdir_p($dir) || ! is_writable($dir)) {
		$cached = false;
		return null;
	}

	$cached = [
		'dir' => $dir,
		'url' => trailingslashit($uploads['baseurl']) . OKHUB_BUNDLE_SUBDIR,
	];

	return $cached;
}

/**
 * Gộp danh sách file nguồn thành 1 bundle, trả URL.
 *
 * @param string $key     Tên bundle ('core', 'home-page', …).
 * @param array  $entries Path theme-relative, hoặc ['src' => path, 'scope' => 'global'].
 * @param string $type    'css' | 'js'.
 *
 * @return string|null URL bundle, hoặc null nếu không bundle được (caller PHẢI fallback).
 */
function okhub_bundle($key, array $entries, $type)
{
	if (defined('OKHUB_BUNDLE') && ! OKHUB_BUNDLE) {
		return null;
	}

	if (! $entries || ! in_array($type, ['css', 'js'], true)) {
		return null;
	}

	$paths = okhub_bundle_dir();

	if (! $paths) {
		return null;
	}

	$body = ('css' === $type)
		? okhub_bundle_collect_css($entries)
		: okhub_bundle_collect_js($entries);

	if (null === $body || '' === $body) {
		return null;
	}

	// Fingerprint theo NỘI DUNG, không theo mtime: deploy bằng git/rsync làm mới mtime nhưng
	// nội dung không đổi → giữ nguyên tên file → browser không phải tải lại.
	$fingerprint = substr(md5($body), 0, 10);
	$filename    = "{$key}.{$fingerprint}.{$type}";
	$target      = $paths['dir'] . '/' . $filename;

	if (file_exists($target)) {
		return $paths['url'] . '/' . $filename;
	}

	// Ghi atomic: file chỉ xuất hiện dưới tên cuối khi đã đầy đủ nội dung.
	$tmp = $target . '.' . getmypid() . '.tmp';

	if (false === @file_put_contents($tmp, $body) || ! @rename($tmp, $target)) {
		@unlink($tmp);
		return null;
	}

	okhub_bundle_prune($paths['dir'], $key, $type);

	return $paths['url'] . '/' . $filename;
}

/**
 * Dọn bundle cũ — GIỮ LẠI vài bản gần nhất làm vùng đệm.
 *
 * KHÔNG xoá sạch bản cũ ngay. WP Rocket ĐANG BẬT trên site này: HTML đã cache vẫn trỏ tới tên
 * bundle CŨ. Sửa 1 file CSS → hash mới → nếu xoá ngay bản cũ thì khách đang được phục vụ
 * HTML-cache sẽ gọi file đã bị xoá → 404 → trang mất sạch CSS tới khi purge page-cache.
 * Bundler KHÔNG tự sinh lại được hash cũ (nó chỉ biết nội dung hiện tại) nên vùng đệm này
 * là thứ duy nhất cứu được.
 */
function okhub_bundle_prune($dir, $key, $type)
{
	$keep = defined('OKHUB_BUNDLE_KEEP') ? (int) OKHUB_BUNDLE_KEEP : 5;

	if ($keep < 1) {
		$keep = 1;
	}

	$files = (array) glob($dir . "/{$key}.*.{$type}");

	if (count($files) <= $keep) {
		return;
	}

	// Mới nhất trước.
	usort($files, function ($a, $b) {
		return filemtime($b) <=> filemtime($a);
	});

	foreach (array_slice($files, $keep) as $stale) {
		@unlink($stale);
	}
}

// ============================== CSS =====================//

/**
 * @return string|null null nếu gặp @import (không gộp được).
 */
function okhub_bundle_collect_css(array $entries)
{
	$out  = '';
	$seen = [];

	foreach ($entries as $entry) {
		$src  = is_array($entry) ? (isset($entry['src']) ? $entry['src'] : '') : (string) $entry;
		$file = realpath(get_theme_file_path($src));

		if (! $file || ! is_readable($file) || isset($seen[$file])) {
			continue;
		}

		$seen[$file] = true;
		$source      = @file_get_contents($file);

		if (false === $source) {
			return null;
		}

		$source = str_replace("\r\n", "\n", $source);

		// Lưới an toàn: @import CHỈ có hiệu lực ở đầu file CSS. Nối vào giữa bundle thì browser
		// bỏ qua im lặng → mất nguyên file đó, không báo lỗi. Thà bail còn hơn hỏng ngầm.
		if (preg_match('#^\s*@import\b#m', $source)) {
			return null;
		}

		$relative = okhub_bundle_theme_relative($file);
		$out .= "\n/* ===== {$relative} ===== */\n" . okhub_bundle_rewrite_urls($source, $file) . "\n";
	}

	return '' !== $out ? $out : null;
}

/**
 * Đổi url(...) tương đối → URL tuyệt đối của theme.
 *
 * BẮT BUỘC: bundle nằm trong uploads/, không phải thư mục file gốc, nên mọi path tương đối
 * sẽ vỡ. Cụ thể ở theme này: assets/fonts/stylesheet.css có 18 @font-face dùng
 * url("SF-Pro-Display/…") và phudu.css có 4 @font-face dùng url("phudu/…")
 * → không rewrite là 404 toàn bộ font.
 *
 * Bỏ qua data:, http(s):, //, /… (root-relative) và #… — chúng vốn đã đúng.
 */
function okhub_bundle_rewrite_urls($css, $file)
{
	return preg_replace_callback(
		'#url\(\s*([\'"]?)([^\'")]+)\1\s*\)#i',
		function ($matches) use ($file) {
			$raw = trim($matches[2]);

			if ('' === $raw || preg_match('#^(data:|https?:|//|/|\#)#i', $raw)) {
				return $matches[0];
			}

			// Tách query/fragment trước khi resolve, ghép lại sau.
			$suffix = '';

			if (preg_match('/^([^?#]*)([?#].*)$/', $raw, $split)) {
				$raw    = $split[1];
				$suffix = $split[2];
			}

			$target = okhub_bundle_resolve_relative($file, $raw);

			if (! $target) {
				return $matches[0];
			}

			$url = okhub_bundle_theme_url($target);

			return $url ? 'url("' . $url . $suffix . '")' : $matches[0];
		},
		$css
	);
}

// ============================== JS =====================//

/**
 * @return string|null null nếu gặp ESM (không gộp được thành classic script).
 */
function okhub_bundle_collect_js(array $entries)
{
	$out  = '';
	$seen = [];

	foreach ($entries as $entry) {
		$normalized = okhub_normalize_js_entry($entry);
		$file       = realpath(get_theme_file_path($normalized['src']));

		if (! $file || ! is_readable($file) || isset($seen[$file])) {
			continue;
		}

		$seen[$file] = true;
		$source      = @file_get_contents($file);

		if (false === $source) {
			return null;
		}

		// Chuẩn hoá CRLF → LF. BẮT BUỘC: ~10 file (single-blog/*, contact-page/section-contact-info/*,
		// blog-list/*) dùng CRLF, khiến regex neo cuối dòng không khớp.
		$source = str_replace("\r\n", "\n", $source);

		if ('' === trim($source)) {
			continue; // File 0 byte (service-makeup/assets, service-pgpb/assets).
		}

		// Lưới an toàn: còn import/export = ESM thật → nối thành classic script sẽ ném
		// SyntaxError và giết cả bundle. Thà load nhiều file rời còn hơn chết cả trang.
		if (preg_match('/^[ \t]*(import|export)\b/m', $source)) {
			return null;
		}

		$relative = okhub_bundle_theme_relative($file);

		// Bọc IIFE để tái tạo module scope. BẮT BUỘC: nhiều section khai báo trùng tên top-level
		// (4 file cùng có `sectionBannerScripts`, 3 file cùng có `sectionServicesScripts`…).
		// Dấu ; đứng đầu chặn ASI dính vào chunk trước.
		$code = ('global' === $normalized['scope'])
			? $source . "\n"
			: ";(function(){\n" . $source . "\n})();\n";

		$out .= "\n/* ===== {$relative} ===== */\n" . $code;
	}

	return '' !== $out ? $out : null;
}

// ============================== Path helper =====================//

/**
 * Resolve path tương đối so với thư mục file cha, chặn thoát ra ngoài theme.
 *
 * @return string|false Path tuyệt đối đã realpath, hoặc false.
 */
function okhub_bundle_resolve_relative($from_file, $relative)
{
	$target = realpath(dirname($from_file) . '/' . $relative);

	if (! $target || ! is_readable($target)) {
		return false;
	}

	$roots = array_filter([
		realpath(get_stylesheet_directory()),
		realpath(get_template_directory()),
	]);

	foreach ($roots as $root) {
		if (0 === strpos($target, $root . DIRECTORY_SEPARATOR)) {
			return $target;
		}
	}

	return false;
}

/** Path tuyệt đối → URL tuyệt đối trong theme. */
function okhub_bundle_theme_url($file)
{
	$map = [
		realpath(get_stylesheet_directory()) => get_stylesheet_directory_uri(),
		realpath(get_template_directory())   => get_template_directory_uri(),
	];

	foreach ($map as $root => $uri) {
		if ($root && 0 === strpos($file, $root . DIRECTORY_SEPARATOR)) {
			return untrailingslashit($uri) . str_replace(DIRECTORY_SEPARATOR, '/', substr($file, strlen($root)));
		}
	}

	return null;
}

/** Path tuyệt đối → path theme-relative, chỉ để ghi comment debug trong bundle. */
function okhub_bundle_theme_relative($file)
{
	$root = realpath(get_stylesheet_directory());

	if ($root && 0 === strpos($file, $root . DIRECTORY_SEPARATOR)) {
		return ltrim(str_replace(DIRECTORY_SEPARATOR, '/', substr($file, strlen($root))), '/');
	}

	return basename($file);
}
