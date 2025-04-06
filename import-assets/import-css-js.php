<?php
$VERSION = WP_DEBUG ? time() : wp_get_theme()->get('Version');;
define('THEME_VERSION', $VERSION);
// ============================== start wp_enqueue lib =====================//
// Add preconnect for Google Fonts
function my_add_preconnects($hints, $relation_type)
{
	if ('preconnect' === $relation_type) {
		$hints[] = [
			'href' => 'https://fonts.googleapis.com',
			'crossorigin' => 'anonymous',
		];
		$hints[] = [
			'href' => 'https://fonts.gstatic.com',
			'crossorigin' => 'anonymous',
		];
	}
	return $hints;
}
add_filter('wp_resource_hints', 'my_add_preconnects', 10, 2);

function  wp_enqueue_lib()
{
	// wp_enqueue_style('link-tag-id', 'url');
	// wp_enqueue_script("ffmpeg", "https://cdn.jsdelivr.net/npm/@ffmpeg/ffmpeg@0.12.15/dist/umd/ffmpeg.min.js", [], false, true);
	wp_enqueue_style('font-Fraunces', 'https://fonts.googleapis.com/css2?family=Fraunces:ital,opsz,wght@0,9..144,100..900;1,9..144,100..900&display=swap', [], THEME_VERSION);

	wp_enqueue_style('swiper', 'https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.css', [], THEME_VERSION);
	wp_enqueue_script("swiper", 'https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.js', [], THEME_VERSION, true);

	wp_enqueue_style('plyr', get_theme_file_uri('/assets/css/plyr.css'), [], THEME_VERSION);
	wp_enqueue_script("plyr", get_theme_file_uri('/assets/js/plyr.js'), [], THEME_VERSION, true);
}
add_action('wp_enqueue_scripts', 'wp_enqueue_lib', 1000);

// ============================== end wp_enqueue lib =====================//

