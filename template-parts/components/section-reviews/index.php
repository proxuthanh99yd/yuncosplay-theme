<?php
$section_reviews = get_field('section_reviews', 'option');

$title = $section_reviews['title'];
$trustpilot = $section_reviews['trustpilot'];
$rating = $trustpilot['rating'];
$reviews_count = $trustpilot['reviews'];
$link_trustpilot = $trustpilot['link'];

$review_items = $section_reviews['outstanding_reviews'] ? $section_reviews['outstanding_reviews'] : [];

if ($link_trustpilot) {
	$link_trustpilot_url = $link_trustpilot['url'];
	$link_trustpilot_title = $link_trustpilot['title'] ?? '';
	$link_trustpilot_target = $link_trustpilot['target'] ? $link_trustpilot['target'] : '_self';
}

$rating_icon_id = 1244;
$image_backround = 1223;
$arrow_right_id = 1210;
$trustpilot_star_desktop_id = 1241;
$trustpilot_star_mobile_id = 1242;
$icon_tripadvisor_id = 1919;
?>

<section class="reviews">
	<div class="reviews__container">
		<div class="reviews__content">
			<div class="reviews__title">
				<h2 class="reviews__title-heading"><?= $title ?? ''; ?></h2>
				<?php if (!empty($link_trustpilot_url)) : ?>
				<a href="<?= esc_url($link_trustpilot_url) ?>" target="<?= esc_attr($link_trustpilot_target) ?>" rel="noopener noreferrer" class="trustpilot-badge">
					<span class="trustpilot-text">Rated <strong><?= $rating ?></strong> out of 5 based on <strong><?= $reviews_count ?></strong> reviews on</span>
					<?= wp_get_attachment_image($trustpilot_star_desktop_id, 'full', false, array('class' => 'trustpilot-logo-image')) ?>
					<span class="trustpilot-text"><strong>Trustpilot</strong></span>
				</a>
				<?php endif; ?>
			</div>

			<div class="reviews__slider-wrapper">
				<div id="reviews-swiper" class="reviews__swiper swiper">
					<div class="swiper-wrapper">
						<?php if (!empty($review_items) && is_array($review_items)) : ?>
						<?php foreach ($review_items as $review_id) : ?>
						<?php
						$review_id = (int) $review_id;
						if (!$review_id) continue;

						$post_obj = get_post($review_id);
						if (!$post_obj || $post_obj->post_status !== 'publish') continue;

						setup_postdata($post_obj);

						// ACF fields (lấy theo post ID)
						$avatar        = get_field('avatar', $review_id);
						$tripadvisor_link = get_field('link', $review_id);
						$rate          = get_field('rate', $review_id);
						$rate_type     = get_field('rate_type', $review_id);
						$description   = get_field('description', $review_id);
						$gallery       = get_field('gallery', $review_id);
						$location 	   = get_field('location', $review_id);

						$gallery_urls = [];
						if (!empty($gallery) && is_array($gallery)) {
							$gallery_urls = array_values(array_filter(array_map(function($img_id) {
								$img_id = (int) $img_id;
								return $img_id ? wp_get_attachment_image_url($img_id, 'full') : '';
							}, $gallery)));
						}
						?>
						<div class="swiper-slide">
							<div class="reviews__item"
								 data-review-id="<?= esc_attr($review_id) ?>"
								 data-gallery="<?= !empty($gallery_urls) ? esc_attr(wp_json_encode($gallery_urls)) : '' ?>">

								<div class="reviews__item-social">
									<?php if(!empty($tripadvisor_link) && $tripadvisor_link['url']): ?>
									<?php
									$url    = $tripadvisor_link['url'] ?? '';
									$target = $tripadvisor_link['target'] ? $tripadvisor_link['target'] : '_self';
									?>
									<a href="<?= esc_url($url) ?>"
									   class="reviews__item-link"
									   target="<?= esc_attr($target) ?>"
									   rel="noopener noreferrer"
									   onclick="event.stopPropagation();">
										<?= wp_get_attachment_image($icon_tripadvisor_id, 'full', false, array('class' => 'reviews__item-link-image', 'loading' => 'lazy')) ?>
									</a>
									<?php endif; ?>
								</div>

								<div class="reviews__item-info">
									<?php if ($avatar) : ?>
									<?= wp_get_attachment_image((int) $avatar, 'full', false, ['class' => 'reviews__item-info-avatar']) ?>
									<?php endif; ?>
									<div class="reviews__item-info-text">
										<span class="reviews__item-info-name"><?= esc_html(get_the_title($review_id)) ?></span>
										<span class="reviews__item-info-location">
											<?= $location; ?>
										</span>
									</div>
								</div>

								<div class="reviews__item-rating">
									<?php for ($i = 0; $i < (int) $rate; $i++) : ?>
									<?= wp_get_attachment_image($rating_icon_id, 'full', false, ['class' => 'reviews__item-rating-icon']) ?>
									<?php endfor; ?>
								</div>

								<div class="reviews__item-review">
									<span class="reviews__item-review-title"><?= esc_html($rate_type) ?></span>
									<div class="reviews__item-review-description">
										<?= wpautop(wp_kses_post($description)) ?>
									</div>
								</div>

							</div>
						</div>
						<?php endforeach; ?>
						<?php wp_reset_postdata(); ?>
						<?php endif; ?>
					</div>
				</div>
				<div class="reviews__navigation">
					<button class="reviews__button reviews__button--prev" aria-label="Previous slide">
						<?= wp_get_attachment_image($arrow_right_id, 'full', false, array('class' => 'reviews__button-icon')) ?>
					</button>

					<?php if (!empty($link_trustpilot_url)) : ?>
					<a href="<?= esc_url($link_trustpilot_url) ?>"
					   target="<?= esc_attr($link_trustpilot_target) ?>"
					   rel="noopener noreferrer"
					   class="trustpilot-badge--mobile">
						<span class="trustpilot-text">
							Rated&nbsp;<strong><?= $rating ?></strong>&nbsp;out of 5 based on
						</span>
						<span class="trustpilot-text">
							<strong><?= $reviews_count ?> reviews</strong>&nbsp;on
							<?= wp_get_attachment_image($trustpilot_star_mobile_id, 'full', false, ['class' => 'trustpilot-logo-image']) ?>
							<strong>Trustpilot</strong>
						</span>
					</a>
					<?php endif; ?>

					<button class="reviews__button reviews__button--next" aria-label="Next slide">
						<?= wp_get_attachment_image($arrow_right_id, 'full', false, array('class' => 'reviews__button-icon')) ?>
					</button>
				</div>
			</div>
		</div>
	</div>
</section>