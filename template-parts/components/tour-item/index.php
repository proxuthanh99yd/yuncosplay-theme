<?php
$icon_destination = 1365;
$icon_line = 2477;

$tour_id    = get_the_ID();
$tour_title = get_the_title();
$tour_link  = get_permalink();
$fallback_thumb_id = 1916;


$thumb_id = get_post_thumbnail_id($tour_id) ? get_post_thumbnail_id($tour_id) : $fallback_thumb_id;
$thumb_html = $thumb_id
	? wp_get_attachment_image(
	$thumb_id,
	'full',
	false,
	[
		'class' => 'tour-item__thumbnail-img',
		'alt' => esc_attr($tour_title),
		'loading' => 'lazy',
		'decoding' => 'async',
	]
)
	: '';

$desc = get_the_excerpt();

$price     = get_field('tour_price') ?? '0';
$duration  = get_field('tour_duration') ?? '0';
$leaving_from_text = get_field('leaving_from') ?? '';


$duration_text = (int)$duration > 1 ? $duration . ' days' : $duration . ' day';
$price_from_text = $duration_text . ' from ' . '$ ' . $price;

$tour_dest_terms = get_the_terms($tour_id, 'destination');
$tour_destination_name = (!empty($tour_dest_terms) && !is_wp_error($tour_dest_terms))
	? $tour_dest_terms[0]->name
	: 'Featured';
?>

<article class="tour-item">
	<a href="<?= esc_url($tour_link) ?>" class="tour-item__link" aria-label="<?= esc_attr($tour_title) ?>"></a>

	<div class="tour-item__thumbnail">
		<?= $thumb_html ?>
	</div>

	<div class="tour-item__content">
		<h3 class="tour-item__content-title">
			<?= esc_html($tour_title) ?>
		</h3>

		<?php if ($desc): ?>
		<p class="tour-item__content-desc">
			<?= esc_html(wp_trim_words($desc, 28)) ?>
		</p>
		<?php endif; ?>

		<div class="tour-item__content-meta">
			<div class="tour-item__content-meta__item tour-item__content-meta__item--leaving-from">
				<span class="tour-item__content-meta__item__label"><?= IS_MOBILE ? 'Departing from':'Leaving from'; ?></span>
				<span class="tour-item__content-meta__item__value">
					<?= !empty($leaving_from_text) ? esc_html($leaving_from_text) : '---' ?>
				</span>
			</div>

			<div class="tour-item__content-meta__item tour-item__content-meta__item--starting-price">
				<span class="tour-item__content-meta__item__label"><?= IS_MOBILE ? 'Price from':'Starting price at'; ?></span>
				<span class="tour-item__content-meta__item__value">
					<?= !empty($price_from_text) ? esc_html($price_from_text) : '---' ?>
				</span>
			</div>
		</div>

		<div class="tour-item__content-divider">
			<?= wp_get_attachment_image($icon_line,'full',false,['loading' => 'lazy','decoding' => 'async',]); ?>
		</div>

		<div class="tour-item__content-footer">
			<span class="tour-item__content-footer__view-detail">
				View this tour
			</span>

			<div class="tour-item__content-footer__destination">
				<span class="tour-item__content-footer__destination-text">
					<?= esc_html($tour_destination_name) ?>
				</span>
				<span class="tour-item__content-footer__destination-icon">
					<?= wp_get_attachment_image($icon_destination,'full',false,['loading' => 'lazy','decoding' => 'async']); ?>
				</span>
			</div>
		</div>
	</div>
</article>
