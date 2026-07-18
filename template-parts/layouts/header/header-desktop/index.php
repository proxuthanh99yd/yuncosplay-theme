<?php
// Icon ids
// Icon header → file tĩnh theme (okhub_img). Logo/thumbnail vẫn lấy từ CMS.


// Field Header (fallback to array to avoid PHP warnings when ACF option is empty)
$header = function_exists('get_field') ? get_field('header', 'option') : [];
if (! is_array($header)) {
	$header = [];
}
$header_contact = isset($header['contact']) && is_array($header['contact']) ? $header['contact'] : [];
$header_logo    = $header['logo_image'] ?? null;
$contact_phone = $header_contact['contact_phone'] ?? null;
$contact_email = $header_contact['contact_email'] ?? null;
$contact_socials = $header_contact['contact_socials'] ?? [];
$contact_address = $header_contact['contact_address'] ?? null;
$contact_now   = $header_contact['contact_now'] ?? null;
$header_menu   = isset($header['menu']) && is_array($header['menu']) ? $header['menu'] : [];

function is_transparent_header_page_helper() {
    return (
        is_front_page()
        || is_page_template('about-us-page.php')
        || is_page_template('faqs.php')
        || is_singular('service')
        || is_post_type_archive('service')
    );
}
if (! function_exists('okhub_header_get_first_related_post_id')) {
	function okhub_header_get_first_related_post_id($value) {
		if ($value instanceof WP_Post) {
			return (int) $value->ID;
		}

		if (is_numeric($value)) {
			return (int) $value;
		}

		if (is_array($value)) {
			$first_value = reset($value);
			return okhub_header_get_first_related_post_id($first_value);
		}

		return 0;
	}
}

if (! function_exists('okhub_header_get_image_id')) {
	function okhub_header_get_image_id($value) {
		if (is_numeric($value)) {
			return (int) $value;
		}

		if (is_array($value)) {
			return (int) ($value['ID'] ?? $value['id'] ?? 0);
		}

		return 0;
	}
}

if (! function_exists('okhub_header_get_all_service_mega_links')) {
	// Lấy hết service từ post type 'service' thay vì lấy danh sách trong ACF (menu_link_mega)
	function okhub_header_get_all_service_mega_links() {
		$links = [];
		$query = new WP_Query([
			'post_type'      => 'service',
			'post_status'    => 'publish',
			'posts_per_page' => -1,
			'orderby'        => 'menu_order',
			'order'          => 'ASC',
		]);

		foreach ($query->posts as $service_post) {
			$links[] = [
				'service' => $service_post->ID,
				'image'   => get_post_thumbnail_id($service_post->ID),
				'link'    => [
					'url'    => get_permalink($service_post->ID),
					'title'  => get_the_title($service_post->ID),
					'target' => '_self',
				],
			];
		}

		wp_reset_postdata();

		return $links;
	}
}

?>

