<?php
$section_overview = get_field('overview');
$title = $section_overview['title'] ?? '';
$description = $section_overview['description'] ?? '';
$route_in_brief = $section_overview['route_in_brief'];
$journey_highlight = $section_overview['journey_highlight'];
$tour_inclusion = $section_overview['tour_inclusion'];

$map = $section_overview['map'];
$map_type = $map['type'] ?? 'image';
$map_image = $map['image'] ?? '';
$map_google_link = $map['google_map'] ?? '';

$icon_route_id = 1714;
$fallback_image = 1916;
?>
<nav class="cta-nav">
	<div class="cta-nav-container">
		<ul class="cta-nav-list">
			<li data-nav-trigger="tour-overview" class="cta-nav-item active">
				<span class="cta-nav-item__text">Overview</span>
			</li>
			<li data-nav-trigger="tour-itinerary" class="cta-nav-item">
				<span class="cta-nav-item__text">Detailed itinerary</span>
			</li>
			<li data-nav-trigger="tour-hotel" class="cta-nav-item">
				<span class="cta-nav-item__text">Hotel options</span>
			</li>
			<li data-nav-trigger="tour-when-to-visit" class="cta-nav-item">
				<span class="cta-nav-item__text">When to visit</span>
			</li>
			<li data-nav-trigger="tour-client-feedback" class="cta-nav-item">
				<span class="cta-nav-item__text">Client feedback</span>
			</li>
			<li data-nav-trigger="tour-related-tour" class="cta-nav-item">
				<span class="cta-nav-item__text">Related tour</span>
			</li>
		</ul>
	</div>
</nav>
<section class="tour-section-nav">
	<div data-nav-target="tour-overview" class="tour-overview" id="tour-overview">
		<div class="tour-overview__intro">
			<div class="tour-overview__header">
				<h2 class="tour-overview__title">
					<?= $title; ?>
				</h2>
				<p class="tour-overview__desc">
					<?= $description; ?>
				</p>
			</div>

			<?php if(!empty($route_in_brief)): ?>
			<div class="tour-overview__route">
				<div class="tour-overview__route-label">
					<div class="tour-overview__route-label-icon">
						<?php echo wp_get_attachment_image($icon_route_id, 'full', false) ?>
					</div>
					<div class="tour-overview__route-label-text">
						Route in brief
					</div>
				</div>

				<div class="tour-overview__route-divider"></div>

				<div class="tour-overview__route-list">
					<?php foreach ($route_in_brief as $term_id): ?>
					<?php
					$term = get_term($term_id, 'destination');
					if (!$term || is_wp_error($term)) continue;
					$route_title = $term->name;
					$route_slug  = $term->slug;
					$route_link  = get_term_link($term);
					$route_desc = term_description($term_id) ?: '';
					
					// ACF gán cho taxonomy
					$route_thumbnail_id = get_field('thumbnail', 'destination_' . $term_id) ? get_field('thumbnail', 'destination_' . $term_id) : $fallback_image;
					$route_gallery = get_field('gallery', 'destination_' . $term_id);
					$route_gallery_urls = [];

					if ($route_gallery) {
						foreach ($route_gallery as $img) {
							$route_gallery_urls[] = is_array($img)
								? $img['url']
								: wp_get_attachment_url($img);
						}
					}
					?>
					<div class="tour-overview__route-item">
						<div
							data-open-location-drawer-trigger
							data-location-title="<?= esc_attr($route_title) ?>"
							data-location-description="<?= esc_attr($route_desc) ?>"
							data-location-gallery-images="<?= esc_attr( wp_json_encode( $route_gallery_urls ) ) ?>"
							data-location-link="<?= esc_url($route_link) ?>"
							class="tour-overview__route-card js-tour-location-trigger"
							role="button"
							tabindex="0"
							data-location-id="<?= esc_attr($route_slug); ?>"
							>
							<?php if ($route_thumbnail_id): ?>
							<?= wp_get_attachment_image($route_thumbnail_id, 'full'); ?>
							<?php endif; ?>

							<div class="tour-overview__route-name">
								<?= esc_html($route_title); ?>
							</div>
						</div>
					</div>
					<?php endforeach; ?>
				</div>
			</div>
			<?php endif; ?>
		</div>

		<div class="tour-overview__content">
			<?php if(IS_MOBILE || wp_is_mobile()): ?>
			<div class="tour-overview__mobile-tabs">
				<div class="tour-overview__mobile-tabs-inner">
					<button class="tour-overview__mobile-tab is-active tour-overview__mobile-tab--left">JOURNEY HIGHLIGHT</button>
					<button class="tour-overview__mobile-tab tour-overview__mobile-tab--right">TOUR INCLUSION</button>
				</div>
			</div>
			<?php endif; ?>

			<div class="tour-overview__left">
				<h3 class="tour-overview__left-title">
					<?= $journey_highlight['title'] ?? ''; ?>
				</h3>
				<?php if(!empty($journey_highlight['items'])) :?>
				<ul class="tour-overview__left-list">
					<?php foreach($journey_highlight['items'] as $item): ?>
					<li>
						<?= $item['description'] ?? ''; ?>
					</li>
					<?php endforeach; ?>
				</ul>
				<?php endif; ?>
			</div>

			<div class="tour-overview__mid <?= IS_MOBILE || wp_is_mobile() ? 'is-hidden': ''; ?>" >
				<h3 class="tour-overview__mid-title">
					<?= $tour_inclusion['title'] ?? ''; ?>
				</h3>

				<?php if (!empty($tour_inclusion['items'])): ?>
				<div data-lenis-prevent class="tour-overview__mid-scroll">
					<?php
					$items = $tour_inclusion['items'];
					$total = count($items);
					?>
					<?php foreach ($items as $index => $item): ?>
					<div class="tour-overview__mid-item">
						<div class="tour-overview__mid-icon">
							<?php if ($item['icon']): ?>
							<?= wp_get_attachment_image($item['icon'], 'full'); ?>
							<?php endif; ?>
						</div>
						<div class="tour-overview__mid-info">
							<div class="tour-overview__mid-item-title">
								<?= $item['title'] ?? ''; ?>
							</div>
							<div class="tour-overview__mid-item-desc">
								<?= $item['description'] ?? ''; ?>
							</div>
						</div>
					</div>

					<?php if ($index < $total - 1): ?>
					<div class="tour-overview__mid-divider"></div>
					<?php endif; ?>
					<?php endforeach; ?>
				</div>
				<?php endif; ?>
			</div>
			<div class="tour-overview__right">
				<?php if($map_type === 'image'): ?>
				<?= wp_get_attachment_image($map_image, 'full', false, array( 'class' => '')); ?>
				<?php else: ?>
				<?= $map_google_link ?? ''; ?>
				<?php endif; ?>
			</div>
		</div>
	</div>
</section>
