<?php
// Icon ids
$icon_flight = 1744;
$icon_arrival_airport = 1745;
$icon_meals = 1746;
$icon_stay_at = 1747;
$icon_phone = 1836;
$icon_whatsapp = 1837;

// ACF Detailed itinerary
$section_detailed_itinerary = get_field('detailed_itinerary');
$detailed_itinerary_title = $section_detailed_itinerary['title'] ?? '';
$detailed_itinerary_desc = $section_detailed_itinerary['description'] ?? '';
$detailed_itinerary_items = $section_detailed_itinerary['items'];

// ACF Accommodation options
$section_accommodation_options = get_field('accommodation_options');
$accommodation_options_title = $section_accommodation_options['title'];
$accommodation_options_desc = $section_accommodation_options['description'];
$accommodation_options_locations = $section_accommodation_options['items'];

// ACF Contact us
$section_contact_us = get_field('contact_us');
$contact_us_title = $section_contact_us['title'] ?? '';
$contact_us_description = $section_contact_us['description'] ?? '';
$contact_us_link = $section_contact_us['link'];

if(!empty($contact_specialist_whatsapp)) {
	$contact_us_link_url = $contact_us_link['url'] ?? '';
	$contact_us_link_title = $contact_us_link['title'] ?? '';
	$contact_us_link_target = $contact_us_link['target'] ?? '_self';
}

// ACF Contact specialist
$section_contact_specialist = get_field('contact_specialist');
$contact_specialist_title = $section_contact_specialist['title'] ?? '';
$contact_specialist_description = $section_contact_specialist['description'] ?? '';
$contact_specialist_whatsapp = $section_contact_specialist['whatsapp'];
$contact_specialist_images = $section_contact_specialist['images'];

if(!empty($contact_specialist_whatsapp)) {
	$contact_specialist_whatsapp_url = $contact_specialist_whatsapp['url'] ?? '';
	$contact_specialist_whatsapp_title = $contact_specialist_whatsapp['title'] ?? '';
	$contact_specialist_whatsapp_target = $contact_specialist_whatsapp['target'] ?? '_self';
}

?>