<?php
$header_classes = 'header header--desktop';
$is_transparent_header_page = is_transparent_header_page_helper();
if (!$is_transparent_header_page) {
	$header_classes .= ' header--white';
}
?>
<header class="<?= esc_attr($header_classes); ?>">
    <div class="header__topbar">
        <div class="header__topbar-wrapper">
            <div class="header__topbar-left">
                <?php if (!empty($contact_phone) && !empty($contact_phone['url'])) : ?>
                <a href="<?= $contact_phone['url'] ?>" target="<?= $contact_phone['target'] ?? '_self'; ?>"
                    class="header__phone">
                    <span class="header__phone-icon">
                        <?php echo okhub_img('icons/call') ?>
                    </span>
                    <span class="header__phone-text"><?= $contact_phone['title'] ?? '' ?></span>
                </a>
                <?php endif; ?>

                <?php if (!empty($contact_email) && !empty($contact_email['url'])) : ?>
                <a href="<?= $contact_email['url'] ?>" target="<?= $contact_email['target'] ?? '_self'; ?>"
                    class="header__email">
                    <span class="header__email-icon">
                        <?php echo okhub_img('icons/sms') ?>
                    </span>
                    <span class="header__email-text"><?= $contact_email['title'] ?? '' ?></span>
                </a>
                <?php endif; ?>
            </div>
            <div class="header__topbar-right">
                <?php if (!empty($contact_socials)): ?>
                <ul class="header__social-list">
                    <?php foreach ($contact_socials as $social): ?>
                    <?php
							$social_link = $social['social_link'];
							$social_link_url = $social_link['url'];
							$social_link_target = $social_link['target'] ? $social_link['target'] : '_self';
							$social_icon = $social['social_icon'];
							if (!empty($social_link_url) && !empty($social_icon)):
							?>
                    <li class="header__social-item">
                        <a href="<?= $social_link_url ?>" target="<?= $social_link_target ?>"
                            class="header__social-link">
                            <?php echo wp_get_attachment_image($social_icon, 'full', false, array('class' => '')) ?>
                        </a>
                    </li>
                    <?php endif; ?>
                    <?php endforeach; ?>
                </ul>
                <?php endif; ?>

                <?php if (!empty($contact_address) && !empty($contact_address['url'])) : ?>
                <a href="<?= $contact_address['url'] ?>" target="<?= $contact_address['target'] ?? '_self'; ?>"
                    class="header__address">
                    <div class="header__address-content">
                        <div class="header__address-content__text">
                            <?= $contact_address['title'] ?? '' ?>
                        </div>
                        <span class="header__address-content__icon">
                            <?php get_template_part('template-parts/components/icon-location/index'); ?>
                        </span>
                    </div>
                </a>
                <?php endif; ?>
            </div>
        </div>
    </div>
    <div class="header__navbar">
        <div class="header__navbar-wrapper">
            <div class="header__navbar-left">
                <a href="/" class="header__logo">
                    <?php if (! empty($header_logo)) : ?>
                    <?php echo wp_get_attachment_image($header_logo, 'medium', false, okhub_image_attrs(array('class' => '', 'sizes' => '52px'), 'eager')); ?>
                    <?php endif; ?>
                </a>
                <nav class="header__nav">
                    <ul class="header__nav-list">
                        <?php
						if (! empty($header_menu) && is_array($header_menu)) :
							foreach ($header_menu as $index => $item) :
								$type   = $item['menu_item_type'] ?? '';
								$label  = $item['menu_item_label'] ?? '';
								$link   = $item['menu_item_link'] ?? [];
								$mega   = $item['menu_item_mega'] ?? '';
								$mega_links = isset($item['menu_link_mega']) && is_array($item['menu_link_mega']) ? array_values(array_filter($item['menu_link_mega'], function ($mega_link_item) {
									return ! empty($mega_link_item['link']['url']);
								})) : [];
								// Service mega menu: lấy hết service từ post type thay vì lấy trong ACF
								if ($mega === 'mega_service') {
									$mega_links = okhub_header_get_all_service_mega_links();
								}
								$has_template_mega = $type === 'mega_menu' && in_array($mega, ['mega_service', 'mega_product'], true);
								$has_link_mega = ! empty($mega_links) && ($type !== 'mega_menu' || ! $has_template_mega);
								$url    = ! empty($link['url']) ? esc_url($link['url']) : '#';
								$target = ! empty($link['target']) ? esc_attr($link['target']) : '_self';
								$label  = $label !== '' ? $label : ($link['title'] ?? '');

								if ($has_link_mega || $has_template_mega) :
									$mega_slug = $has_link_mega
										? 'mega-menu-links-' . $index
										: (($mega === 'mega_service') ? 'mega-menu-service' : 'mega-menu-product');
									$mega_part = ($mega === 'mega_service') ? 'mega-menu-service' : 'mega-menu-product';
						?>
                        <li data-mega-menu-trigger="<?= esc_attr($mega_slug); ?>" class="header__nav-item">
                            <a href="<?= $url; ?>" target="<?= $target; ?>" class="header__nav-link">
                                <span class="header__nav-link-text"><?= esc_html($label); ?></span>
                                <span class="header__nav-link-icon">
                                    <?php echo okhub_img('icons/arrow-down'); ?>
                                </span>
                            </a>
                            <?php if ($has_link_mega) : ?>
                            <div data-mega-menu-content="<?= esc_attr($mega_slug); ?>"
                                class="header__mega-menu-service header__mega-menu-item">
                                <div class="header__mega-menu-service-wrapper">
                                    <div class="header__mega-menu-service-left">
                                        <ul data-lenis-prevent class="header__mega-menu-service__service-list">
                                            <?php foreach ($mega_links as $mega_link_index => $mega_link_item) : ?>
                                            <?php
												$dropdown_link = $mega_link_item['link'] ?? [];
												$dropdown_url = ! empty($dropdown_link['url']) ? esc_url($dropdown_link['url']) : '';
												$dropdown_target = ! empty($dropdown_link['target']) ? esc_attr($dropdown_link['target']) : '_self';
												$dropdown_label = $dropdown_link['title'] ?? '';
												$service_id = okhub_header_get_first_related_post_id($mega_link_item['service'] ?? null);
												$service_icon = $service_id && function_exists('get_field') ? okhub_header_get_image_id(get_field('icon', $service_id) ?: get_field('service_icon', $service_id) ?: get_field('thumbnail_icon', $service_id)) : 0;
												if ($dropdown_url === '' || $dropdown_label === '') {
													continue;
												}
											?>
                                            <li data-service-trigger-index="<?= esc_attr($mega_link_index); ?>"
                                                class="header__mega-menu-service__service-item <?= $mega_link_index === 0 ? 'header__mega-menu-service__service-item--active' : ''; ?>">
                                                <a href="<?= $dropdown_url; ?>" target="<?= $dropdown_target; ?>"
                                                    class="header__mega-menu-service__service-link">
                                                    <span class="header__mega-menu-service__service-link-label">
                                                        <?php if (! empty($service_icon)) : ?>
                                                        <span class="header__mega-menu-service__service-link-thumb">
                                                            <?= wp_get_attachment_image($service_icon, 'full', false, ['class' => '']); ?>
                                                        </span>
                                                        <?php endif; ?>
                                                        <span
                                                            class="header__mega-menu-service__service-link-text"><?= esc_html($dropdown_label); ?></span>
                                                    </span>
                                                    <span class="header__mega-menu-service__service-link-icon">
                                                        <?php echo okhub_img('icons/arrow'); ?>
                                                    </span>
                                                </a>
                                            </li>
                                            <?php endforeach; ?>
                                        </ul>
                                    </div>
                                    <div class="header__mega-menu-service-right">
                                        <?php foreach ($mega_links as $mega_link_index => $mega_link_item) : ?>
                                        <?php
															$dropdown_link = $mega_link_item['link'] ?? [];
															$dropdown_url = ! empty($dropdown_link['url']) ? esc_url($dropdown_link['url']) : '';
															$dropdown_label = $dropdown_link['title'] ?? '';
															$service_id = okhub_header_get_first_related_post_id($mega_link_item['service'] ?? null);
															$service_offer = $service_id && function_exists('get_field') ? (get_field('service_offer', $service_id) ?: []) : [];
															$service_link = $service_id ? get_permalink($service_id) : $dropdown_url;
															$service_title = $service_id ? get_the_title($service_id) : $dropdown_label;
															$service_image = okhub_header_get_image_id($mega_link_item['image'] ?? null);
															$service_image = $service_image ?: ($service_id ? get_post_thumbnail_id($service_id) : 0);
															$service_offer_title = $service_offer['title'] ?? $service_title;
															$service_offer_items = isset($service_offer['offer_items']) && is_array($service_offer['offer_items']) ? $service_offer['offer_items'] : [];

															if ($dropdown_url === '' || $dropdown_label === '') {
																continue;
															}
															?>
                                        <article data-service-target-index="<?= esc_attr($mega_link_index); ?>"
                                            class="header__mega-menu-service-item <?= $mega_link_index === 0 ? 'header__mega-menu-service-item--active' : ''; ?>">
                                            <div class="header__mega-menu-service__banner">
                                                <div class="header__mega-menu-service__banner-overlay"></div>
                                                <div class="header__mega-menu-service__banner-background">
                                                    <?php if (! empty($service_image)) : ?>
                                                    <?= wp_get_attachment_image($service_image, 'full', false); ?>
                                                    <?php else : ?>
                                                    <?= okhub_img('common/thumb-fallback'); ?>
                                                    <?php endif; ?>
                                                </div>
                                                <div class="header__mega-menu-service__banner-content">
                                                    <div class="header__mega-menu-service__banner-content-left">
                                                        <h3 class="header__mega-menu-service__banner-title">
                                                            <?= wp_kses_post($service_offer_title); ?>
                                                        </h3>
                                                        <?php if (! empty($service_offer_items)) : ?>
                                                        <ul class="header__mega-menu-service__banner-service-list">
                                                            <?php foreach ($service_offer_items as $service_offer_item) : ?>
                                                            <?php $offer_text = $service_offer_item['offer_item'] ?? ''; ?>
                                                            <?php if ($offer_text === '') continue; ?>
                                                            <li class="header__mega-menu-service__banner-service-item">
                                                                <div
                                                                    class="header__mega-menu-service__banner-service-content">
                                                                    <span
                                                                        class="header__mega-menu-service__banner-service-item-icon">
                                                                        <?php echo okhub_img('icons/icon'); ?>
                                                                    </span>
                                                                    <span
                                                                        class="header__mega-menu-service__banner-service-item-text">
                                                                        <?= $offer_text; ?>
                                                                    </span>
                                                                </div>
                                                            </li>
                                                            <?php endforeach; ?>
                                                        </ul>
                                                        <?php endif; ?>
                                                    </div>
                                                    <div class="header__mega-menu-service-banner__content-right">
                                                        <a href="<?= $dropdown_url; ?>"
                                                            class="header__mega-menu-service-banner__btn-details">
                                                            <span
                                                                class="header__mega-menu-service-banner__btn-details-icon">
                                                                <?php echo okhub_img('icons/arrow-right-2'); ?>
                                                            </span>
                                                            <span
                                                                class="header__mega-menu-service-banner__btn-details-text">Xem
                                                                chi tiết</span>
                                                        </a>
                                                    </div>
                                                </div>
                                            </div>
                                        </article>
                                        <?php endforeach; ?>
                                    </div>
                                </div>
                            </div>
                            <?php else : ?>
                            <?php get_template_part('template-parts/layouts/header/header-desktop/' . $mega_part); ?>
                            <?php endif; ?>
                        </li>
                        <?php else : ?>
                        <li class="header__nav-item">
                            <a href="<?= $url; ?>" target="<?= $target; ?>" class="header__nav-link">
                                <span class="header__nav-link-text"><?= esc_html($label); ?></span>
                            </a>
                        </li>
                        <?php endif; ?>
                        <?php endforeach; ?>
                        <?php endif; ?>

                    </ul>
                </nav>
            </div>
            <div class="header__navbar-right">
                <div class="header__search-input-wrapper">
                    <input class="header__search-input" type="text" placeholder="Nhập từ khoá tìm kiếm" readonly />
                    <?php echo okhub_img('icons/search-normal') ?>
                </div>

                <?php if (!empty($contact_now) && !empty($contact_now['url'])) : ?>
                <a href="<?= $contact_now['url'] ?>" target="<?= $contact_now['target'] ?? '_self'; ?>"
                    class="header__contact-btn">
                    <?php get_template_part('template-parts/components/animated-button/index', null, ['text' => $contact_now['title'] ?? '']); ?>
                </a>
                <?php endif; ?>
            </div>
        </div>
    </div>
    <?php get_template_part('template-parts/layouts/header/header-desktop/mega-menu-search-result'); ?>
</header>
<div class="header__mega-menu-overlay"></div>