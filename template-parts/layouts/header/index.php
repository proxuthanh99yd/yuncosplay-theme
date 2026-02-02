<?php
$header = get_field('header', 'option');
if (!empty($header)) {
	$header_logo = $header['logo'];
	$header_logo_mobile = $header['logo_mobile'];
	$header_whatsapp_link = $header['whatsapp_contact'];
	$header_phone_link = $header['phone_contact'];
	$header_contact_link = $header['button_contact'];
}

$icon_language_america_id = 1329;
$icon_language_english_id = 1330;
$icon_language_japan_id = 1331;

$icon_email_id = 1794;
$icon_arrow_down_id = 1060;
$icon_search_id = 1061;
$icon_close_id = 1062;
$icon_chatbot_id = 1969;


// Determine which icon and text to show
$trigger_icon_id = $icon_language_english_id;
$trigger_text =  'English';
?>


<?php
// Check if we're on the thankyou page
$is_thankyou_page = false;
if (is_page_template('thankyou-page.php') || 
	(is_page() && get_page_template_slug() === 'thankyou-page.php') ||
	(isset($_GET) && strpos($_SERVER['REQUEST_URI'], 'thankyou') !== false)) {
	$is_thankyou_page = true;
}

// Check if we're on the 404 page
$is_404_page = is_404();

$header_classes = 'header header--default';
if ($is_thankyou_page || $is_404_page) {
	$header_classes .= ' header--mobile-solid';
}
?>

