<?php
$section_hotels = get_field('list');
$title = $section_hotels['title'] ?? '';
$desc  = $section_hotels['desc'] ?? '';

// Terms
$destination_terms = get_terms([
	'taxonomy'   => 'destination',
	'hide_empty' => false,
	'orderby'    => 'name',
	'order'      => 'ASC',
]);

$rating_terms = get_terms([
	'taxonomy'   => 'rating-of-the-property',
	'hide_empty' => false,
	'orderby'    => 'term_id',
	'order'      => 'ASC',
]);

$parent_countries = [];
$child_cities = [];

if (!is_wp_error($destination_terms) && !empty($destination_terms)) {
	foreach ($destination_terms as $t) {
		if ((int) $t->parent === 0) $parent_countries[] = $t;
		else $child_cities[] = $t;
	}
}

/**
 * SSR page: lấy từ query string "paged" (vì bạn đang dùng API paged)
 * - ưu tiên $_GET['paged'] vì section này không phải archive page chuẩn
 * - fallback get_query_var('paged') nếu có
 */
$paged = 1;
if (isset($_GET['paged'])) {
	$paged = max(1, (int) $_GET['paged']);
} else {
	$paged = max(1, (int) get_query_var('paged'));
}

$limit = 12;

$q = new WP_Query([
	'post_type'      => 'hotel',
	'post_status'    => 'publish',
	'posts_per_page' => $limit,
	'paged'          => $paged,
	'orderby'        => 'modified',
	'order'          => 'DESC',
]);

$total_posts = (int) $q->found_posts;
$total_pages = (int) $q->max_num_pages;
$progress    = $total_pages > 0 ? round(($paged / $total_pages) * 100) : 100;

