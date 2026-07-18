<?php
/**
 * Ảnh thuộc về theme (trang trí, icon, overlay...) — phục vụ qua file tĩnh trong
 * assets/images/ thay vì hardcode attachment ID của media library.
 *
 * Map sinh tự động ở inc/theme-images-map.php, key dạng "khu-vuc/ten"
 * (vd 'icons/arrow', 'services/offer').
 *
 * @param string $key   Key trong map, vd 'icons/arrow'
 * @param array  $args  class, alt, loading ('lazy'|'eager'), decoding, sizes (override),
 *                      id, style, extra (chuỗi attr thô)
 * @return string HTML <img> (rỗng nếu key không tồn tại)
 */

if (!function_exists('okhub_img_map')) {
	function okhub_img_map() {
		static $map = null;
		if ($map === null) {
			$file = get_theme_file_path('/inc/theme-images-map.php');
			$map = is_file($file) ? require $file : array();
		}
		return $map;
	}
}

if (!function_exists('okhub_img')) {
	function okhub_img($key, $args = array()) {
		$map = okhub_img_map();
		if (empty($map[$key])) return '';
		$it   = $map[$key];
		$base = get_theme_file_uri('/assets/images/');

		$class    = isset($args['class'])    ? $args['class']    : '';
		$alt      = isset($args['alt'])      ? $args['alt']      : '';
		$loading  = array_key_exists('loading', $args)  ? $args['loading']  : 'lazy';
		$decoding = array_key_exists('decoding', $args) ? $args['decoding'] : 'async';

		$attrs = array();
		$attrs[] = 'src="' . esc_url($base . $it['src']) . '"';

		if (!empty($it['srcset'])) {
			$srcset = implode(', ', array_map(function ($p) use ($base) {
				$p  = trim($p);
				$sp = strrpos($p, ' ');
				return esc_url($base . substr($p, 0, $sp)) . ' ' . substr($p, $sp + 1);
			}, explode(',', $it['srcset'])));
			$attrs[] = 'srcset="' . esc_attr($srcset) . '"';
			$sizes = isset($args['sizes']) ? $args['sizes'] : (isset($it['sizes']) ? $it['sizes'] : '100vw');
			$attrs[] = 'sizes="' . esc_attr($sizes) . '"';
		} elseif (isset($args['sizes'])) {
			$attrs[] = 'sizes="' . esc_attr($args['sizes']) . '"';
		}

		if (!empty($it['w'])) $attrs[] = 'width="' . (int) $it['w'] . '"';
		if (!empty($it['h'])) $attrs[] = 'height="' . (int) $it['h'] . '"';
		if ($class !== '')    $attrs[] = 'class="' . esc_attr($class) . '"';
		$attrs[] = 'alt="' . esc_attr($alt) . '"';
		if ($loading)  $attrs[] = 'loading="' . esc_attr($loading) . '"';
		if ($decoding) $attrs[] = 'decoding="' . esc_attr($decoding) . '"';
		if (!empty($args['id']))    $attrs[] = 'id="' . esc_attr($args['id']) . '"';
		if (!empty($args['style'])) $attrs[] = 'style="' . esc_attr($args['style']) . '"';
		if (!empty($args['extra'])) $attrs[] = $args['extra'];

		return '<img ' . implode(' ', $attrs) . '>';
	}
}