<header class="<?= esc_attr($header_classes); ?>">
	<div class="header-top">
		<div class="header-container header-top__inner">
			<div class="header-top__left-mb">
				<?= wp_get_attachment_image($icon_email_id, 'full', false, array('class' => '')) ?>
			</div>
			<a href="/" class="header-logo">
				<?= wp_get_attachment_image($header_logo, 'full', false, array('class' => 'header-logo__desktop')) ?>
				<?= wp_get_attachment_image($header_logo_mobile, 'full', false, array('class' => 'header-logo__mobile')) ?>
			</a>
			<button class="header-top__right-mb menu-toggle" id="header-mobile-menu-toggle">
				<svg class="menu-icon" xmlns="http://www.w3.org/2000/svg" width="33" height="27" viewBox="0 0 33 27" fill="none">
					<!-- Hamburger (3 lines) -->
					<path class="line h-line h-1" d="M2.8 6.5H30.2" />
					<path class="line h-line h-2" d="M2.8 13.5H30.2" />
					<path class="line h-line h-3" d="M2.8 20.5H30.2" />

					<!-- X (2 lines) -->
					<path class="line x-line x-1" d="M5 13.5H28" />
					<path class="line x-line x-2" d="M5 13.5H28" />
				</svg>
				<span class="header-top__right-mb__text">Menu</span>
			</button>
			<div class="header-info">
				<div class="header-info__item header-info__item--language header-info__item-language-dropdown">
					<div class="header-info__item-language-dropdown-trigger notranslate">
						<span class="header-info__item-language-dropdown-trigger-icon notranslate">
							<?= wp_get_attachment_image($trigger_icon_id, 'full', false, array('class' => '')) ?>
						</span>
						<span class="header-info__item-language-dropdown-trigger-text notranslate"><?= esc_html($trigger_text) ?></span>
					</div>
					<div class="header-info__item-language-dropdown-content notranslate header__lang-dropdown gtranslate_wrapper">
						<div class="gtranslate_inner">
							<p class="header-info__item-language-dropdown-content-title notranslate">Change Language</p>
							<ul class="header-info__item-language-dropdown-content-list notranslate">
								<?= do_shortcode('[gtranslate]') ?>
							</ul>
						</div>
					</div>
					<!-- Google Translate Widget (hidden) -->
					<div id="google_translate_element" style="display: none;"></div>
				</div>

				<div class="header-info__item">
					<?= wp_get_attachment_image($icon_chatbot_id, 'full', false, array('class' => 'header-info__item-link--chat-icon')) ?>
				</div>

				<div class="header-info__item">
					<?php if (!empty($header_whatsapp_link) && !empty($header_whatsapp_link['url'])): ?>
					<a class="header-info__item-link header-info__item-link--whatsapp" href="<?= $header_whatsapp_link['url']; ?>" target="<?= $header_whatsapp_link['target']; ?>">
						<span class="header-info__item-link--whatsapp-text"><?= $header_whatsapp_link['title']; ?></span>
					</a>
					<?php endif; ?>
				</div>

				<div class="header-info__item">
					<?php if (!empty($header_phone_link) && !empty($header_phone_link['url'])): ?>
					<a class="header-info__item-link header-info__item-link--phone" href="<?= $header_phone_link['url']; ?>" target="<?= $header_phone_link['target']; ?>">
						<?= wp_get_attachment_image(1056, 'full', false, array('class' => 'header-info__item-link--phone-icon')) ?>
						<span class="header-info__item-link--phone-text"><?= $header_phone_link['title']; ?></span>
					</a>
					<?php endif; ?>

					<span class="header-info__item-or-text">or</span>
				</div>

				<div class="header-info__item">
					<?php if (!empty($header_contact_link) && !empty($header_contact_link['url'])): ?>
					<a class="header-info__item-link header-info__item-link--contact compound-avian-button" href="<?= $header_contact_link['url']; ?>" target="<?= $header_contact_link['target']; ?>">
						<div class="compound-avian-button__content">
							<span class="compound-avian-button__content-text header-info__item-link--contact-text">
								<?= $header_contact_link['title']; ?>
							</span>
						</div>
					</a>
					<?php endif; ?>
				</div>
			</div>
		</div>
	</div>
	<div class="header-bottom">
		<div class="header-container header-bottom__inner">
			<nav class="header-navigation">
				<ul class="header-navigation__list">
					<?php
					// Get hotel_and_resorts link from ACF
					$hotel_and_resorts_link = '#';
					$hotel_and_resorts_text = 'Hotel & resorts';
					if (!empty($header['navigation']['hotel_and_resorts']['link'])) {
						$hotel_link = $header['navigation']['hotel_and_resorts']['link'];
						$hotel_and_resorts_link = !empty($hotel_link['url']) ? $hotel_link['url'] : '#';
						$hotel_and_resorts_text = !empty($hotel_link['title']) ? $hotel_link['title'] : $hotel_and_resorts_text;
					}

					// Get destinations title from ACF
					$destination_text = 'Destination';
					if (!empty($header['navigation']['destinations']['title'])) {
						$destination_text = $header['navigation']['destinations']['title'];
					}

					// Get blog_categories title from ACF
					$inspiration_text = 'Inspiration';
					if (!empty($header['navigation']['blog_categories']['title'])) {
						$inspiration_text = $header['navigation']['blog_categories']['title'];
					}

					// Get holiday_types title from ACF
					$holidays_types_text = 'Holidays types';
					if (!empty($header['navigation']['holiday_types']['title'])) {
						$holidays_types_text = $header['navigation']['holiday_types']['title'];
					}

					// Get about_us title from ACF
					$about_us_text = 'About Us';
					if (!empty($header['navigation']['about_us']['title'])) {
						$about_us_text = $header['navigation']['about_us']['title'];
					}

					$nav_items = [
						['key' => 'destination', 'text' => $destination_text, 'show_icon' => true, 'is_link' => false],
						['key' => 'holidays-types', 'text' => $holidays_types_text, 'show_icon' => true, 'is_link' => false],
						['key' => 'inspiration', 'text' => $inspiration_text, 'show_icon' => true, 'is_link' => false],
						['key' => 'hotel-resorts', 'text' => $hotel_and_resorts_text, 'show_icon' => false, 'is_link' => true, 'href' => $hotel_and_resorts_link],
						['key' => 'about', 'text' => $about_us_text, 'show_icon' => true, 'is_link' => false],
					];
					foreach ($nav_items as $item):
					?>
					<li class="header-navigation__item">
						<?php if ($item['is_link']): ?>
						<a href="<?= $item['href'] ?? '#'; ?>" class="header-navigation__item-link header-navigation__item-link--<?= $item['key']; ?>">
							<span class="header-navigation__item-link__text"><?= $item['text']; ?></span>
							<?php if ($item['show_icon']): ?>
							<?= wp_get_attachment_image($icon_arrow_down_id, 'full', false, array('class' => 'header-navigation__item-link__icon')) ?>
							<?php endif; ?>
						</a>
						<?php else: ?>
						<div class="header-navigation__item-link header-navigation__item-link--<?= $item['key']; ?>" data-mega-menu="<?= $item['key']; ?>">
							<span class="header-navigation__item-link__text"><?= $item['text']; ?></span>
							<?php if ($item['show_icon']): ?>
							<svg class="header-navigation__item-link__icon" xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 16 16" fill="none">
								<path d="M12.6663 6L7.99967 10.6667L3.33301 6" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/>
							</svg>
							<?php endif; ?>
						</div>
						<?php endif; ?>
					</li>
					<?php endforeach; ?>
				</ul>
			</nav>

			<div id="header-search" class="header-search">
				<div class="header-search__input-wrapper">
					<label class="header-search__input">
						<span class="header-search__button-close">
							<?= wp_get_attachment_image($icon_close_id, 'full', false, array('class' => 'header-search__button-close__icon')) ?>
						</span>
						<input type="text" id="header-search-input" placeholder="Search destinations, experiences or hotels...">

						<span class="header-search__button-search">
							<?= wp_get_attachment_image($icon_search_id, 'full', false, array('class' => '')) ?>
						</span>
					</label>
				</div>
				<label for="header-search-input" class="header-search__button">
					<svg class="header-search__button-icon" xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 20 20" fill="none">
						<path d="M9.58268 17.5001C13.9549 17.5001 17.4993 13.9557 17.4993 9.58341C17.4993 5.21116 13.9549 1.66675 9.58268 1.66675C5.21043 1.66675 1.66602 5.21116 1.66602 9.58341C1.66602 13.9557 5.21043 17.5001 9.58268 17.5001Z" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/>
						<path d="M18.3327 18.3334L16.666 16.6667" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/>
					</svg>
				</label>
			</div>
			<template id="header-search-result-item">
				<li class="header-search__results-item">
					<a class="header-search__results-item-link" href="#">
						<span class="header-search__results-item-text"></span>
					</a>
				</li>
			</template>
		</div>
	</div>

	<div class="header-search-panel header-search-panel--hidden">
		<div class="header-container header-search-panel__inner">
			<ul id="header-search-result" class="header-search__results"></ul>
		</div>
	</div>

	<?php get_template_part('template-parts/layouts/header/mega-menu'); ?>
	<?php get_template_part('template-parts/layouts/header/mega-menu-mobile'); ?>
	<div class="page-overlay page-overlay--hidden"></div>
</header>