$endpoint = home_url('/wp-json/api/v1/get-all/hotel');
$rating_icon_url = wp_get_attachment_image_url(2002, 'full');
?>
<section
		 id="hotels"
		 data-hotels
		 data-rating-icon-url="<?= esc_url($rating_icon_url ?: '') ?>"
		 data-endpoint="<?= esc_url($endpoint) ?>"
		 data-page="<?= esc_attr($paged) ?>"
		 data-limit="<?= esc_attr($limit) ?>"
		 data-total="<?= esc_attr($total_posts) ?>"
		 data-total-pages="<?= esc_attr(max(1, $total_pages)) ?>"
		 >
	<div class="container">
		<h2 class="hotels__title"><?= esc_html($title) ?></h2>
		<p class="hotels__desc"><?= esc_html($desc) ?></p>

		<div class="hotels__list">
			<!-- FILTER -->
			<div class="hotels__list-filter" data-hotels-filter>

				<!-- Country -->
				<div class="hotels__list-filter-item hotels__list-filter-item--dropdown" data-filter-country>
					<div class="hotels__list-filter-item-header">
						<p>Where in Indochina</p>
						<svg class="hotels__list-filter-item-arrow" xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 16 16" fill="none">
							<path d="M12.6654 6L7.9987 10.6667L3.33203 6" stroke="#2E2E2E" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/>
						</svg>
					</div>

					<div class="hotels__list-filter-dropdown">
						<?php if (!empty($parent_countries)): ?>
						<?php foreach ($parent_countries as $country): ?>
						<label class="hotels__list-filter-dropdown-item">
							<input
								   type="checkbox"
								   name="indochina"
								   value="<?= esc_attr($country->slug) ?>"
								   data-parent-term-id="<?= esc_attr($country->term_id) ?>"
								   >
							<span class="hotels__list-filter-checkbox"></span>
							<span class="hotels__list-filter-label"><?= esc_html($country->name) ?></span>
						</label>
						<?php endforeach; ?>
						<?php endif; ?>
					</div>
				</div>

				<!-- Cities -->
				<div class="hotels__list-filter-item hotels__list-filter-item--dropdown" data-filter-cities>
					<div class="hotels__list-filter-item-header">
						<p>Cities & Places</p>
						<svg class="hotels__list-filter-item-arrow" xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 16 16" fill="none">
							<path d="M12.6654 6L7.9987 10.6667L3.33203 6" stroke="#2E2E2E" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/>
						</svg>
					</div>

					<div class="hotels__list-filter-dropdown">
						<div class="hotels__list-filter-search">
							<input type="text" class="hotels__list-filter-search-input" placeholder="Enter search content" data-city-search>
							<svg class="hotels__list-filter-search-icon" xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 16 16" fill="none">
								<path d="M7.33333 12.6667C10.2789 12.6667 12.6667 10.2789 12.6667 7.33333C12.6667 4.38781 10.2789 2 7.33333 2C4.38781 2 2 4.38781 2 7.33333C2 10.2789 4.38781 12.6667 7.33333 12.6667Z" stroke="#2E2E2E" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/>
								<path d="M14 14L11.1 11.1" stroke="#2E2E2E" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/>
							</svg>
						</div>

						<div class="hotels__list-filter-dropdown-content custom-scrollbar" data-lenis-prevent>
							<div class="hotels__list-filter-empty-message" id="citiesEmptyMessage">
								<p>Please select a country above to see cities and locations.</p>
							</div>

							<?php if (!empty($child_cities)): ?>
							<?php foreach ($child_cities as $city): ?>
							<label class="hotels__list-filter-dropdown-item" data-parent-id="<?= esc_attr($city->parent) ?>" style="display:none;">
								<input type="checkbox" name="cities" value="<?= esc_attr($city->slug) ?>">
								<span class="hotels__list-filter-checkbox"></span>
								<span class="hotels__list-filter-label"><?= esc_html($city->name) ?></span>
							</label>
							<?php endforeach; ?>
							<?php endif; ?>
						</div>
					</div>
				</div>

				<!-- Rating -->
				<div class="hotels__list-filter-item hotels__list-filter-item--dropdown" data-filter-rating>
					<div class="hotels__list-filter-item-header">
						<div class="hotels__list-filter-item-content">
							<p>Rating of the property</p>
							<?php echo wp_get_attachment_image(1955, 'full', false, array( 'class' => 'hotels__list-filter-item-icon')) ?>
						</div>
						<svg class="hotels__list-filter-item-arrow" xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 16 16" fill="none">
							<path d="M12.6654 6L7.9987 10.6667L3.33203 6" stroke="#2E2E2E" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/>
						</svg>
					</div>

					<div class="hotels__list-filter-dropdown">
						<?php if (!empty($rating_terms) && !is_wp_error($rating_terms)): ?>
						<?php foreach ($rating_terms as $rt): ?>
						<label class="hotels__list-filter-dropdown-item">
							<input type="checkbox" name="rating" value="<?= esc_attr($rt->slug) ?>">
							<span class="hotels__list-filter-checkbox"></span>
							<div class="hotels__list-filter-label-wrapper">
								<span class="hotels__list-filter-label <?= !empty($rt->description) ? 'hotels__list-filter-label--with-description' : '' ?>"><?= esc_html($rt->name) ?></span>
								<?php if (!empty($rt->description)): ?>
								<p class="hotels__list-filter-description"><?= esc_html($rt->description) ?></p>
								<?php endif; ?>
							</div>
						</label>
						<?php endforeach; ?>
						<?php endif; ?>
					</div>
				</div>

			</div>

			<!-- Mobile Filter Popup -->
			<div class="hotels__filter-popup" id="hotelsFilterPopup">
				<div class="hotels__filter-popup-overlay"></div>
				<div class="hotels__filter-popup-content">
					<div class="hotels__filter-popup-header">
						<h3 class="hotels__filter-popup-title"></h3>
						<button class="hotels__filter-popup-close" aria-label="Close filter popup">
							<svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 16 16" fill="none">
								<path d="M12 4L4 12M4 4L12 12" stroke="#630F3F" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/>
							</svg>
						</button>
					</div>
					<div class="hotels__filter-popup-body"></div>
				</div>
			</div>

			<!-- LIST -->
			<div class="hotels__list-content" data-hotels-content>
				<div class="hotels__list-items" data-hotels-list>
					<?php if ($q->have_posts()): ?>
					<?php while ($q->have_posts()): $q->the_post(); ?>
					<?php
					$hotel_id = get_the_ID();
					$thumb_id = get_post_thumbnail_id($hotel_id);
					$destinations = get_the_terms($hotel_id, 'destination');
					$ratings = get_the_terms($hotel_id, 'rating-of-the-property');

					$dest_names = (!empty($destinations) && !is_wp_error($destinations)) ? wp_list_pluck($destinations, 'name') : [];
					$rating_names = (!empty($ratings) && !is_wp_error($ratings)) ? wp_list_pluck($ratings, 'name') : [];
					?>
					<a class="hotel-card" href="<?= esc_url(get_the_permalink()) ?>">
						<div class="hotel-card__overlay"></div>
						<?php if ($thumb_id): ?>
						<?= wp_get_attachment_image($thumb_id, 'full', false, ['class' => 'hotel-card__image', 'loading' => 'lazy']) ?>
						<?php endif; ?>
						<div class="hotel-card__content">
							<h3 class="hotel-card__title"><?= esc_html(get_the_title()) ?></h3>
							<hr class="hotel-card__divider"/>
							<div class="hotel-card__info">
								<?php if (!empty($rating_names[0])): ?>
								<div class="hotel-card__info-item">
									<div class='hotel-card__info-item-wrapper'>
										<?= wp_get_attachment_image(2002, 'full', false, array( 'class' => 'hotel-card__info-item-label-image')) ?>
										<p class="hotel-card__info-item-label">Rating</p>
									</div>
									<span class="hotel-card__info-item-value"><?= esc_html($rating_names[0]) ?></span>
								</div>
								<?php endif; ?>

								<?php if (!empty($dest_names[1]) || !empty($dest_names[0])): ?>
								<div class="hotel-card__info-item">
									<span class="hotel-card__info-item-label">Location</span>
									<span class="hotel-card__info-item-value"><?= esc_html($dest_names[1] ?? $dest_names[0]) ?></span>
								</div>
								<?php endif; ?>
							</div>
						</div>
					</a>
					<?php endwhile; wp_reset_postdata(); ?>
					<?php endif; ?>
				</div>

				<!-- TEMPLATE cho JS -->
				<template id="template-hotel-card">
					<a class="hotel-card" href="#">
						<div class="hotel-card__overlay"></div>
						<img class="hotel-card__image" src="" alt="" loading="lazy" />
						<div class="hotel-card__content">
							<h3 class="hotel-card__title"></h3>
							<hr class="hotel-card__divider"/>
							<div class="hotel-card__info">
								<div class="hotel-card__info-item hotel-card__info-item--rating" style="display:none;">
									<div class="hotel-card__info-item-wrapper">
										<img class="hotel-card__info-item-label-image" src="" alt="" data-rating-icon>
										<p class="hotel-card__info-item-label">Rating</p>
									</div>
									<span class="hotel-card__info-item-value"></span>
								</div>
								<div class="hotel-card__info-item hotel-card__info-item--location" style="display:none;">
									<span class="hotel-card__info-item-label">Location</span>
									<span class="hotel-card__info-item-value"></span>
								</div>
							</div>
						</div>
					</a>
				</template>

				<template id="template-hotel-skeleton">
					<div class="hotel-card hotel-card--skeleton" aria-hidden="true">
						<div class="hotel-card__image skeleton skeleton--media"></div>
						<div class="hotel-card__overlay"></div>
						<div class="hotel-card__content">
							<div class="hotel-card__title skeleton skeleton--title"></div>
							<hr class="hotel-card__divider"/>
							<div class="hotel-card__info">
								<div class="hotel-card__info-item">
									<span class="hotel-card__info-item-label skeleton skeleton--label"></span>
									<span class="hotel-card__info-item-value skeleton skeleton--value"></span>
								</div>
								<div class="hotel-card__info-item">
									<span class="hotel-card__info-item-label skeleton skeleton--label"></span>
									<span class="hotel-card__info-item-value skeleton skeleton--value"></span>
								</div>
							</div>
						</div>
					</div>
				</template>

				<!-- PAGINATION -->
				<nav class="pagination" aria-label="Hotel pagination" data-hotels-pagination>
					<p class="pagination__text" data-pagination-text>
						<?= $total_posts
	? "You've viewed " . esc_html(min($paged * $limit, $total_posts)) . " of " . esc_html($total_posts) . " articles"
	: "Page " . esc_html($paged) . " of " . esc_html(max(1, $total_pages))
						?>
					</p>

					<div class="pagination__bar">
						<button
								type="button"
								class="pagination__prev <?= $paged <= 1 ? 'is-disabled' : '' ?>"
								data-page-prev
								<?= $paged <= 1 ? 'disabled' : '' ?>
								>
							<svg xmlns="http://www.w3.org/2000/svg" width="22" height="22" viewBox="0 0 22 22" fill="none">
								<path d="M13.4709 17.8872L7.61623 12.0326C6.9248 11.3411 6.9248 10.2097 7.61623 9.51827L13.4709 3.66357" stroke="#292D32" stroke-width="1.5" stroke-miterlimit="10" stroke-linecap="round" stroke-linejoin="round" />
							</svg>
						</button>

						<div class="pagination__progress">
							<div class="pagination__progress-inner" style="width: <?= esc_attr($progress) ?>%" data-pagination-progress></div>
						</div>

						<button
								type="button"
								class="pagination__next <?= $paged >= $total_pages ? 'is-disabled' : '' ?>"
								data-page-next
								<?= ($paged >= $total_pages && $total_pages > 0) ? 'disabled' : '' ?>
								>
							<svg xmlns="http://www.w3.org/2000/svg" width="22" height="22" viewBox="0 0 22 22" fill="none">
								<path d="M8 17.8872L13.8547 12.0326C14.5461 11.3411 14.5461 10.2097 13.8547 9.51827L8 3.66357" stroke="#292D32" stroke-width="1.34694" stroke-miterlimit="10" stroke-linecap="round" stroke-linejoin="round" />
							</svg>
						</button>
					</div>
				</nav>

			</div>
		</div>
	</div>

	<div class="hotels__rating-popup" id="hotelsRatingPopup">
		<div class="hotels__rating-popup-overlay"></div>
		<div class="hotels__rating-popup-content">
			<div class="hotels__rating-popup-header">
				<div class="hotels__rating-popup-header-top">
					<h3 class="hotels__rating-popup-title">Our rating</h3>
					<button class="hotels__rating-popup-close" aria-label="Close popup">
						<svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 16 16" fill="none">
							<path d="M12 4L4 12M4 4L12 12" stroke="#fff" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/>
						</svg>
					</button>
				</div>
			</div>
			<div class="hotels__rating-popup-body">
				<?php foreach ($rating_terms as $rating) { ?>
				<div class="hotels__rating-popup-item">
					<strong class="hotels__rating-popup-item-title"><?= esc_html($rating->name); ?>:</strong>
					<p class="hotels__rating-popup-item-desc"><?= esc_html($rating->description); ?></p>
				</div>
				<?php } ?>
			</div>
		</div>
	</div>
</section>
