<?php
// Icon ids
$icon_phone_id = 64;
$icon_email_id = 65;
$icon_arrow_down_id = 68;
$icon_search_id = 66;
$icon_arrow_right_id = 69;


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

?>

<?php
$header_classes = 'header header--desktop';
if (!is_front_page()) {
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
							<?php echo wp_get_attachment_image($icon_phone_id, 'full', false, array('class' => '')) ?>
						</span>
						<span class="header__phone-text"><?= $contact_phone['title'] ?? '' ?></span>
					</a>
				<?php endif; ?>

				<?php if (!empty($contact_email) && !empty($contact_email['url'])) : ?>
					<a href="<?= $contact_email['url'] ?>" target="<?= $contact_email['target'] ?? '_self'; ?>"
						class="header__email">
						<span class="header__email-icon">
							<?php echo wp_get_attachment_image($icon_email_id, 'full', false, array('class' => '')) ?>
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
						<?php echo wp_get_attachment_image($header_logo, 'full', false, okhub_image_attrs(array('class' => ''), 'eager')); ?>
					<?php endif; ?>
				</a>
				<nav class="header__nav">
					<ul class="header__nav-list">
						<?php
						if (! empty($header_menu) && is_array($header_menu)) :
							foreach ($header_menu as $item) :
								$type   = $item['menu_item_type'] ?? '';
								$label  = $item['menu_item_label'] ?? '';
								$link   = $item['menu_item_link'] ?? [];
								$mega   = $item['menu_item_mega'] ?? '';
								$url    = ! empty($link['url']) ? esc_url($link['url']) : '#';
								$target = ! empty($link['target']) ? esc_attr($link['target']) : '_self';
								$label  = $label !== '' ? $label : ($link['title'] ?? '');

								if ($type === 'mega_menu') :
									$mega_slug = ($mega === 'mega_service') ? 'mega-menu-service' : 'mega-menu-product';
									$mega_part = ($mega === 'mega_service') ? 'mega-menu-service' : 'mega-menu-product';
						?>
									<li data-mega-menu-trigger="<?= esc_attr($mega_slug); ?>" class="header__nav-item">
										<a href="<?= $url; ?>" target="<?= $target; ?>" class="header__nav-link">
											<span class="header__nav-link-text"><?= esc_html($label); ?></span>
											<span class="header__nav-link-icon">
												<?php echo wp_get_attachment_image($icon_arrow_down_id, 'full', false, array('class' => '')); ?>
											</span>
										</a>
										<?php get_template_part('template-parts/layouts/header/header-desktop/' . $mega_part); ?>
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
					<?php echo wp_get_attachment_image($icon_search_id, 'full', false, array('class' => '')) ?>
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