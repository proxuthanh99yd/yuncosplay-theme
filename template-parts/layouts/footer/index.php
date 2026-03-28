<?php 

$footer = get_field('footer', 'option');

// Footer information
$footer_info = $footer['footer_info'];
$footer_logo = $footer_info['logo_image'];
$footer_socials = $footer_info['socials'];

// Footer contact
$footer_contact = $footer['footer_contact'];
$footer_contact_title = $footer_contact['title'];
$footer_contact_items = $footer_contact['contact_items'];

// Footer navigation
$footer_nav = $footer['footer_navigation'];
$footer_nav_title = $footer_nav['title'];
$footer_nav_items = $footer_nav['navigation_items'];

// Footer address
$footer_address = $footer['footer_address'];
$footer_address_link = $footer_address['address_link'];
$footer_address_map_type = $footer_address['map_type'];
if($footer_address_map_type == 'map_image') {
	$footer_address_map_image = $footer_address['map_image'];
} else {
	$footer_address_map_frame = $footer_address['google_map_frame'];
}

// Footer copyright
$footer_copyright = $footer['footer_copyright'];
?>

<footer class="footer">
	<div class="footer-top">
		<div class="footer-info">
			<a href="/" class="footer-logo">
				<?php echo wp_get_attachment_image($footer_logo, 'full', false, array( 'class' => '')) ?>
			</a>
			<?php if(!empty($footer_socials)): ?>
			<ul class="footer-social-list">
				<?php foreach($footer_socials as $social): ?>
				<li class="footer-social-item">
					<?php 
					$social_icon = $social['social_icon'];
					$social_link = $social['social_link'];
					$social_link_url = $social_link['url'];
					$social_link_target = $social_link['target'] ? $social_link['target'] : '_self';
					if(!empty($social_link_url) && !empty($social_icon)):
					?>
					<a href="<?php echo $social_link_url ?>" target="<?php echo $social_link_target ?>" class="footer-social-link">
						<?php echo wp_get_attachment_image($social_icon, 'full', false, array( 'class' => '')) ?>
					</a>
					<?php endif; ?>
				</li>
				<?php endforeach; ?>
			</ul>
			<?php endif; ?>
		</div>
		<div class="footer-divider"></div>
		<div class="footer-contact">
			<p data-accordion-trigger class="footer-contact-title">
				<?= $footer_contact_title ?? '' ?>
			</p>
			<?php if(!empty($footer_contact_items)): ?>
			<ul data-accordion-content aria-expanded="true" class="footer-contact-list">
				<?php foreach($footer_contact_items as $item): ?>
				<?php 
					$contact_type = $item['contact_type'];
					$contact_icon = $item['contact_icon'];
					if($contact_type === 'contact_link') {
						$contact_link = $item['contact_link'];
						$contact_link_url = $contact_link['url'];
						$contact_link_target = $contact_link['target'] ? $contact_link['target'] : '_self';
						$contact_link_title = $contact_link['title'] ?? '';
					} else {
						$contact_text = $item['contact_text'];
					}
				?>
				
				<li class="footer-contact-item">
					<?php if($contact_type === 'contact_link'): ?>
					<a href="<?= $contact_link_url ?>" target="<?= $contact_link_target ?>" class="footer-contact-link">
						<span class="footer-contact-link__icon"><?= wp_get_attachment_image($contact_icon, 'full', false, array( 'class' => '')) ?></span>
						<span class="footer-contact-link__text"><?= $contact_link_title; ?></span>
					</a>
					<?php else: ?>
					<p class="footer-contact-link">
						<span class="footer-contact-link__icon"><?= wp_get_attachment_image($contact_icon, 'full', false, array( 'class' => '')) ?></span>
						<span class="footer-contact-link__text"><?= $contact_text; ?></span>
					</p>
					<?php endif; ?>
				</li>
				<?php endforeach; ?>
			</ul>
			<?php endif; ?>
		</div>
		<div class="footer-divider"></div>
		<div class="footer-nav">
			<p data-accordion-trigger class="footer-nav-title">
				<?= $footer_nav_title ?? '' ?>
			</p>
			<?php if(!empty($footer_nav_items)): ?>
			<ul data-accordion-content aria-expanded="true" class="footer-nav-list">
				<?php foreach($footer_nav_items as $nav_item): ?>
				<?php 
					$nav_item_link = $nav_item['navigation_link'];
					$nav_item_link_url = $nav_item_link['url'];
					$nav_item_link_target = $nav_item_link['target'] ? $nav_item_link['target'] : '_self';
					$nav_item_link_text = $nav_item_link['title'];
				?>
				<li class="footer-nav-item">
					<a href="<?= $nav_item_link_url ?>" target="<?= $nav_item_link_target ?>" class="footer-nav-link">
						<?= $nav_item_link_text ?>
					</a>
				</li>
				<?php endforeach; ?>
			</ul>
			<?php endif; ?>
		</div>
		<div class="footer-divider"></div>
		<div class="footer-address">
			<div class="footer-address-wrapper">
				<?php if(!empty($footer_address_link['url'])): ?>
				<a href="<?= $footer_address_link['url'] ?>" target="<?= $footer_address_link['target'] ? $footer_address_link['target'] : '_self' ?>" class="footer-address-link">
					<p class="footer-address-link__text">
						<?= $footer_address_link['title'] ?? '' ?>
					</p>
					<?php get_template_part('template-parts/components/icon-location/index'); ?>
				</a>
				<?php endif; ?>
				<div class="footer-address-image">
					<?php if($footer_address_map_type == 'map_image'): ?>
						<?= wp_get_attachment_image($footer_address_map_image, 'full', false, array( 'class' => '')) ?>
					<?php else: ?>
						<?= $footer_address_map_frame ?>
					<?php endif; ?>
				</div>				
			</div>
		</div>
	</div>
	<div class="footer-bottom">
		<div class="footer-bottom-wrapper">
			<p class="footer-copyright"><?= $footer_copyright ?></p>
		</div>
	</div>
</footer>