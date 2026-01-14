<?php
$VERSION = true ? time() : wp_get_theme()->get('Version');
// define('THEME_VERSION', null);
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
	wp_enqueue_style('swiper', get_theme_file_uri('/assets/css/swiper-bundle.min.css'), [], THEME_VERSION);
	wp_enqueue_script("swiper", get_theme_file_uri('/assets/js/swiper-bundle.min.js'), [], THEME_VERSION, true);
	// Lenis smooth scroll
	wp_enqueue_style(
		'lenis',
		'https://unpkg.com/lenis@1.3.17/dist/lenis.css',
		[],
		THEME_VERSION
	);
	wp_enqueue_script(
		'lenis',
		'https://unpkg.com/lenis@1.3.17/dist/lenis.min.js',
		[],
		THEME_VERSION,
		true
	);
	// GSAP
	wp_enqueue_script(
		'gsap-core',
		'https://cdn.jsdelivr.net/npm/gsap@3.14.1/dist/gsap.min.js',
		[],
		THEME_VERSION,
		true
	);
	wp_enqueue_script(
		'gsap-scroll-trigger',
		'https://cdn.jsdelivr.net/npm/gsap@3.14.1/dist/ScrollTrigger.min.js',
		[],
		THEME_VERSION,
		true
	);
	wp_enqueue_script(
		'gsap-scroll-smoother',
		'https://cdn.jsdelivr.net/npm/gsap@3.14.1/dist/ScrollSmoother.min.js',
		[],
		THEME_VERSION,
		true
	);
	// AOS
	wp_enqueue_style(
		'aos',
		'https://unpkg.com/aos@2.3.1/dist/aos.css',
		[],
		THEME_VERSION
	);
	wp_enqueue_script(
		'aos',
		'https://unpkg.com/aos@2.3.1/dist/aos.js',
		[],
		THEME_VERSION,
		true
	);
}
add_action('wp_enqueue_scripts', 'wp_enqueue_lib', 1000);

// ============================== end wp_enqueue lib =====================//

// ============================== wp_enqueue lib =====================//
function  wp_enqueue_local()
{
	$wp_enqueue_mapping = [
		[
			'type' => 'style',
			'handle' => 'stylesheet',
			'src' => get_theme_file_uri('/assets/fonts/stylesheet.css'),
			'deps' => [],
			'ver' => THEME_VERSION,
			'in_footer' => false,
			'condition' => true
		],
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
			'type' => 'script',
			'handle' => 'app',
			'src' => get_theme_file_uri('/assets/js/app.js'),
			'deps' => [],
			'ver' => THEME_VERSION,
			'in_footer' => true,
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
			'type' => 'script',
			'handle' => '_custom_drawer',
			'src' => get_theme_file_uri('/assets/js/custom-drawer.js'),
			'deps' => [],
			'ver' => THEME_VERSION,
			'in_footer' => true,
			'condition' => true
		],
		[
			'type' => 'style',
			'handle' => 'datepicker',
			'src' => get_theme_file_uri('/assets/css/flatpickr.min.css'),
			'deps' => [],
			'ver' => THEME_VERSION,
			'in_footer' => false,
			'condition' => true
		],
		[
			'type' => 'script',
			'handle' => 'datepicker',
			'src' => get_theme_file_uri('/assets/js/flatpickr.min.js'),
			'deps' => [],
			'ver' => THEME_VERSION,
			'in_footer' => true,
			'condition' => true
		],
		// Header
		[
			'type' => 'style',
			'handle' => 'header',
			'src' => get_theme_file_uri('/template-parts/layouts/header/assets/styles.css'),
			'deps' => [],
			'ver' => THEME_VERSION,
			'in_header' => false,
			'condition' => true
		],
		[
			'type' => 'script',
			'handle' => 'header',
			'src' => get_theme_file_uri('/template-parts/layouts/header/assets/scripts.js'),
			'deps' => [],
			'ver' => THEME_VERSION,
			'in_footer' => true,
			'condition' => true
		],
		// Footer
		[
			'type' => 'style',
			'handle' => 'footer',
			'src' => get_theme_file_uri('/template-parts/layouts/footer/assets/styles.css'),
			'deps' => [],
			'ver' => THEME_VERSION,
			'in_footer' => false,
			'condition' => true
		],
		[
			'type' => 'script',
			'handle' => 'footer',
			'src' => get_theme_file_uri('/template-parts/layouts/footer/assets/scripts.js'),
			'deps' => [],
			'ver' => THEME_VERSION,
			'in_footer' => true,
			'condition' => true
		],
		// Home page
		[
			'type' => 'style',
			'handle' => 'home-page',
			'src' => get_theme_file_uri('/template-parts/home-page/assets/styles.css'),
			'deps' => [],
			'ver' => THEME_VERSION,
			'in_footer' => false,
			'condition' => is_page_template('front-page.php')
		],
		[
			'type' => 'script',
			'handle' => 'home-page',
			'src' => get_theme_file_uri('/template-parts/home-page/assets/scripts.js'),
			'deps' => [],
			'ver' => THEME_VERSION,
			'in_footer' => true,
			'condition' => is_page_template('front-page.php')
		]
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

	wp_localize_script('header', 'wpApiSettings', [
		'nonce' => wp_create_nonce('wp_rest'),
		'root'  => esc_url_raw(rest_url()),
	]);
}

add_action('wp_enqueue_scripts', 'wp_enqueue_local', 1001);

// ============================== wp_enqueue javascript =====================//
function wp_enqueue_javascript()
{
	$wp_enqueue_mapping = [
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

add_action('wp_enqueue_scripts', 'wp_enqueue_javascript', 1003);
add_filter('script_loader_tag', 'add_type_attribute', 10, 3);

function add_type_attribute($tag, $handle, $src)
{
	// if not your script, do nothing and return original $tag
	// if ('front-page' !== $handle && 'offcanvas' !== $handle) {
	$module_handles = ['home-page'];
	if (!in_array($handle, $module_handles, true)) {
		return $tag;
	}

	// change the script tag by adding type="module" and return it.
	$tag = '<script type="module" src="' . esc_url($src) . '"></script>';
	return $tag;
}

// ============================== wp_enqueue lib =====================//