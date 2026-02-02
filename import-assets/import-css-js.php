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
	wp_enqueue_style('font-OpenSans', 'https://fonts.googleapis.com/css2?family=Open+Sans:ital,wght@0,300..800;1,300..800&display=swap', [], THEME_VERSION);
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
	wp_enqueue_script(
		'gsap-custom-ease',
		'https://cdn.jsdelivr.net/npm/gsap@3.14.1/dist/CustomEase.min.js',
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
            'type'      => 'script',
            'handle'    => 'contact-form-submit-lock',
            'src'       => get_theme_file_uri('/assets/js/contact-form.js'),
            'deps'      => ['contact-form-7'],
            'ver'       => THEME_VERSION,
            'in_footer' => true,
            'condition' => true
        ],
// 		[
// 			'type' => 'style',
// 			'handle' => 'datepicker',
// 			'src' => get_theme_file_uri('/assets/css/flatpickr.min.css'),
// 			'deps' => [],
// 			'ver' => THEME_VERSION,
// 			'in_footer' => false,
// 			'condition' => true
// 		],
// 		[
// 			'type' => 'script',
// 			'handle' => 'datepicker',
// 			'src' => get_theme_file_uri('/assets/js/flatpickr.min.js'),
// 			'deps' => [],
// 			'ver' => THEME_VERSION,
// 			'in_footer' => true,
// 			'condition' => true
// 		],
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
		// CTA
		[
			'type' => 'style',
			'handle' => 'cta',
			'src' => get_theme_file_uri('/template-parts/components/cta/assets/styles.css'),
			'deps' => [],
			'ver' => THEME_VERSION,
			'in_footer' => false,
			'condition' => true
		],
		[
			'type' => 'script',
			'handle' => 'cta',
			'src' => get_theme_file_uri('/template-parts/components/cta/assets/scripts.js'),
			'deps' => [],
			'ver' => THEME_VERSION,
			'in_footer' => true,
			'condition' => true
		],
        // Popup destination 		
		[
			'type' => 'style',
			'handle' => 'popup-destination',
			'src' => get_theme_file_uri('/template-parts/components/popup-destination/styles.css'),
			'deps' => [],
			'ver' => THEME_VERSION,
			'in_footer' => false,
			'condition' => true
		],
		[
			'type' => 'script',
			'handle' => 'popup-destination',
			'src' => get_theme_file_uri('/template-parts/components/popup-destination/init.js'),
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
			'deps' => ['swiper'],
			'ver' => THEME_VERSION,
			'in_footer' => true,
			'condition' => is_page_template('front-page.php')
		],
		// Hotels and Resorts Page
		[
			'type' => 'style',
			'handle' => 'hotels-and-resorts-page',
			'src' => get_theme_file_uri('/template-parts/hotels-and-resorts-page/assets/styles.css'),
			'deps' => [],
			'ver' => THEME_VERSION,
			'in_footer' => false,
			'condition' => is_page_template('hotels-and-resorts-page.php')
		],
		[
			'type' => 'script',
			'handle' => 'hotels-and-resorts-page',
			'src' => get_theme_file_uri('/template-parts/hotels-and-resorts-page/assets/scripts.js'),
			'deps' => [],
			'ver' => THEME_VERSION,
			'in_footer' => true,
			'condition' => is_page_template('hotels-and-resorts-page.php')
		],
		// Category /category/[slug]
		[
			'type' => 'style',
			'handle' => 'blog-page',
			'src' => get_theme_file_uri('/template-parts/blog-page/assets/styles.css'),
			'deps' => [],
			'ver' => THEME_VERSION,
			'in_footer' => false,
			'condition' => is_page_template('blog-page.php') || is_category()
		],
		[
			'type' => 'script',
			'handle' => 'blog-page',
			'src' => get_theme_file_uri('/template-parts/blog-page/assets/scripts.js'),
			'deps' => ['gsap-core', 'gsap-scroll-trigger', 'gsap-custom-ease', 'swiper'],
			'ver' => THEME_VERSION,
			'in_footer' => true,
			'condition' => is_page_template('blog-page.php') || is_category()
		],
		// Taxonomy Destination: /destination/[slug]
		[
            'type' => 'style',
            'handle' => 'taxonomy-destination',
            'src' => get_theme_file_uri('/template-parts/destination-page/assets/styles.css'),
            'deps' => [],
            'ver' => THEME_VERSION,
            'in_footer' => false,
            'condition' => is_tax('destination'), 
        ],
        [
            'type' => 'script',
            'handle' => 'taxonomy-destination',
            'src' => get_theme_file_uri('/template-parts/destination-page/assets/scripts.js'),
            'deps' => ['gsap-core', 'gsap-scroll-trigger', 'gsap-custom-ease', 'swiper'],
            'ver' => THEME_VERSION,
            'in_footer' => true,
            'condition' => is_tax('destination'), 
        ],
        // Taxonomy Holiday Type: /holiday-type/[slug]
        [
            'type' => 'style',
            'handle' => 'taxonomy-holiday-type',
            'src' => get_theme_file_uri('/template-parts/detail-holiday-type-page/assets/styles.css'),
            'deps' => [],
            'ver' => THEME_VERSION,
            'in_footer' => false,
            'condition' => is_tax('holiday-type'), 
        ],
        [
            'type' => 'script',
            'handle' => 'taxonomy-holiday-type',
            'src' => get_theme_file_uri('/template-parts/detail-holiday-type-page/assets/scripts.js'),
            'deps' => ['gsap-core', 'gsap-scroll-trigger', 'gsap-custom-ease', 'swiper'],
            'ver' => THEME_VERSION,
            'in_footer' => true,
            'condition' => is_tax('holiday-type'), 
        ],
		[
			'type' => 'style',
			'handle' => 'nouislider',
			'src' => get_theme_file_uri('/assets/css/nouislider.min.css'),
			'deps' => [],
			'ver' => THEME_VERSION,
			'in_footer' => false,
			'condition' => is_page_template('page-contact.php')
		],
		[
			'type' => 'script',
			'handle' => 'alpinejs',
			'src' => get_theme_file_uri('/assets/js/alpinejs@3.x.x.min.js'),
			'deps' => ['contact-page'],
			'ver' => THEME_VERSION,
			'in_footer' => true,
			'condition' => is_page_template('page-contact.php')
		],
		[
			'type' => 'script',
			'handle' => 'nouislider',
			'src' => get_theme_file_uri('/assets/js/nouislider.min.js'),
			'deps' => [],
			'ver' => THEME_VERSION,
			'in_footer' => true,
			'condition' => is_page_template('page-contact.php')
		],
		[
			'type' => 'script',
			'handle' => 'validate-js',
			'src' => get_theme_file_uri('/assets/js/validate.min.js'),
			'deps' => [],
			'ver' => THEME_VERSION,
			'in_footer' => true,
			'condition' => is_page_template('page-contact.php')
		],
		[
			'type' => 'script',
			'handle' => 'contact-page',
			'src' => get_theme_file_uri('/template-parts/page-contact/assets/scripts.js'),
			'deps' => ['app', 'nouislider', 'validate-js'],
			'ver' => THEME_VERSION,
			'in_footer' => true,
			'condition' => is_page_template('page-contact.php')
		],
		[
			'type' => 'style',
			'handle' => 'contact-page',
			'src' => get_theme_file_uri('/template-parts/page-contact/assets/styles.css'),
			'deps' => [],
			'ver' => THEME_VERSION,
			'in_footer' => false,
			'condition' => is_page_template('page-contact.php')
		],
		// Detail Tour Page
		[
			'type' => 'style',
			'handle' => 'detail-tour-page',
			'src' => get_theme_file_uri('/template-parts/detail-tour-page/assets/styles.css'),
			'deps' => [],
			'ver' => THEME_VERSION,
			'in_footer' => false,
			'condition' => is_singular('tour')
		],
		[
			'type' => 'script',
			'handle' => 'detail-tour-page',
			'src' => get_theme_file_uri('/template-parts/detail-tour-page/assets/scripts.js'),
			'deps' => [],
			'ver' => THEME_VERSION,
			'in_footer' => true,
			'condition' => is_singular('tour')
		],
        // Blog destail 		
		[
			'type' => 'style',
			'handle' => 'single-post',
			'src' => get_theme_file_uri('/template-parts/single/assets/styles.css'),
			'deps' => [],
			'ver' => THEME_VERSION,
			'in_footer' => false,
			'condition' => is_singular('post')
		],
		[
			'type' => 'script',
			'handle' => 'single-post',
			'src' => get_theme_file_uri('/template-parts/single/assets/scripts.js'),
			'deps' => ['swiper'],
			'ver' => THEME_VERSION,
			'in_footer' => true,
			'condition' => is_singular('post')
		],
		// Thank You Page
		[
			'type' => 'style',
			'handle' => 'thankyou-page',
			'src' => get_theme_file_uri('/template-parts/thankyou-page/assets/styles.css'),
			'deps' => [],
			'ver' => THEME_VERSION,
			'in_footer' => false,
			'condition' => is_page_template('thankyou-page.php')
		],
		// 404 Page
		[
			'type' => 'style',
			'handle' => '404-page',
			'src' => get_theme_file_uri('/assets/css/404.css'),
			'deps' => [],
			'ver' => THEME_VERSION,
			'in_footer' => false,
			'condition' => is_404()
		],
				[
			'type' => 'script',
			'handle' => 'review',
			'src' => get_theme_file_uri('/template-parts/page-contact/assets/review.js'),
			'deps' => [],
			'ver' => THEME_VERSION,
			'in_footer' => true,
			'condition' => is_page_template('page-contact.php')
		],
		// About Us Page
		[
			'type' => 'style',
			'handle' => 'aboutus-page',
			'src' => get_theme_file_uri('/template-parts/aboutus-page/assets/styles.css'),
			'deps' => [],
			'ver' => THEME_VERSION,
			'in_footer' => false,
			'condition' => is_page_template('aboutus-page.php')
		],
		[
			'type' => 'script',
			'handle' => 'aboutus-page',
			'src' => get_theme_file_uri('/template-parts/aboutus-page/assets/scripts.js'),
			'deps' => [],
			'ver' => THEME_VERSION,
			'in_footer' => true,
			'condition' => is_page_template('aboutus-page.php')
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
	$module_handles = ['home-page', 'taxonomy-destination', 'taxonomy-holiday-type','detail-tour-page', 'blog-page', 'hotels-and-resorts-page', 'contact-page', 'review', 'popup-destination', 'aboutus-page', 'single-post'];
	if (!in_array($handle, $module_handles, true)) {
		return $tag;
	}

	// change the script tag by adding type="module" and return it.
	$tag = '<script type="module" src="' . esc_url($src) . '"></script>';
	return $tag;
}

// ============================== wp_enqueue lib =====================//