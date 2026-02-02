<?php
$footer = get_field("footer", 'option');
$logo = isset($footer['logo']) ? $footer['logo'] : '';
$partners = isset($footer['partner']) ? $footer['partner'] : [];
$navs = isset($footer['navigation']) ? $footer['navigation'] : [];
$socials = isset($footer['social_media']) ? $footer['social_media'] : [];
$contact_information = isset($footer['contact_information']) ? $footer['contact_information'] : '';
$licence = isset($footer['licence']) ? $footer['licence'] : '';

$deco_id = 1297;

$footer_title = $footer['footer_title'];
$footer_desc = $footer['footer_desc'];
?>

<footer class="footer">
	<?= wp_get_attachment_image($deco_id, 'full', false, array('class' => 'footer_deco')) ?>

	<div class="footer_container">
		<div class="footer_partners">
			<?php foreach ($partners as $partner): ?>
			<div class="footer_partner">
				<?= wp_get_attachment_image($partner, 'full', false) ?>
			</div>
			<?php endforeach; ?>
		</div>

		<div class="footer_bar"></div>

		<div class="footer_menu">
			<div class="footer_groups">
				<?php foreach($navs as $nav): ?>
				<?php $items = isset($nav['items']) ? $nav['items'] : []; ?>
				<div class="footer_group">
					<div class="footer_group-btn">
						<span class="footer_group-label"><?= $nav['title']; ?></span>
						<svg
							 xmlns="http://www.w3.org/2000/svg"
							 width="13"
							 height="8"
							 viewBox="0 0 13 8"
							 fill="none"
							 class="footer_group-icon"
							 >
							<path
								  d="M0.441406 0.44043L6.32602 6.32505L12.2106 0.44043"
								  stroke="#2E2E2E"
								  stroke-width="1.24615"
								  />
						</svg>
					</div>
					<ul class="footer_items">
						<?php foreach($items as $item): ?>
						<li class="footer_item">
							<a href="<?= $item['link']['url']; ?>" class="footer_item-link"><?= $item['link']['title']; ?></a>
						</li>
						<?php endforeach; ?>
					</ul>
				</div>
				<?php endforeach; ?>
			</div>

			<div class="footer_menu-info">
				<?= wp_get_attachment_image($logo, 'full', false, array('class' => 'footer_logo')) ?>
				<p class="footer_info-label">
					<?= $footer_title; ?>
				</p>
				<div class="footer_info-desc">
					<?= $footer_desc; ?>
				</div>
			</div>
		</div>

		<div class="footer_contact">
			<h3 class="footer_contact-title">Newsletter sign up</h3>
			<?php echo do_shortcode('[contact-form-7 id="07e1f34" title="Form Newsletter sign up"]'); ?>
		</div>

		<div class="footer_bar mb-hidden"></div>

		<div class="footer_socials">
			<?php foreach($socials as $social): ?>
			<?php 
			$icon = $social['icon'];
			$link = $social['link'];
			?>
			<a href="<?= esc_url($link['url']); ?>" target="<?= $link['target']; ?>" class="footer_social-link">
				<?= wp_get_attachment_image($icon, 'full', false, array('class' => 'footer_social-icon')) ?>
			</a>
			<?php endforeach; ?>
		</div>

		<div class="footer_bar"></div>

		<div class="footer_bottom">
			<p class="footer_content">
				<?= $contact_information ?>
			</p>
			<p class="footer_content">
				<?= $licence ?>
			</p>
		</div>
	</div>
</footer>