// ============================== wp_enqueue lib =====================//
function  wp_enqueue_local()
{
	$wp_enqueue_mapping = [
		[
			'type' => 'style',
			'handle' => '_reset',
			'src' => get_theme_file_uri('/assets/css/_reset.css'),
			'deps' => [],
			'ver' => THEME_VERSION,
			'in_footer' => false,
			'condition' => true
		],
		[
			'type' => 'style',
			'handle' => '_variables',
			'src' => get_theme_file_uri('/assets/css/_variables.css'),
			'deps' => [],
			'ver' => THEME_VERSION,
			'in_footer' => false,
			'condition' => true
		],
		[
			'type' => 'style',
			'handle' => 'global',
			'src' => get_theme_file_uri('/assets/css/global.css'),
			'deps' => [],
			'ver' => THEME_VERSION,
			'in_footer' => false,
			'condition' => true
		],
		[
			'type' => 'style',
			'handle' => 'fonts',
			'src' => get_theme_file_uri('/assets/fonts/stylesheet.css'),
			'deps' => [],
			'ver' => THEME_VERSION,
			'in_footer' => false,
			'condition' => true
		],
		[
			'type' => 'script',
			'handle' => '_utils',
			'src' => get_theme_file_uri('/assets/js/utils.js'),
			'deps' => [],
			'ver' => THEME_VERSION,
			'in_footer' => true,
			'condition' => true
		],
		[
			'type' => 'script',
			'handle' => '_custom_option',
			'src' => get_theme_file_uri('/assets/js/custom-option.js'),
			'deps' => [],
			'ver' => THEME_VERSION,
			'in_footer' => true,
			'condition' => true
		],
		[
			'type' => 'style',
			'handle' => 'header',
			'src' => get_theme_file_uri('/template-parts/header/assets/styles.css'),
			'deps' => [],
			'ver' => THEME_VERSION,
			'in_footer' => false,
			'condition' => true
		],
		[
			'type' => 'script',
			'handle' => 'header',
			'src' => get_theme_file_uri('/template-parts/header/assets/scripts.js'),
			'deps' => [],
			'ver' => THEME_VERSION,
			'in_footer' => true,
			'condition' => true
		],
		[
			'type' => 'style',
			'handle' => 'footer',
			'src' => get_theme_file_uri('/template-parts/footer/assets/styles.css'),
			'deps' => [],
			'ver' => THEME_VERSION,
			'in_footer' => false,
			'condition' => true
		],
		[
			'type' => 'script',
			'handle' => 'footer',
			'src' => get_theme_file_uri('/template-parts/footer/assets/scripts.js'),
			'deps' => [],
			'ver' => THEME_VERSION,
			'in_footer' => true,
			'condition' => true
		],
		[
			'type' => 'style',
			'handle' => 'front-page',
			'src' => get_theme_file_uri('/template-parts/front-page/assets/styles.css'),
			'deps' => [],
			'ver' => THEME_VERSION,
			'in_footer' => false,
			'condition' => is_front_page()
		],
		[
			'type' => 'script',
			'handle' => 'front-page',
			'src' => get_theme_file_uri('/template-parts/front-page/assets/scripts.js'),
			'deps' => [],
			'ver' => THEME_VERSION,
			'in_footer' => true,
			'condition' => is_front_page()
		],
		[
			'type' => 'style',
			'handle' => 'page-testimonial',
			'src' => get_theme_file_uri('/template-parts/page-testimonial/assets/styles.css'),
			'deps' => [],
			'ver' => THEME_VERSION,
			'in_footer' => false,
			'condition' => is_page_template('page-testimonial.php')
		],
		[
			'type' => 'script',
			'handle' => 'page-testimonial',
			'src' => get_theme_file_uri('/template-parts/page-testimonial/assets/scripts.js'),
			'deps' => [],
			'ver' => THEME_VERSION,
			'in_footer' => true,
			'condition' => is_page_template('page-testimonial.php')
		],
		[
			'type' => 'style',
			'handle' => 'page-tours',
			'src' => get_theme_file_uri('/template-parts/page-tours/assets/styles.css'),
			'deps' => [],
			'ver' => THEME_VERSION,
			'in_footer' => false,
			'condition' => is_page_template('page-tours.php')
		],
		[
			'type' => 'script',
			'handle' => 'page-tours',
			'src' => get_theme_file_uri('/template-parts/page-tours/assets/scripts.js'),
			'deps' => [],
			'ver' => THEME_VERSION,
			'in_footer' => true,
			'condition' => is_page_template('page-tours.php')
		],
		[
			'type' => 'style',
			'handle' => 'single-tours',
			'src' => get_theme_file_uri('/template-parts/single-tours/assets/styles.css'),
			'deps' => [],
			'ver' => THEME_VERSION,
			'in_footer' => false,
			'condition' => is_singular('tours')
		],
		[
			'type' => 'script',
			'handle' => 'single-tours',
			'src' => get_theme_file_uri('/template-parts/single-tours/assets/scripts.js'),
			'deps' => [],
			'ver' => THEME_VERSION,
			'in_footer' => true,
			'condition' => is_singular('tours')
		],
		[
			'type' => 'style',
			'handle' => 'about-us',
			'src' => get_theme_file_uri('/template-parts/about-us/assets/styles.css'),
			'deps' => [],
			'ver' => THEME_VERSION,
			'in_footer' => false,
			'condition' => is_page_template('page-about-us.php')
		],
		[
			'type' => 'script',
			'handle' => 'about-us',
			'src' => get_theme_file_uri('/template-parts/about-us/assets/scripts.js'),
			'deps' => [],
			'ver' => THEME_VERSION,
			'in_footer' => true,
			'condition' => is_page_template('page-about-us.php')
		],
		[
			'type' => 'style',
			'handle' => 'csr-activities',
			'src' => get_theme_file_uri('/template-parts/csr-activities/assets/styles.css'),
			'deps' => [],
			'ver' => THEME_VERSION,
			'in_footer' => false,
			'condition' => is_page_template('csr-activities.php')
		],
		[
			'type' => 'script',
			'handle' => 'csr-activities',
			'src' => get_theme_file_uri('/template-parts/csr-activities/assets/scripts.js'),
			'deps' => [],
			'ver' => THEME_VERSION,
			'in_footer' => true,
			'condition' => is_page_template('csr-activities.php')
		],
	];

	foreach ($wp_enqueue_mapping as $wp_enqueue) {
		if (!$wp_enqueue['condition']) {
			continue;
		}
		if ($wp_enqueue['type'] == 'style') {
			wp_enqueue_style($wp_enqueue['handle'], $wp_enqueue['src'], $wp_enqueue['deps'], $wp_enqueue['ver']);
		} else {
			wp_enqueue_script($wp_enqueue['handle'], $wp_enqueue['src'], $wp_enqueue['deps'], $wp_enqueue['ver'], $wp_enqueue['in_footer']);
		}
	}
}

add_action('wp_enqueue_scripts', 'wp_enqueue_local', 1001);

add_filter('script_loader_tag', 'add_type_attribute', 10, 3);

function add_type_attribute($tag, $handle, $src)
{
	// if not your script, do nothing and return original $tag
	// if ('front-page' !== $handle && 'offcanvas' !== $handle) {
	$module_handles = ['front-page', 'about-us', 'csr-activities','page-testimonial','page-tours','single-tours'];
		if (!in_array($handle, $module_handles, true)) {
				return $tag;
			}

	// change the script tag by adding type="module" and return it.
	$tag = '<script type="module" src="' . esc_url($src) . '"></script>';
	return $tag;
}

// ============================== wp_enqueue lib =====================//