<section  class="itinerary-section" id="tour-itinerary">
	<div class="itinerary-layout">
		<div data-lenis-prevent class="itinerary-left">
			<div class="itinerary-left-scroll">
				<div data-nav-target="tour-itinerary" class="itinerary-container">
					<div  class="itinerary-head">
						<h2 class="itinerary-title">
							<?= $detailed_itinerary_title; ?>
						</h2>

						<div class="itinerary-actions">
							<button class="itinerary-action" type="button" data-action="close-all">CLOSE ALL</button>
							<span class="itinerary-divider"></span>
							<button class="itinerary-action" type="button" data-action="expand-all">EXPAND ALL</button>
						</div>
					</div>

					<p class="itinerary-desc">
						<?= $detailed_itinerary_desc; ?>
					</p>


					<div class="itinerary-accordion" id="itineraryAccordion">
						<?php if( !empty($detailed_itinerary_items) ): ?>
						<?php foreach($detailed_itinerary_items as $index => $item): ?>
						<?php 
						$is_first_item = $index === 0;
						$day_number      = $index + 1;

						$name            = $item['name'] ?? '';
						$description     = $item['description'] ?? '';
						$flight_in_use   = $item['flight_in_use'] ?? '';
						$arrival_airport = $item['arrival_airport'] ?? '';
						$meals           = $item['meals'] ?? '';

						/** stay_at = post object */
						$hotel_id    = !empty($item['stay_at']) ? (int) $item['stay_at'] : 0;
						$hotel_title = $hotel_id ? get_the_title($hotel_id) : '';
						$hotel_slug  = $hotel_id ? get_post_field('post_name', $hotel_id) : '';

						/** gallery từ information.images */
						$hotel_information = $hotel_id ? get_field('information', $hotel_id) : [];
						$hotel_gallery_ids = $hotel_information['images'] ?? [];

						?>
						<article class="itinerary-item <?= $is_first_item ? 'itinerary-item--open' :'' ?>">
							<button class="itinerary-toggle" type="button">
								<span class="itinerary-day">
									<span class="itinerary-day__badge">
										<span class="itinerary-day__badge-small">DAY</span>
										<strong><?= $day_number; ?></strong>
									</span>
									<span class="itinerary-day__name"><?= esc_html($name) ?></span>
								</span>

								<span class="itinerary-chevron" aria-hidden="true">
									<svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none">
									<path d="M19 8L11.3217 15.5002L4 8" stroke="#630F3F" stroke-width="2"/>
									</svg>
								</span>
							</button>

							<div style="<?= $is_first_item ? 'height: auto; opacity: 1;' : 'height: 0px; opacity: 0;' ?>" class="itinerary-panel itinerary-content">
								<?php if ($description): ?>
								<div class="itinerary-block"><?= $description ?></div>
								<?php endif; ?>

								<ul class="itinerary-info">
									<?php if ($flight_in_use): ?>
									<li class="itinerary-info__row">
										<span class="itinerary-info__icon">
											<?= wp_get_attachment_image($icon_flight, 'full', false,['loading'=>'lazy','decoding'=>'async']) ?>
										</span>
										<span class="itinerary-info__label">Flight in use:</span>
										<span class="itinerary-info__value"><?= esc_html($flight_in_use) ?></span>
									</li>
									<?php endif; ?>

									<?php if ($arrival_airport): ?>
									<li class="itinerary-info__row">
										<span class="itinerary-info__icon">
											<?= wp_get_attachment_image($icon_arrival_airport, 'full', false, ['loading'=>'lazy','decoding'=>'async']) ?>
										</span>
										<span class="itinerary-info__label">Arrival airport:</span>
										<span class="itinerary-info__value"><?= esc_html($arrival_airport) ?></span>
									</li>
									<?php endif; ?>

									<?php if ($meals): ?>
									<li class="itinerary-info__row">
										<span class="itinerary-info__icon">
											<?= wp_get_attachment_image($icon_meals, 'full', false,['loading'=>'lazy','decoding'=>'async']) ?>
										</span>
										<span class="itinerary-info__label">Meals:</span>
										<span class="itinerary-info__value"><?= esc_html($meals) ?></span>
									</li>
									<?php endif; ?>

									<?php if ($hotel_id): ?>
									<li
										class="itinerary-info__row"
										data-hotel-id="<?= esc_attr($hotel_slug) ?>"
										tabindex="0"
										>
										<span class="itinerary-info__icon">
											<?= wp_get_attachment_image($icon_stay_at, 'full', false, ['loading'=>'lazy','decoding'=>'async']) ?>
										</span>

										<span class="itinerary-info__label">Stay at:</span>
										<?php 
										$hotel_title = $hotel_id ? get_the_title($hotel_id) : '';
										$hotel_link = $hotel_id ? get_permalink($hotel_id) : '';
										$hotel_desc = $hotel_id ? get_the_excerpt($hotel_id) : '';
										$hotel_information = $hotel_id ? get_field('information', $hotel_id) : [];
										$hotel_gallery_images = $hotel_information['images'] ?? [];
										if(!empty($hotel_gallery_images)) {
											$hotel_gallery_urls = [];
											foreach ($hotel_gallery_images as $img_id) {
												$img_url = wp_get_attachment_image_url($img_id, 'full');
												if ($img_url) {
													$hotel_gallery_urls[] = $img_url;
												}
											}
										} else {
											$hotel_gallery_urls = [];
										}
										$hotel_review_rating = $hotel_information['review_rating'] ?? 0;
										$hotel_google_map_link = $hotel_information['google_map_link'] ?? '';
										if(!empty($hotel_google_map_link)) {
											$hotel_google_map_link_title = $hotel_google_map_link['title'] ?? '';
											$hotel_google_map_link_url = $hotel_google_map_link['url'] ?? '';
										}
										?>
										<button 
											type="button" 
											data-open-hotel-drawer-trigger  
											data-hotel-title="<?= esc_attr($hotel_title) ?>"
											data-hotel-address-title="<?= esc_attr($hotel_google_map_link_title) ?>"
											data-hotel-address-link="<?= esc_url($hotel_google_map_link_url) ?>"
											data-hotel-rating="<?= esc_attr($hotel_review_rating) ?>"
											data-hotel-description="<?= esc_attr($hotel_desc) ?>"
											data-hotel-gallery-images="<?= esc_attr( wp_json_encode( $hotel_gallery_urls ) ) ?>"
											data-hotel-link="<?= esc_url($hotel_link) ?>"
											class="itinerary-stay js-hotel-popup-trigger">

											<?= esc_html($hotel_title) ?>
										</button>
									</li>
									<?php endif; ?>
								</ul>

								<?php if (!empty($hotel_gallery_ids)): ?>
								<div class="itinerary-gallery" data-itinerary-gallery>
									<div class="itinerary-nav-wrapper">
										<button disabled class="itinerary-nav-btn itinerary-nav-btn--prev">
											<svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 20 20" fill="none">
												<path d="M13.334 15.8335L7.08378 9.43493L13.334 3.3335" stroke="currentColor" stroke-width="2"/>
											</svg>
										</button>
										<button class="itinerary-nav-btn itinerary-nav-btn--next">
											<svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 20 20" fill="none">
												<path d="M6.66797 15.8335L12.9182 9.43493L6.66797 3.3335" stroke="currentColor" stroke-width="2"/>
											</svg>
										</button>
									</div>
									<div class="itinerary-gallery__grid hotel-gallery-swiper swiper hotel-gallery-swiper-<?=$index; ?>">
										<div class="swiper-wrapper hotel-gallery-swiper-wrapper">
											<?php foreach ($hotel_gallery_ids as $item): ?>
												<div class="swiper-slide hotel-gallery-swiper-slide">
													<div class="itinerary-gallery__item">
														<?= wp_get_attachment_image($item,'full',false,['class'    => 'itinerary-gallery__img','loading'  => 'lazy','decoding' => 'async']); ?>
													</div>
												</div>
											<?php endforeach; ?>
										</div>
									</div>
								</div>
								<?php endif; ?>
							</div>
						</article>

						<?php endforeach; ?>
						<?php endif; ?>
					</div>
				</div>

				<div data-nav-target="tour-hotel" class="acc-wrap">
					<div class="acc-head">
						<h2 class="acc-title">
							Accommodation options						
						</h2>

						<div class="acc-actions">
							<button class="acc-action" type="button" data-action="close-all">CLOSE ALL</button>
							<span class="acc-divider"></span>
							<button class="acc-action" type="button" data-action="expand-all">EXPAND ALL</button>
						</div>
					</div>

					<p class="acc-desc">
						<?= $accommodation_options_desc; ?>
					</p>
					<?php if(!empty($accommodation_options_locations)): ?>
					<div class="acc-accordion" id="accAccordion">
						<?php foreach($accommodation_options_locations as $index => $term_destination_id): ?>
						<?php
						$is_first_item = $index === 0;
						$term_destination = get_term($term_destination_id, 'destination');
						if (!$term_destination || is_wp_error($term_destination)) continue;

    					$term_destination_name  = $term_destination->name;
						// Query hotel theo destination
						$hotel_query = new WP_Query([
							'post_type'      => 'hotel',
							'posts_per_page' => -1,
							'tax_query'      => [
								[
									'taxonomy' => 'destination',
									'field'    => 'term_id',
									'terms'    => $term_destination_id,
								],
							],
						]);
						?>
						<?php if(!$is_first_item): ?>
						<div class="acc-item-divider"></div>
						<?php endif; ?>
						<article class="acc-item <?= $is_first_item ? 'acc-item--open' :'' ?>">
							<h3 class="acc-toggle">
								<span class="acc-name">
									<?= esc_html($term_destination_name) ?>
								</span>
								<span class="acc-chevron" aria-hidden="true">
									<svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none">
									<path d="M19 8L11.3217 15.5002L4 8" stroke="#630F3F" stroke-width="2"/>
									</svg>
								</span>
							</h3>

							<?php if ($hotel_query->have_posts()): ?>
							<div style="<?= $is_first_item ? 'height: auto; opacity: 1;' : 'height: 0px; opacity: 0;' ?>" class="acc-panel">
								<div class="acc-grid">
								<?php while ($hotel_query->have_posts()): $hotel_query->the_post(); ?>
									<?php
										$hotel_id    = get_the_ID();
										$hotel_title = $hotel_id ? get_the_title($hotel_id) : '';
										$hotel_link = $hotel_id ? get_permalink($hotel_id) : '';
										$hotel_desc = $hotel_id ? get_the_excerpt($hotel_id) : '';
										$hotel_information = $hotel_id ? get_field('information', $hotel_id) : [];
										$hotel_gallery_images = $hotel_information['images'] ?? [];
										if(!empty($hotel_gallery_images)) {
											$hotel_gallery_urls = [];
											foreach ($hotel_gallery_images as $img_id) {
												$img_url = wp_get_attachment_image_url($img_id, 'full');
												if ($img_url) {
													$hotel_gallery_urls[] = $img_url;
												}
											}
										} else {
											$hotel_gallery_urls = [];
										}
										$hotel_review_rating = $hotel_information['review_rating'] ?? 0;
										$hotel_google_map_link = $hotel_information['google_map_link'] ?? '';
										if(!empty($hotel_google_map_link)) {
											$hotel_google_map_link_title = $hotel_google_map_link['title'] ?? '';
											$hotel_google_map_link_url = $hotel_google_map_link['url'] ?? '';
										}
									?>
									<button
										data-open-hotel-drawer-trigger  
										data-hotel-title="<?= esc_attr($hotel_title) ?>"
										data-hotel-address-title="<?= esc_attr($hotel_google_map_link_title) ?>"
										data-hotel-address-link="<?= esc_url($hotel_google_map_link_url) ?>"
										data-hotel-rating="<?= esc_attr($hotel_review_rating) ?>"
										data-hotel-description="<?= esc_attr($hotel_desc) ?>"
										data-hotel-gallery-images="<?= esc_attr( wp_json_encode( $hotel_gallery_urls ) ) ?>"
										data-hotel-link="<?= esc_url($hotel_link) ?>"
										type="button"
										class="acc-card js-hotel-popup-trigger"
										data-hotel-id="<?= esc_attr($hotel_slug) ?>"
									>
										<div class="acc-card__img">
											<?php if (has_post_thumbnail()): ?>
												<?= get_the_post_thumbnail(
													$hotel_id,
													'full',
													[
														'class' => 'acc-card__img-el',
														'alt' => esc_attr($hotel_title),
														'loading' => 'lazy',
														'decoding' => 'async',
													]
												); ?>
											<?php endif; ?>
										</div>
										<div class="acc-card__overlay">
											<span class="acc-card__overlay-text">View detail</span>
										</div>
										<h4 class="acc-card__name">
											<?= esc_html($hotel_title) ?>
										</h4>
									</button>
								<?php endwhile; ?>
								</div>
							</div>
							<?php else: ?>
							<div class="acc-panel">
								<p class="acc-panel__empty">No accommodation options available.</p>
							</div>
							<?php endif; ?>
						</article>
						<?php wp_reset_postdata(); ?>
						<?php endforeach; ?>
					</div>
					<?php endif; ?>
				</div>
			</div>
		</div>

		<aside class="itinerary-right">
			<div class="itinerary-cta itinerary-cta--primary">
				<div class="itinerary-cta__icon">
					<?php echo wp_get_attachment_image($icon_phone, 'full', false, ['loading'=>'lazy','decoding'=>'async']) ?>
				</div>
				<h3 class="itinerary-cta__title">
					<?= $contact_us_title; ?>
				</h3>
				<p class="itinerary-cta__desc">
					<?= $contact_us_description; ?>
				</p>
				<?php 
				$tour_id = get_the_ID();
				$tour_slug = get_post_field( 'post_name', $tour_id );
				$contact_params = !empty($tour_slug) ? '?tour=' . $tour_slug : '';
				?>
				<a class="compound-avian-button itinerary-cta__btn" href="/contact<?= $contact_params; ?>" target="<?= $contact_us_link_target ?>">
					<p class="compound-avian-button__content">Contact us for advice</p>
				</a>
			</div>

			<div class="itinerary-cta__line-right"></div>

			<div class="itinerary-cta itinerary-cta--secondary">
				<?php if(!empty($contact_specialist_images)):?>
				<div class="itinerary-cta__avatars">
					<?php foreach($contact_specialist_images as $item): ?>
					<span class="itinerary-cta__avatar">
						<?php echo wp_get_attachment_image($item, 'full', false, ['loading'=>'lazy','decoding'=>'async']) ?>
					</span>
					<?php endforeach; ?>
				</div>
				<?php endif; ?>

				<h3 class="itinerary-cta__title itinerary-cta__title--italic">
					<?= $contact_specialist_title; ?>
				</h3>

				<p class="itinerary-cta__desc itinerary-cta__desc--muted">
					<?= $contact_specialist_description; ?>
				</p>

				<?php if(!empty($contact_specialist_whatsapp) && !empty($contact_specialist_whatsapp_url)): ?>
				<a class="itinerary-cta__wa" href="<?= $contact_specialist_whatsapp_url; ?>" target="<?= $contact_specialist_whatsapp_url; ?>" rel="noopener">
					<span class="itinerary-cta__wa-icon">
						<?php echo wp_get_attachment_image($icon_whatsapp, 'full', false, ['loading'=>'lazy','decoding'=>'async']) ?>
					</span>
					<span>WHATSAPP (<?= $contact_specialist_whatsapp_title; ?>)</span>
				</a>
				<?php endif; ?>
			</div>
		</aside>

	</div>
</section>
